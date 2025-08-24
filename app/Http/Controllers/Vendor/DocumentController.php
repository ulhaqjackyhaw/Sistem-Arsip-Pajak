<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Cari vendor milik user:
        // 1) pakai relasi/kolom vendor_id kalau ada
        // 2) fallback ke kecocokan NPWP
        $vendor = Vendor::query()
            ->when(!empty($user->vendor_id ?? null), fn($q) => $q->where('id', $user->vendor_id))
            ->when(empty($user->vendor_id ?? null) && !empty($user->npwp ?? null), fn($q) => $q->where('npwp', preg_replace('/\D/','',$user->npwp)))
            ->first();

        $docs = collect(); // default koleksi kosong (tidak memanggil links() di view karena dibungkus !$vendor)

        if ($vendor) {
            $q      = trim((string) $request->input('q', ''));
            $period = $request->input('period'); // format HTML <input type="month"> = YYYY-MM

            $docs = Document::query()
                ->where('vendor_id', $vendor->id)
                ->when($q !== '', function ($query) use ($q) {
                    $query->where(function ($qq) use ($q) {
                        $qq->where('original_name', 'like', "%{$q}%")
                           ->orWhere('stored_name',   'like', "%{$q}%");
                    });
                })
                ->when($period, fn($query) => $query->where('period', $period))
                ->orderByDesc('created_at')
                ->paginate(20)
                ->withQueryString(); // penting supaya pagination bawa ?q=&period=
        }

        return view('vendor.documents.index', [
            'vendor' => $vendor,
            'docs'   => $docs,
        ]);
    }
}
