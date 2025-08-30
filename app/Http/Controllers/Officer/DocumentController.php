<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    /**
     * Daftar vendor untuk unggah/lihat dokumen.
     * - Pencarian ?q= (NPWP/Nama)
     * - withCount('documents') + latestDocument (kolom minimal)
     * - HTMX: bila HX-Request → balas partial tabel saja
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $vendors = Vendor::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('npwp', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$q}%");
                });
            })
            ->withCount('documents')
            ->with([
                'latestDocument' => function ($sub) {
                    // pilih kolom minimal agar tidak ambiguous
                    $sub->select('documents.id', 'documents.vendor_id', 'documents.created_at');
                },
            ])
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // Jika request dari HTMX → kirim PARTIAL tabel saja
        if ($request->header('HX-Request')) {
            return view('officer.partials.vendor_table', [
                'vendors' => $vendors,
                'q'       => $q,
            ]);
        }

        // Akses normal → kirim HALAMAN penuh
        return view('officer.documents.index', [
            'vendors' => $vendors,
            'q'       => $q,
        ]);
    }

    /**
     * Halaman dokumen per vendor.
     */
    public function showVendor(Vendor $vendor)
    {
        $docs = $vendor->documents()
            ->with('uploader')   // dipakai di blade: $d->uploader->name
            ->latest()
            ->paginate(20);

        return view('officer.documents.vendor', compact('vendor', 'docs'));
    }

    /**
     * Simpan dokumen bukti pajak (PRIVATE storage).
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $vendor = Vendor::findOrFail($request->vendor_id);
        $file   = $request->file('file');

        // Hash sebelum dipindah (stabil)
        $sha256     = hash_file('sha256', $file->getRealPath());
        $storedName = uniqid('doc_') . '.' . $file->getClientOriginalExtension();
        $dir        = "bukti_pajak/{$vendor->npwp}/{$request->period}";

        // Simpan ke disk "private"
        $path = $file->storeAs($dir, $storedName, 'private');

        // Simpan metadata
        Document::create([
            'vendor_id'     => $vendor->id,
            'uploaded_by'   => $request->user()->id,
            'period'        => $request->period,
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => $storedName,
            'mime'          => $file->getMimeType() ?: $file->getClientMimeType(),
            'size'          => $file->getSize(),
            'hash'          => $sha256,
            'path'          => $path, // contoh: bukti_pajak/NPWP/2025-08/doc_xxx.pdf
            // 'disk'        => 'private', // isi hanya bila kolom 'disk' ada
        ]);

        return back()->with('ok', 'Dokumen tersimpan');
    }

    /**
     * Unduh dokumen secara privat.
     */
    public function download(Document $document): BinaryFileResponse
    {
        $disk = $document->disk ?? 'private';

        if (! Storage::disk($disk)->exists($document->path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $downloadName = $document->original_name ?: $document->stored_name;
        $filePath     = Storage::disk($disk)->path($document->path);

        return response()->download($filePath, $downloadName);
    }

    /**
     * Hapus dokumen dari storage & database.
     */
    public function destroy(Request $request, Document $document): RedirectResponse
    {
        $disk = $document->disk ?? 'private';

        if (Storage::disk($disk)->exists($document->path)) {
            Storage::disk($disk)->delete($document->path);
        }

        $document->delete();

        return back()->with('ok', 'Dokumen dihapus');
    }
}
