<?php

namespace App\Support;

class TaxFilename
{
    /**
     * Parse nama file bukti pajak.
     *
     * Pola yang didukung (case-insensitive):
     * 1) BLU_<NPWP>_<YYYY-MM>.pdf
     * 2) BLU_<NPWP>_<YYYYMM>.pdf
     * 3) BLU_<NPWP>_<MMYYYY>.pdf
     * 4) BLU_<NPWP> <NAMA_VENDOR>_<DDMMYYYY>.pdf
     * 5) BLU-<NPWP>-<DDMMYYYY>.pdf
     *
     * Contoh:
     *  - BLU_9876543210987654_2025-08.pdf   -> period 2025-08
     *  - BLU_9876543210987654_202508.pdf    -> period 2025-08
     *  - BLU_9876543210987654_082025.pdf    -> period 2025-08
     *  - BLU_9876543210987654 PT ABC_08052025.pdf -> period 2025-05, vendor_name=PT ABC
     *
     * @return array|false { npwp, vendor_name?, date_raw?, period }
     */
    public static function parse(string $filename)
    {
        $stem = pathinfo($filename, PATHINFO_FILENAME);

        // Normalisasi
        $s = trim($stem);
        $s = preg_replace('/\s+/', ' ', $s);

        // Ambil segmen setelah "BLU" (opsional delimiter)
        // Contoh match: "BLU_9876.... PT ABC_08052025"
        if (!preg_match('/^BLU[\s_\-]+(.+)$/i', $s, $m0)) {
            return false;
        }
        $tail = trim($m0[1]);

        // Ambil NPWP (angka 8-20 digit) di awal tail
        if (!preg_match('/^(?<npwp>\d{8,20})(?<after>.*)$/', $tail, $m1)) {
            return false;
        }
        $npwp = preg_replace('/\D/', '', $m1['npwp']);
        $after = trim($m1['after']);

        // Pecah menjadi "bagian nama vendor (opsional)" + "bagian tanggal/periode"
        // Ambil blok terakhir setelah delimiter sebagai kandidat tanggal/periode
        // Delimiter bisa: space, underscore, dash
        $parts = preg_split('/[\s_\-]+/', $after);
        $dateRaw = null;
        $vendorName = null;

        if ($parts && count($parts) > 0) {
            // Kandidat tanggal kemungkinan di bagian paling akhir
            $last = strtoupper(end($parts));
            // Coba identifikasi tanggal/periode:
            // 8 digit -> asumsikan DDMMYYYY
            // 6 digit -> YYYYMM atau MMYYYY
            // YYYY-MM -> langsung
            if (preg_match('/^\d{8}$/', $last)) {
                $dateRaw = $last; // DDMMYYYY (asumsi default)
                array_pop($parts);
            } elseif (preg_match('/^\d{6}$/', $last)) {
                $dateRaw = $last; // YYYYMM atau MMYYYY
                array_pop($parts);
            } elseif (preg_match('/^\d{4}\-\d{2}$/', $last)) {
                $dateRaw = $last; // YYYY-MM
                array_pop($parts);
            } else {
                // Tidak ketemu tanggal di ekor, coba format lain: mis. setelah underscore terakhir
                if (preg_match('/^(.*)[\s_\-]+(\d{8}|\d{6}|\d{4}\-\d{2})$/', $after, $m2)) {
                    $vendorName = trim($m2[1]);
                    $dateRaw    = strtoupper($m2[2]);
                } else {
                    // Tidak ada tanggal, mungkin format: BLU_<NPWP>_<YYYY-MM> tanpa vendor name
                    // atau BLU_<NPWP> saja -> gagal
                }
            }

            if ($dateRaw === null && $vendorName === null) {
                // Kalau belum kebentuk vendorName dari m2, anggap sisa after (kecuali date) sebagai vendor
                $vendorName = trim($after, " _-");
            } elseif ($vendorName === null && !empty($parts)) {
                // Sisa parts (kecuali last) -> vendorName
                $vendorName = trim(implode(' ', $parts));
            }
        }

        // Hitung period dari $dateRaw
        $period = null;
        if ($dateRaw !== null) {
            // DDMMYYYY
            if (preg_match('/^\d{8}$/', $dateRaw)) {
                $dd = intval(substr($dateRaw, 0, 2));
                $mm = intval(substr($dateRaw, 2, 2));
                $yy = intval(substr($dateRaw, 4, 4));
                if ($mm >= 1 && $mm <= 12) {
                    $period = sprintf('%04d-%02d', $yy, $mm);
                }
            }
            // YYYYMM
            elseif (preg_match('/^\d{6}$/', $dateRaw)) {
                $front = intval(substr($dateRaw, 0, 4));
                $back  = intval(substr($dateRaw, 4, 2));
                $front2 = intval(substr($dateRaw, 0, 2));
                $back2  = intval(substr($dateRaw, 2, 4));
                if ($front >= 1900 && $back >= 1 && $back <= 12) {
                    // YYYYMM
                    $period = sprintf('%04d-%02d', $front, $back);
                } elseif ($front2 >= 1 && $front2 <= 12 && $back2 >= 1900) {
                    // MMYYYY
                    $period = sprintf('%04d-%02d', $back2, $front2);
                }
            }
            // YYYY-MM
            elseif (preg_match('/^\d{4}\-\d{2}$/', $dateRaw)) {
                $period = $dateRaw;
            }
        }

        if (!$npwp || !$period) {
            return false;
        }

        return [
            'npwp'        => $npwp,
            'vendor_name' => $vendorName ?: null,
            'date_raw'    => $dateRaw,
            'period'      => $period,
        ];
    }
}
