<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;
use App\Models\Vendor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    /**
     * Daftar vendor untuk unggah/lihat dokumen.
     * - Support pencarian ?q= (NPWP / Nama)
     * - Hitung jumlah dokumen per vendor (documents_count)
     * - Ambil waktu unggah terakhir via relasi latestDocument (aman dari kolom ambigu)
     */
    public function index(Request $request): View
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
                'latestDocument' => function ($q) {
                    // prefix nama kolom agar tidak "ambiguous"
                    $q->select('documents.id', 'documents.vendor_id', 'documents.created_at');
                }
            ])
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('officer.documents.index', compact('vendors', 'q'));
    }

    /**
     * Halaman dokumen per vendor.
     */
    public function showVendor(Vendor $vendor): View
    {
        $docs = $vendor->documents()->latest()->paginate(20);
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
            'path'          => $path,     // contoh: bukti_pajak/NPWP/2025-08/doc_xxx.pdf
            // 'disk'       => 'private', // hanya isi jika kolom 'disk' memang ada di tabel
        ]);

        return back()->with('ok', 'Dokumen tersimpan');
    }

    /**
     * Unduh dokumen secara privat (via controller).
     */
    public function download(Document $document): StreamedResponse
    {
        $disk = $document->disk ?? 'private';

        if (!Storage::disk($disk)->exists($document->path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $downloadName = $document->original_name ?: $document->stored_name;

        return Storage::disk($disk)->download($document->path, $downloadName);
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
