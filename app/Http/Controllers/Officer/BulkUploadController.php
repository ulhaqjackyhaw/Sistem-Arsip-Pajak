<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Vendor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BulkUploadController extends Controller
{
    /**
     * Tampilkan form bulk upload.
     */
    public function form(): View
    {
        return view('officer.bulk.index');
    }

    /**
     * Proses unggah banyak PDF sekaligus.
     *
     * Mendukung nama file dengan prefix BLU_ atau BPU_ (case-insensitive),
     * NPWP harus langsung setelah prefix, periode bisa muncul dalam berbagai format:
     * - YYYY-MM
     * - pasangan token terpisah MM YYYY atau YYYY MM
     * - YYYYMM / MMYYYY
     * - DDMMYYYY (bahkan jika tertanam pada token panjang, akan dipindai sub-string 8 digit)
     */
    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'files'   => ['required', 'array', 'min:1', 'max:300'],
            'files.*' => ['file', 'mimes:pdf', 'max:20480'], // 20 MB per file
        ]);

        $results = ['success' => [], 'failed' => []];

        /** @var \Illuminate\Http\UploadedFile $file */
        foreach ($request->file('files') as $file) {
            $original = $file->getClientOriginalName();

            // 1) Deteksi meta dari nama file
            $meta = $this->parseFromName($original);
            if (!$meta) {
                $results['failed'][] = [
                    'name'   => $original,
                    'reason' => 'Nama file tidak sesuai pola: harus diawali BLU_ atau BPU_, lalu NPWP. Periode akan dicari otomatis dari nama.',
                ];
                continue;
            }

            // 2) Cari vendor: prioritas NPWP, fallback ke nama (longgar)
            $vendor = Vendor::where('npwp', $meta['npwp'])->first();
            if (!$vendor && $meta['vendorName']) {
                $vn = trim($meta['vendorName']);
                $vendor = Vendor::whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($vn).'%'])->first();
            }

            if (!$vendor) {
                $results['failed'][] = [
                    'name'   => $original,
                    'reason' => 'Vendor tidak ditemukan (NPWP: '.$meta['npwp'].').',
                ];
                continue;
            }

            // 3) Siapkan path simpan
            $period     = $meta['period']; // format YYYY-MM
            $dir        = "bukti_pajak/{$vendor->npwp}/{$period}";
            $storedName = uniqid('doc_').'.'.$file->getClientOriginalExtension();

            // 4) Hitung hash untuk deteksi duplikat
            $hash = hash_file('sha256', $file->getRealPath());

            // 5) Cegah duplikat: vendor + periode + hash sama
            $duplicate = Document::where('vendor_id', $vendor->id)
                ->where('period', $period)
                ->where('hash', $hash)
                ->exists();
            if ($duplicate) {
                $results['failed'][] = [
                    'name'   => $original,
                    'reason' => 'Duplikat: file dengan konten sama sudah ada pada periode tersebut.',
                ];
                continue;
            }

            // 6) Simpan file ke disk "private"
            $path = $file->storeAs($dir, $storedName, 'private');

            // 7) Simpan metadata ke DB
            Document::create([
                'vendor_id'     => $vendor->id,
                'uploaded_by'   => $request->user()->id,
                'period'        => $period,
                'original_name' => $original,
                'stored_name'   => $storedName,
                'mime'          => $file->getMimeType() ?: $file->getClientMimeType(),
                'size'          => $file->getSize(),
                'hash'          => $hash,
                'path'          => $path, // contoh: bukti_pajak/NPWP/2025-08/doc_xxx.pdf
                // kolom 'disk' opsional; kalau tidak ada di migration, jangan diset
            ]);

            $results['success'][] = [
                'name'   => $original,
                'vendor' => $vendor->name,
                'npwp'   => $vendor->npwp,
                'period' => $period,
            ];
        }

        return back()->with('results', $results);
    }

    /**
     * Parser nama file → {npwp, period(YYYY-MM), vendorName?}
     *
     * Dukung prefix: BLU_ atau BPU_ (+ spasi/dash/underscore).
     * NPWP (8–20 digit) harus langsung setelah prefix.
     * Periode dicari di sisa nama: YYYY-MM | (MM YYYY / YYYY MM) | YYYYMM/MMYYYY | DDMMYYYY (di mana saja).
     */
    private function parseFromName(string $filename): ?array
    {
        $stem = pathinfo($filename, PATHINFO_FILENAME); // tanpa ekstensi

        // 1) Prefix BLU_ atau BPU_
        if (!preg_match('/^(?:BLU|BPU)[\s_\-]+(.+)$/i', $stem, $m0)) {
            return null;
        }
        $tail = trim($m0[1]);

        // 2) NPWP persis di depan setelah prefix
        if (!preg_match('/^(\d{8,20})(.*)$/', $tail, $m1)) {
            return null;
        }
        $npwp  = preg_replace('/\D/', '', $m1[1]);
        $after = trim($m1[2]);

        // 3) Cari periode (YYYY-MM prioritas)
        $period = null;

        // 3a) YYYY-MM
        if (preg_match('/\b((?:19|20)\d{2})-(0[1-9]|1[0-2])\b/', $after, $ym)) {
            $period = $ym[1] . '-' . $ym[2]; // YYYY-MM
        }

        // 3b) Pasangan token terpisah: MM YYYY atau YYYY MM
        if (!$period) {
            $tokens = preg_split('/[\s_\-]+/', $after, -1, PREG_SPLIT_NO_EMPTY);
            for ($i = 0; $i < count($tokens) - 1 && !$period; $i++) {
                $a = $tokens[$i];
                $b = $tokens[$i + 1];

                $isMM   = preg_match('/^(0[1-9]|1[0-2])$/', $a);
                $isYYYY = preg_match('/^(?:19|20)\d{2}$/', $b);

                $isYYYY2 = preg_match('/^(?:19|20)\d{2}$/', $a);
                $isMM2   = preg_match('/^(0[1-9]|1[0-2])$/', $b);

                if ($isMM && $isYYYY) {            // MM YYYY
                    $period = $b . '-' . $a;
                } elseif ($isYYYY2 && $isMM2) {    // YYYY MM
                    $period = $a . '-' . $b;
                }
            }
        }

        // 3c) Substring 8 digit (DDMMYYYY) di mana saja, termasuk dalam token panjang
        if (!$period) {
            if (preg_match_all('/\d{8}/', $after, $hits)) {
                foreach ($hits[0] as $d8) {
                    $dd = (int) substr($d8, 0, 2);
                    $mm = (int) substr($d8, 2, 2);
                    $yy = (int) substr($d8, 4, 4);
                    if ($yy >= 1900 && $mm >= 1 && $mm <= 12 && $dd >= 1 && $dd <= 31) {
                        $period = sprintf('%04d-%02d', $yy, $mm);
                        break;
                    }
                }
            }
        }

        // 3d) Substring 6 digit → YYYYMM atau MMYYYY
        if (!$period) {
            if (preg_match_all('/\d{6}/', $after, $hits6)) {
                foreach ($hits6[0] as $d6) {
                    $yyyy = (int) substr($d6, 0, 4);
                    $mm   = (int) substr($d6, 4, 2);
                    if ($yyyy >= 1900 && $mm >= 1 && $mm <= 12) {
                        $period = sprintf('%04d-%02d', $yyyy, $mm); // YYYYMM
                        break;
                    }
                    $mm2   = (int) substr($d6, 0, 2);
                    $yyyy2 = (int) substr($d6, 2, 4);
                    if ($yyyy2 >= 1900 && $mm2 >= 1 && $mm2 <= 12) {
                        $period = sprintf('%04d-%02d', $yyyy2, $mm2); // MMYYYY
                        break;
                    }
                }
            }
        }

        // 4) Vendor name (opsional, hanya untuk info di hasil)
        $vendorName = trim(preg_replace('/\d+/', ' ', $after)); // kasar: buang angka

        if (!$npwp || !$period) {
            return null;
        }

        return [
            'npwp'       => $npwp,
            'period'     => $period,     // YYYY-MM
            'vendorName' => $vendorName, // boleh kosong
        ];
    }
}
