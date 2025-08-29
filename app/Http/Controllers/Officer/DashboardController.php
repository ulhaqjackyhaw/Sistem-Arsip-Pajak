<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Vendor;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama (boleh dicache ringan 60 detik)
        $stats = Cache::remember('officer.dashboard.stats', 60, function () {
            return [
                'totalDocs'    => Document::count(),
                'totalVendors' => Vendor::count(),
                'latest'       => Document::with(['vendor','uploader'])
                                    ->latest('created_at')
                                    ->first(),
            ];
        });

        // 10 upload terbaru (tanpa cache agar terasa real-time)
        $recentUploads = Document::with(['vendor','uploader'])
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('officer.dashboard', [
            'totalDocs'     => $stats['totalDocs'],
            'totalVendors'  => $stats['totalVendors'],
            'latest'        => $stats['latest'],
            'recentUploads' => $recentUploads,
        ]);
    }
}
