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
    $docs = Document::whereIn('id', $request->input('docs'))->get();

    if ($docs->isEmpty()) {
        return redirect()->route('vendor.documents.index')->with('error', 'Tidak ada dokumen yang dipilih.');
    }

    $zip = new ZipArchive();
    $zipFileName = 'dokumen.zip';
    $tempPath = storage_path('app/public/zip_temp/');

    // Verifikasi apakah folder ada
    if (!is_dir($tempPath)) {
        mkdir($tempPath, 0777, true); // Buat folder jika belum ada
    }

    // Path untuk menyimpan file ZIP
    $zipPath = $tempPath . $zipFileName;

    // Verifikasi Path
    Log::info('Path untuk ZIP: ' . $zipPath);

    if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
        Log::error('Gagal membuka file ZIP pada path: ' . $zipPath);
        return redirect()->route('vendor.documents.index')->with('error', 'Tidak dapat membuat file ZIP.');
    }

    // Menambahkan file-file yang dipilih ke dalam arsip ZIP
    foreach ($docs as $document) {
        $filePath = storage_path('app/' . $document->file_path);  // Sesuaikan path dengan penyimpanan Anda
        if (file_exists($filePath)) {
            $zip->addFile($filePath, $document->original_name);
        } else {
            Log::error('File tidak ditemukan: ' . $filePath);
        }
    }

    // Menutup file ZIP setelah menambahkan semua file
    $closeStatus = $zip->close();
    if ($closeStatus !== true) {
        Log::error('Gagal menutup file ZIP. Kode error: ' . $closeStatus);
        return redirect()->route('vendor.documents.index')->with('error', 'Gagal menutup file ZIP.');
    }

    // Mengunduh file ZIP dan menghapusnya setelah pengunduhan
    return response()->download($zipPath)->deleteFileAfterSend(true);
}

}