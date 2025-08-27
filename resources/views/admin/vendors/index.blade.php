<x-app-layout>
    <x-slot name="header">
        {{-- Header dengan gaya gelap yang konsisten --}}
        <div class="bg-slate-800 p-6 sm:p-8 rounded-2xl shadow-lg">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-2xl text-white leading-tight">Manajemen Vendor</h2>
                    <p class="text-base text-slate-300 mt-1">Kelola data, akun, serta impor/ekspor data vendor.</p>
                </div>
                <div class="flex items-center gap-3">

                    <a href="{{ route('admin.vendors.create') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-md hover:bg-sky-600 transition-colors">
                        Tambah Vendor Baru
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Alerts --}}
            @if (session('ok'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-r-lg text-base" role="alert">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('ok') }}</p>
                    @if (session('import_errors'))
                        <ul class="mt-2 text-sm list-disc pl-5">
                            @foreach (session('import_errors') as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-r-lg text-base" role="alert">
                     <p class="font-bold">Terjadi Kesalahan</p>
                    <ul class="mt-2 list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- SEKSI 1: Aksi & Perkakas (Impor & Tambah Cepat) --}}
            <div class="bg-white shadow-lg sm:rounded-2xl border border-gray-100">
                <details class="group" {{ $errors->has('name') || $errors->has('npwp') ? 'open' : '' }}>
                    <summary class="p-6 cursor-pointer select-none flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-xl text-gray-900">Tambah Vendor dengan Cepat</h3>
                            <p class="text-base text-gray-500 mt-1">Gunakan fitur ini untuk menambah banyak vendor sekaligus.</p>
                        </div>
                        <div class="text-sky-600 group-open:rotate-90 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 border-t border-slate-200">
                        {{-- Form Import --}}
                        <div class="mt-6">
                       <h4 class="font-semibold text-lg text-slate-800">Download Template CSV ini Terlebih dahulu</h4>
                       <br>
                       <div><a href="{{ route('admin.vendors.export', request()->only('q')) }}"
                       class="inline-flex items-center gap-2 rounded-lg border border-slate-500 px-4 py-2 text-sm font-semibold text-black hover:bg-slate-700 transition-colors">
                        Template CSV
                    </a>
                    
                    <p class="text-sm text-gray-500 mt-1">Isi dengan Format:  <code>name,npwp,email. Isi di kolom 1</code>.</p>
                    
                </div>
                             <br>
                            <h4 class="font-semibold text-lg text-slate-800">Impor file CSV</h4>
                             <p class="text-sm text-gray-500 mt-1">Pilih File CSV yang sudah diisi. klik check buat akun jika ingin sekaligus dibuat akun. Lalu tinggal import</p>
                             <form method="POST" action="{{ route('admin.vendors.import') }}" enctype="multipart/form-data" class="mt-4 flex flex-col sm:flex-row sm:items-center gap-3">
                                 @csrf
                                 <input type="file" name="csv" accept=".csv,text/csv" required class="text-base w-full file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 rounded-lg border border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500">
                                 <br>
                                 <div>
                                 <label class="inline-flex items-center gap-2 text-base">
                                     <input type="checkbox" name="create_accounts" value="1" class="rounded border-Black-900 text-sky-600 focus:ring-sky-500">
                                     <span>Buat akun</span>
                                 </label>
                                 </div>
                                 <br>
                                 <button class="px-5 py-2.5 text-base font-semibold rounded-lg border border-slate-300 bg-slate-100 hover:bg-slate-200 text-slate-800 shrink-0">Impor</button>
                             </form>
                        </div>

                        {{-- Form Tambah Cepat --}}
                        {{-- <div class="mt-8 border-t border-dashed pt-8">
                             <h4 class="font-semibold text-lg text-slate-800">Tambah Cepat (Manual)</h4>
                             <form method="POST" action="{{ route('admin.vendors.store') }}" class="mt-4 grid md:grid-cols-2 gap-6">
                                 @csrf
                                 <div>
                                     <x-input-label for="name_quick" value="Nama PT / Vendor" class="text-base font-semibold" />
                                     <input id="name_quick" name="name" type="text" value="{{ old('name') }}" required class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4" />
                                 </div>
                                 <div>
                                     <x-input-label for="npwp_quick" value="NPWP" class="text-base font-semibold" />
                                     <input id="npwp_quick" name="npwp" type="text" inputmode="numeric" value="{{ old('npwp') }}" required class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4 font-mono" />
                                     <p class="text-sm text-gray-500 mt-1">Angka saja, tanpa titik/dash.</p>
                                 </div>
                                 <div class="md:col-span-2">
                                     <x-input-label for="email_quick" value="Email (opsional)" class="text-base font-semibold" />
                                     <input id="email_quick" name="email" type="email" value="{{ old('email') }}" class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4" />
                                 </div>
                                 <div class="md:col-span-2 border-t pt-4">
                                     <label class="inline-flex items-center gap-2">
                                         <input type="checkbox" name="create_account" value="1" class="rounded border-gray-300 text-sky-600 focus:ring-sky-500" {{ old('create_account') ? 'checked' : '' }}>
                                         <span class="text-base text-gray-700">Sekalian buat akun login</span>
                                     </label>
                                 </div>
                                 <div class="md:col-span-2 flex items-end">
                                     <x-primary-button class="px-6 py-3 text-base font-bold">Tambah Vendor</x-primary-button>
                                 </div>
                             </form>
                        </div> --}}
                    </div>
                </details>
            </div>

            {{-- SEKSI 2: Daftar Vendor (Pencarian & Tabel) --}}
            <div class="bg-white shadow-lg sm:rounded-2xl p-6 sm:p-8 border border-gray-100">
                <form method="GET" action="{{ route('admin.vendors.index') }}" class="flex flex-col sm:flex-row items-center gap-3 mb-6">
                    <div class="relative w-full">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                           <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        </div>
                        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari berdasarkan nama, NPWP, atau email..." class="w-full rounded-lg border-gray-300 pl-11 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4">
                    </div>
                    <button class="px-6 py-2.5 text-base font-semibold rounded-lg bg-sky-600 hover:bg-sky-700 text-white shrink-0 w-full sm:w-auto">Cari</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-base">
                        <thead class="text-sm uppercase tracking-wider font-bold text-slate-800 bg-slate-100 border-b-2 border-slate-200">
                            <tr>
                                <th class="text-left py-4 px-4">Nama</th>
                                <th class="text-left py-4 px-4">NPWP</th>
                                <th class="text-left py-4 px-4">Email</th>
                                <th class="text-right py-4 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($vendors as $v)
                                <tr class="hover:bg-sky-50/50">
                                    <td class="py-4 px-4 font-semibold text-gray-900">
                                        {{ $v->name }}
                                        <a href="{{ route('officer.vendors.show', $v) }}" class="block text-sm font-normal text-sky-600 hover:underline">Lihat Dokumen &rarr;</a>
                                    </td>
                                    <td class="py-4 px-4 font-mono text-gray-800">{{ $v->npwp }}</td>
                                    <td class="py-4 px-4 text-gray-600">{{ $v->email ?? '—' }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            <a href="{{ route('admin.vendors.edit', $v) }}" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200" title="Edit Vendor"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                            
                                            <form method="POST" action="{{ route('admin.vendors.account.create', $v) }}"
                                                  class="needs-confirmation"
                                                  data-confirm-title="Buat Akun Vendor?"
                                                  data-confirm-text="Anda akan membuat akun login untuk {{ $v->name }}."
                                                  data-confirm-icon="info"
                                                  data-confirm-button="Ya, Buat Akun!">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-emerald-100 text-emerald-600 hover:bg-emerald-200" title="Buat Akun"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg></button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('admin.vendors.account.reset', $v) }}"
                                                  class="needs-confirmation"
                                                  data-confirm-title="Reset Password?"
                                                  data-confirm-text="Password baru akan dibuat untuk akun vendor ini."
                                                  data-confirm-icon="warning"
                                                  data-confirm-button="Ya, Reset Password!">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200" title="Reset Password"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H5v-2H3v-2H1.75a1.75 1.75 0 01-1.75-1.75V11c0-.414.336-.75.75-.75h1.5a.75.75 0 01.75.75v2.25a.25.25 0 00.25.25h2.25a.25.25 0 00.25-.25v-2.25a.25.25 0 00-.25-.25h-1.5a.75.75 0 01-.75-.75V7.5a2 2 0 012-2h4.586a1 1 0 01.707.293l2 2a1 1 0 01.293.707V11c0 .414-.336.75-.75.75h-1.5a.75.75 0 01-.75-.75v-2.25a.25.25 0 00-.25-.25h-2.25a.25.25 0 00-.25.25v2.25c0 .414.336.75.75.75h1.5a.75.75 0 01.75.75v.75a6 6 0 01-6 6H6a2 2 0 01-2-2V7a2 2 0 012-2h3.586a1 1 0 01.707.293l2 2a1 1 0 01.293.707V9a2 2 0 01-2 2h-1z"></path></svg></button>
                                            </form>
                                            
                                            <form method="POST" action="{{ route('admin.vendors.destroy', $v) }}"
                                                  class="needs-confirmation"
                                                  data-confirm-title="Hapus Vendor Ini?"
                                                  data-confirm-text="Vendor {{ $v->name }} akan dihapus. Aksi ini tidak bisa dibatalkan."
                                                  data-confirm-icon="error"
                                                  data-confirm-button="Ya, Hapus Vendor!">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-red-100 text-red-600 hover:bg-red-200" title="Hapus Vendor"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500 text-base">Tidak ada vendor ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($vendors->hasPages())
                    <div class="mt-5 border-t border-gray-200 pt-4">
                        {{ $vendors->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Cari semua form yang butuh konfirmasi
            const confirmationForms = document.querySelectorAll('.needs-confirmation');

            confirmationForms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    // Hentikan pengiriman form asli
                    event.preventDefault();

                    // Ambil data dari atribut HTML
                    const title = form.dataset.confirmTitle || 'Apakah Anda yakin?';
                    const text = form.dataset.confirmText || 'Tindakan ini tidak bisa dibatalkan!';
                    const icon = form.dataset.confirmIcon || 'warning';
                    const confirmButtonText = form.dataset.confirmButton || 'Ya, Lanjutkan!';
                    const cancelButtonText = form.dataset.cancelButton || 'Batal';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmButtonText,
                        cancelButtonText: cancelButtonText
                    }).then((result) => {
                        // Jika pengguna mengklik "Ya", kirim formnya
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>