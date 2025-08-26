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

    // 1. Mencari vendor berdasarkan user yang sedang login
    $vendor = Vendor::query()
        ->when(!empty($user->vendor_id ?? null), fn($q) => $q->where('id', $user->vendor_id))
        ->when(empty($user->vendor_id ?? null) && !empty($user->npwp ?? null), fn($q) => $q->where('npwp', preg_replace('/\D/', '', $user->npwp)))
        ->first();

    if (!$vendor) {
        return redirect()->route('vendor.documents.index')->with('error', 'Akun vendor tidak terhubung.');
    }

    // 2. Mengambil dokumen yang dipilih
    $docs = Document::whereIn('id', $request->input('docs'))
                    ->where('vendor_id', $vendor->id)
                    ->get();

    if ($docs->isEmpty()) {
        return redirect()->route('vendor.documents.index')->with('error', 'Tidak ada dokumen yang dipilih atau dokumen tidak ditemukan.');
    }

    $zip = new ZipArchive();
    $zipFileName = 'dokumen_' . now()->timestamp . '.zip';
    $zipPath = storage_path('app/public/zip_temp/' . $zipFileName);

    Storage::disk('public')->makeDirectory('zip_temp');

    $res = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    if ($res !== true) {
        $error = "Tidak dapat membuat file ZIP. Kode error: " . $res . " - " . $this->getZipErrorMessage($res);
        Log::error($error . ' pada path: ' . $zipPath);
        return redirect()->route('vendor.documents.index')->with('error', 'Tidak dapat membuat file ZIP.');
    }
    
    // 3. Mengambil NPWP dari vendor dan membersihkannya
    $vendorNpwp = preg_replace('/\D/', '', $vendor->npwp ?? null);

    // 4. Menambahkan file ke ZIP
    foreach ($docs as $document) {
        // ==================================================================
        // PERBAIKAN: Menambahkan periode ke dalam jalur file
        // ==================================================================
        $filePath = 'bukti_pajak/' . $vendorNpwp . '/' . $document->period . '/' . $document->stored_name;

        // Memeriksa keberadaan file di disk 'private'
        if ($document->stored_name && Storage::disk('private')->exists($filePath)) {
            Log::info('Menambahkan file ke ZIP: ' . $filePath);
            $zip->addFile(Storage::disk('private')->path($filePath), $document->original_name);
        } else {
            Log::warning('Dokumen dengan ID ' . $document->id . ' dilewati. Jalur file tidak valid: ' . $filePath);
        }
    }

    $zip->close();

    // 5. Mengirimkan file ZIP untuk diunduh
    if (file_exists($zipPath)) {
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    return redirect()->route('vendor.documents.index')->with('error', 'Terjadi kesalahan saat membuat file ZIP.');
}


}