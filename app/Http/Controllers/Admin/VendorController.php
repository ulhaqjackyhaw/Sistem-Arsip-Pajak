<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $q     = trim((string) $request->input('q'));
        $sort  = in_array($request->input('sort'), ['name','npwp','email','created_at']) ? $request->input('sort') : 'name';
        $dir   = $request->input('dir') === 'desc' ? 'desc' : 'asc';

        $vendors = Vendor::when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('npwp','like',"%{$q}%")
                        ->orWhere('name','like',"%{$q}%")
                        ->orWhere('email','like',"%{$q}%");
                });
            })
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->appends(['q'=>$q, 'sort'=>$sort, 'dir'=>$dir]);

        return view('admin.vendors.index', compact('vendors','q','sort','dir'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required','string','max:255'],
            'npwp'            => ['required','string','max:32','unique:vendors,npwp'],
            'email'           => ['nullable','email','max:255','unique:vendors,email'],
            'notes'           => ['nullable','string','max:1000'],
            'create_account'  => ['nullable','boolean'],
            'password'        => ['nullable','string','min:6'],
        ]);

        $npwp = preg_replace('/\D/','', (string) $data['npwp']);
        $data['npwp'] = $npwp;

        return DB::transaction(function () use ($request, $data, $npwp) {
            $vendor = Vendor::create([
                'name'  => $data['name'],
                'npwp'  => $npwp,
                'email' => $data['email'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            $createdUser = null;
            $plainPass   = null;

            if ($request->boolean('create_account')) {
                $email = $data['email'] ?? ('vendor_'.$npwp.'@example.local');

                if (User::where('npwp', $npwp)->exists()) {
                    throw ValidationException::withMessages(['npwp'=>'Akun dengan NPWP tersebut sudah ada.']);
                }
                if (User::where('email', $email)->exists()) {
                    throw ValidationException::withMessages(['email'=>'Email sudah digunakan oleh user lain.']);
                }

                $plainPass = $request->input('password') ?: Str::random(10);

                $createdUser = User::create([
                    'name'     => $vendor->name,
                    'email'    => $email,
                    'npwp'     => $npwp,
                    'role'     => 'vendor',
                    'password' => Hash::make($plainPass),
                ]);

                if (Schema::hasColumn('vendors', 'user_id')) {
                    $vendor->forceFill(['user_id' => $createdUser->id])->save();
                }
            }

            $msg = 'Vendor berhasil dibuat.';
            if ($createdUser) {
                $msg .= " Akun vendor juga dibuat. Password sementara: {$plainPass}";
            }

            return redirect()->route('admin.vendors.index')->with('ok', $msg);
        });
    }

    public function edit(Vendor $vendor)
    {
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
{
    // Simpan NPWP lama sebagai kunci fallback
    $oldNpwp = preg_replace('/\D/', '', (string) $vendor->npwp);

    // Normalisasi email (opsional tapi baik)
    if ($request->filled('email')) {
        $request->merge(['email' => strtolower(trim($request->email))]);
    }

    // Cek: apakah vendor ini SUDAH punya akun user?
    // (lewat vendor_id atau user_id)
    $relatedUserId =
        $vendor->user()->value('id') ?:        // users.vendor_id == vendor.id
        $vendor->userByUserId()->value('id');  // vendors.user_id == users.id

    // Base rules: unik di vendors saja
    $rules = [
        'name'  => ['required','string','max:255'],
        'npwp'  => ['required','string','max:32', Rule::unique('vendors','npwp')->ignore($vendor->id)],
        'email' => ['nullable','email','max:255', Rule::unique('vendors','email')->ignore($vendor->id)],
        'notes' => ['nullable','string','max:1000'],
    ];

    // Jika vendor ini memang punya akun user, barulah wajib unik juga di users
    if ($relatedUserId) {
        $rules['npwp'][]  = Rule::unique('users','npwp')->ignore($relatedUserId);
        $rules['email'][] = Rule::unique('users','email')->ignore($relatedUserId);
    }

    $data = $request->validate($rules);
    $data['npwp'] = preg_replace('/\D/','', (string) $data['npwp']); // digit-only

    DB::transaction(function () use ($vendor, $data, $oldNpwp) {
        // 1) Update vendor
        $vendor->update($data);

        // 2) Cari user terkait:
        //    - Prioritas users.vendor_id
        //    - Lalu vendors.user_id (skema lama)
        //    - Lalu fallback via NPWP lama
        $user = $vendor->user()->first()
             ?: $vendor->userByUserId()->first()
             ?: User::where('npwp', $oldNpwp)->where('role','vendor')->first();

        // 2b) Jika ketemu via NPWP lama, rapikan tautannya:
        if ($user) {
            // set users.vendor_id jika belum sesuai
            if (\Schema::hasColumn('users', 'vendor_id') && $user->vendor_id !== $vendor->id) {
                $user->vendor_id = $vendor->id;
            }
            // set vendors.user_id jika kolomnya masih ada dan belum cocok
            if (\Schema::hasColumn('vendors', 'user_id') && $vendor->user_id != $user->id) {
                $vendor->forceFill(['user_id' => $user->id])->save();
            }

            // 3) Sinkronkan field login di users
            $user->fill([
                'name'  => $data['name'],
                'npwp'  => $data['npwp'],                 // <<< kunci: update NPWP user
                'email' => $data['email'] ?: $user->email // jangan tiba-tiba null
            ])->save();
        }
    });

    return redirect()->route('admin.vendors.index')
        ->with('ok','Vendor & akun login (jika ada) berhasil diperbarui.');
}


    public function destroy(Vendor $vendor)
    {
        // Proteksi: cegah hapus jika sudah punya dokumen
        if (method_exists($vendor, 'documents') && $vendor->documents()->exists()) {
            return back()->withErrors(['delete'=>'Tidak bisa menghapus: vendor sudah punya dokumen.']);
        }

        $vendor->delete();

        return back()->with('ok','Vendor dihapus.');
    }

    public function createAccount(Vendor $vendor, Request $request)
    {
        $npwp = preg_replace('/\D/','', (string) $vendor->npwp);
        $email = $vendor->email ?? ('vendor_'.$npwp.'@example.local');

        if (User::where('npwp',$npwp)->exists()) {
            return back()->withErrors(['account'=>'Akun vendor sudah ada.']);
        }
        if (User::where('email',$email)->exists()) {
            return back()->withErrors(['account'=>'Email sudah digunakan oleh user lain.']);
        }

        $plain = Str::random(10);

        $user = User::create([
            'name'     => $vendor->name,
            'email'    => $email,
            'npwp'     => $npwp,
            'role'     => 'vendor',
            'password' => Hash::make($plain),
        ]);

        if (Schema::hasColumn('vendors','user_id')) {
            $vendor->forceFill(['user_id'=>$user->id])->save();
        }

        return back()->with('ok', "Akun vendor dibuat. Password sementara: {$plain}");
    }

    public function resetPassword(Vendor $vendor)
    {
        // Temukan user berdasarkan relasi atau NPWP
        $user = null;
        if (Schema::hasColumn('vendors','user_id') && $vendor->user_id) {
            $user = User::find($vendor->user_id);
        }
        if (!$user) {
            $user = User::where('npwp', $vendor->npwp)->where('role','vendor')->first();
        }
        if (!$user) {
            return back()->withErrors(['reset'=>'User vendor belum ada. Buat akun dulu.']);
        }

        $plain = Str::random(10);
        $user->update(['password'=>Hash::make($plain)]);

        // Production: kirim email reset link. Di dev: tampilkan.
        return back()->with('ok', "Password direset. Password baru: {$plain}");
    }

    public function export(Request $request): StreamedResponse
    {
        $q = trim((string) $request->input('q'));
        $vendors = Vendor::when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('npwp','like',"%{$q}%")
                        ->orWhere('name','like',"%{$q}%")
                        ->orWhere('email','like',"%{$q}%");
                });
            })
            ->orderBy('name')
            ->cursor(); // hemat memori

        $filename = 'vendors_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($vendors) {
            $out = fopen('php://output','w');
            fputcsv($out, ['name','npwp','email','notes']);
            foreach ($vendors as $v) {
                fputcsv($out, [$v->name, $v->npwp, $v->email, $v->notes]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv' => ['required','file','mimes:csv,txt','max:5120'],
            'create_accounts' => ['nullable','boolean'],
        ]);

        $createAccounts = $request->boolean('create_accounts');

        $file = $request->file('csv');
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return back()->withErrors(['csv'=>'Gagal membaca file.']);
        }

        $header = fgetcsv($handle);
        // Wajib ada kolom: name,npwp,email,notes (case-insensitive)
        $map = $this->mapCsvHeader($header);

        $ok = 0; $skip = 0; $fail = 0; $errors = [];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $name  = trim((string) ($row[$map['name']] ?? ''));
                $npwp  = preg_replace('/\D/','', (string) ($row[$map['npwp']] ?? ''));
                $email = trim((string) ($row[$map['email']] ?? ''));
                $notes = trim((string) ($row[$map['notes']] ?? ''));

                if ($name === '' || $npwp === '') {
                    $skip++; continue;
                }

                if (Vendor::where('npwp',$npwp)->exists()) {
                    $skip++; continue;
                }

                $vendor = Vendor::create(compact('name','npwp','email','notes'));

                if ($createAccounts) {
                    if (!User::where('npwp',$npwp)->exists()) {
                        $pwd = Str::random(10);
                        $u = User::create([
                            'name'     => $vendor->name,
                            'email'    => $email ?: ('vendor_'.$npwp.'@example.local'),
                            'npwp'     => $npwp,
                            'role'     => 'vendor',
                            'password' => Hash::make($pwd),
                        ]);
                        if (Schema::hasColumn('vendors','user_id')) {
                            $vendor->forceFill(['user_id'=>$u->id])->save();
                        }
                        // NOTE: di production, kirim email reset link.
                    }
                }

                $ok++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $fail++;
            $errors[] = $e->getMessage();
        } finally {
            fclose($handle);
        }

        $msg = "Import selesai. Berhasil: {$ok}, dilewati: {$skip}";
        if ($fail > 0) $msg .= " (gagal: {$fail})";

        return back()->with('ok', $msg)->with('import_errors', $errors);
    }

    private function mapCsvHeader(?array $header): array
    {
        $map = ['name'=>null,'npwp'=>null,'email'=>null,'notes'=>null];
        if (!$header) return $map;

        $lower = array_map(fn($h)=>strtolower(trim((string)$h)), $header);
        foreach ($lower as $i => $col) {
            if (isset($map[$col]) && $map[$col] === null) $map[$col] = $i;
        }
        // fallback posisi default
        $i = 0;
        foreach ($map as $k => $v) {
            if ($v === null) $map[$k] = $i++;
        }
        return $map;
    }

    public function updatePassword(Request $request, Vendor $vendor)
{
    $request->validate([
        'new_password' => ['required','string','min:6','confirmed'], // butuh field new_password_confirmation
    ]);

    // cari user vendor berdasarkan relasi user_id, fallback ke npwp
    $user = null;
    if (\Illuminate\Support\Facades\Schema::hasColumn('vendors','user_id') && $vendor->user_id) {
        $user = \App\Models\User::find($vendor->user_id);
    }
    if (!$user) {
        $user = \App\Models\User::where('npwp', $vendor->npwp)->where('role','vendor')->first();
    }
    if (!$user) {
        return back()->withErrors(['password' => 'User vendor belum ada. Buat akun dulu.']);
    }

    $user->forceFill(['password' => \Illuminate\Support\Facades\Hash::make($request->new_password)])->save();

    return back()->with('ok', 'Password akun vendor berhasil diperbarui.');
}

}
