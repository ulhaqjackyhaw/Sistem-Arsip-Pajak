<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Vendor;
use Illuminate\Http\Request;
use ZipArchive;
use Storage;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    // Method untuk menampilkan daftar dokumen
    public function index(Request $request)
    {
        $user = $request->user();

        // Cari vendor berdasarkan pengguna yang sedang login
        $vendor = Vendor::query()
            ->when(!empty($user->vendor_id ?? null), fn($q) => $q->where('id', $user->vendor_id))
            ->when(empty($user->vendor_id ?? null) && !empty($user->npwp ?? null), fn($q) => $q->where('npwp', preg_replace('/\D/', '', $user->npwp)))
            ->first();

        $docs = collect(); // Koleksi kosong jika tidak ada vendor

        if ($vendor) {
            $q = trim((string) $request->input('q', ''));
            $period = $request->input('period'); // Format HTML <input type="month"> = YYYY-MM

            // Query untuk mengambil dokumen berdasarkan pencarian dan filter periode
            $docs = Document::query()
                ->where('vendor_id', $vendor->id)
                ->when($q !== '', function ($query) use ($q) {
                    $query->where(function ($qq) use ($q) {
                        $qq->where('original_name', 'like', "%{$q}%")
                            ->orWhere('stored_name', 'like', "%{$q}%");
                    });
                })
                ->when($period, fn($query) => $query->where('period', $period))
                ->orderByDesc('created_at')
                ->paginate(20)
                ->withQueryString(); // Agar query string tetap terbawa saat melakukan pagination
        }

        return view('vendor.documents.index', [
            'vendor' => $vendor,
            'docs'   => $docs,
        ]);
    }

    // Method untuk menangani pengunduhan ZIP dari dokumen yang dipilih
    public function downloadZip(Request $request)
{
    $user = $request->user();

    // Cari vendor dengan logika yang sama seperti di metode index
    $vendor = Vendor::query()
        ->when(!empty($user->vendor_id ?? null), fn($q) => $q->where('id', $user->vendor_id))
        ->when(empty($user->vendor_id ?? null) && !empty($user->npwp ?? null), fn($q) => $q->where('npwp', preg_replace('/\D/', '', $user->npwp)))
        ->first();

    // Jika vendor tidak ditemukan, lempar pesan kesalahan
    if (!$vendor) {
        return redirect()->route('vendor.documents.index')->with('error', 'Akun vendor tidak terhubung.');
    }

    $docs = Document::whereIn('id', $request->input('docs'))
                    ->where('vendor_id', $vendor->id)
                    ->get();

    if ($docs->isEmpty()) {
        return redirect()->route('vendor.documents.index')->with('error', 'Tidak ada dokumen yang dipilih atau dokumen tidak ditemukan.');
    }

    $zip = new ZipArchive();
    $zipFileName = 'dokumen_' . now()->timestamp . '.zip';
    $zipPath = storage_path('app/public/zip_temp/' . $zipFileName);

    // Pastikan direktori zip_temp ada
    Storage::disk('public')->makeDirectory('zip_temp');

    // Coba buka file ZIP
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        Log::error('Gagal membuat file ZIP pada path: ' . $zipPath);
        return redirect()->route('vendor.documents.index')->with('error', 'Tidak dapat membuat file ZIP.');
    }

    // Tambahkan file-file ke dalam ZIP
    foreach ($docs as $document) {
        // PERBAIKAN: Gunakan stored_name dan bangun jalur file secara manual
        $filePath = 'documents/vendor/' . $document->vendor_id . '/' . $document->stored_name;

        // Pastikan jalur file tidak null dan file ada sebelum ditambahkan ke ZIP
        if ($document->stored_name && Storage::disk('local')->exists($filePath)) {
            // Log jika file berhasil ditambahkan
            Log::info('Menambahkan file ke ZIP: ' . $filePath);
            $zip->addFile(Storage::path($filePath), $document->original_name);
        } else {
            // Catat jika file tidak ada atau nama file kosong
            Log::warning('Dokumen dengan ID ' . $document->id . ' dilewati. Jalur file tidak valid: ' . $filePath);
        }
    }

    // Menutup file ZIP
    $zip->close();

    // Cek apakah file ZIP telah dibuat dan kembalikan ke pengguna
    if (file_exists($zipPath)) {
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    return redirect()->route('vendor.documents.index')->with('error', 'Terjadi kesalahan saat membuat file ZIP.');
}


}