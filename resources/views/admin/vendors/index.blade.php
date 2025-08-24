<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Vendor</h2>
                <p class="text-sm text-gray-500">Kelola data PT (Vendor), akun vendornya, dan impor/ekspor data.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.vendors.export', request()->only('q')) }}"
                   class="px-3 py-2 text-sm rounded-md border border-gray-200 hover:bg-gray-50">Export CSV</a>
                <a href="{{ route('admin.vendors.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">Tambah Vendor</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
            @if (session('ok'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                    {{ session('ok') }}
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
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- Bar: Search + Import + Tambah Cepat --}}
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                    {{-- Cari --}}
                    <form method="GET" action="{{ route('admin.vendors.index') }}"
                          class="flex-1 flex items-center gap-2">
                        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama / NPWP / email…"
                               class="w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                        <button class="px-3 py-2 text-sm rounded-md border border-gray-200 hover:bg-gray-50">Cari</button>
                    </form>

                    {{-- Import CSV --}}
                    <form method="POST" action="{{ route('admin.vendors.import') }}" enctype="multipart/form-data"
                          class="flex-1 flex items-center gap-2">
                        @csrf
                        <input type="file" name="csv" accept=".csv,text/csv"
                               class="text-sm w-full rounded-md border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="create_accounts" value="1" class="rounded border-gray-300">
                            <span>Buat akun vendor</span>
                        </label>
                        <button class="px-3 py-2 text-sm rounded-md border border-gray-200 hover:bg-gray-50">Import</button>
                    </form>
                </div>

                {{-- Tambah cepat (manual) --}}
                <details class="mt-6 group">
                    <summary class="cursor-pointer select-none text-sm text-gray-700 hover:text-gray-900 flex items-center gap-2">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-md border border-gray-200">+</span>
                        <span>Tambah cepat (manual) sesuai database</span>
                    </summary>
                    <div class="mt-4">
                        <form method="POST" action="{{ route('admin.vendors.store') }}" class="grid md:grid-cols-2 gap-4">
                            @csrf
                            <div>
                                <x-input-label for="name_quick" value="Nama PT / Vendor" />
                                <input id="name_quick" name="name" type="text" value="{{ old('name') }}" required
                                       class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                            </div>
                            <div>
                                <x-input-label for="npwp_quick" value="NPWP" />
                                <input id="npwp_quick" name="npwp" type="text" inputmode="numeric" value="{{ old('npwp') }}" required
                                       class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                                <p class="text-xs text-gray-500 mt-1">Masukkan angka saja (tanpa titik/dash)</p>
                            </div>
                            <div>
                                <x-input-label for="email_quick" value="Email (opsional)" />
                                <input id="email_quick" name="email" type="email" value="{{ old('email') }}"
                                       class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="notes_quick" value="Catatan (opsional)" />
                                <textarea id="notes_quick" name="notes" rows="2"
                                          class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">{{ old('notes') }}</textarea>
                            </div>

                            <div class="md:col-span-2 border-t pt-3">
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="create_account" value="1" class="rounded border-gray-300"
                                           {{ old('create_account') ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">Sekalian buat akun login untuk vendor ini</span>
                                </label>
                                <div class="grid sm:grid-cols-2 gap-4 mt-3">
                                    <div>
                                        <x-input-label for="password_quick" value="Password sementara (opsional)" />
                                        <input id="password_quick" name="password" type="text" value="{{ old('password') }}"
                                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500"
                                               placeholder="Kosongkan untuk auto-generate" />
                                        <p class="text-xs text-gray-500 mt-1">Login vendor: NPWP + password.</p>
                                    </div>
                                    <div class="flex items-end">
                                        <x-primary-button>Tambah</x-primary-button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </details>
            </div>

            {{-- Tabel / Kartu responsif daftar vendor --}}
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                @php
                    $toggleDir = fn($c) => request('sort')===$c && request('dir')!=='desc' ? 'desc' : 'asc';
                    $th = function($label,$col) use ($toggleDir) {
                        $dir = $toggleDir($col);
                        $is = request('sort')===$col;
                        $arrow = $is ? (request('dir')==='desc' ? '↓':'↑') : '';
                        $q = array_filter(array_merge(request()->all(), ['sort'=>$col,'dir'=>$dir]));
                        $url = route('admin.vendors.index',$q);
                        return "<a href=\"{$url}\" class=\"hover:underline\">{$label} {$arrow}</a>";
                    };
                @endphp

                {{-- Desktop table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="border-b font-semibold">
                            <tr>
                                <th class="text-left py-2">{!! $th('NPWP','npwp') !!}</th>
                                <th class="text-left py-2">{!! $th('Nama','name') !!}</th>
                                <th class="text-left py-2">{!! $th('Email','email') !!}</th>
                                <th class="text-left py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($vendors as $v)
                                <tr class="hover:bg-gray-50/80">
                                    <td class="py-2 font-mono">{{ $v->npwp }}</td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $v->name }}</span>
                                            <a href="{{ route('officer.vendors.show', $v) }}"
                                               class="text-xs px-2 py-0.5 rounded border border-gray-200 hover:border-blue-600 hover:text-blue-700">Dokumen</a>
                                        </div>
                                    </td>
                                    <td class="py-2">{{ $v->email }}</td>
                                    <td class="py-2">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('admin.vendors.edit', $v) }}"
                                               class="px-3 py-1.5 text-sm rounded-md border border-gray-200 hover:border-blue-600 hover:text-blue-700">Edit</a>

                                            <form method="POST" action="{{ route('admin.vendors.account.create', $v) }}"
                                                  onsubmit="return confirm('Buat akun vendor untuk {{ $v->name }}?')">
                                                @csrf
                                                <button class="px-3 py-1.5 text-sm rounded-md border border-gray-200 hover:border-emerald-600 hover:text-emerald-700">
                                                    Buat Akun
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.vendors.account.reset', $v) }}"
                                                  onsubmit="return confirm('Reset password akun vendor ini?')">
                                                @csrf
                                                <button class="px-3 py-1.5 text-sm rounded-md border border-gray-200 hover:border-orange-600 hover:text-orange-700">
                                                    Reset Password
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.vendors.destroy', $v) }}"
                                                  onsubmit="return confirm('Hapus vendor ini? (akan gagal bila sudah ada dokumen)')">
                                                @csrf @method('DELETE')
                                                <button class="px-3 py-1.5 text-sm rounded-md border border-gray-200 hover:border-red-600 hover:text-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile cards --}}
                <div class="md:hidden grid gap-3">
                    @foreach($vendors as $v)
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $v->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">NPWP: <span class="font-mono">{{ $v->npwp }}</span></div>
                                    @if($v->email)
                                        <div class="text-xs text-gray-500 mt-0.5">Email: {{ $v->email }}</div>
                                    @endif
                                </div>
                                <a href="{{ route('officer.vendors.show', $v) }}"
                                   class="text-xs px-2 py-0.5 rounded border border-gray-200 hover:border-blue-600 hover:text-blue-700 whitespace-nowrap">Dokumen</a>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                <a href="{{ route('admin.vendors.edit', $v) }}"
                                   class="px-3 py-1.5 text-sm rounded-md border border-gray-200 hover:border-blue-600 hover:text-blue-700">Edit</a>

                                <form method="POST" action="{{ route('admin.vendors.account.create', $v) }}"
                                      onsubmit="return confirm('Buat akun vendor untuk {{ $v->name }}?')">
                                    @csrf
                                    <button class="px-3 py-1.5 text-sm rounded-md border border-gray-200 hover:border-emerald-600 hover:text-emerald-700">
                                        Buat Akun
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.vendors.account.reset', $v) }}"
                                      onsubmit="return confirm('Reset password akun vendor ini?')">
                                    @csrf
                                    <button class="px-3 py-1.5 text-sm rounded-md border border-gray-200 hover:border-orange-600 hover:text-orange-700">
                                        Reset Password
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.vendors.destroy', $v) }}"
                                      onsubmit="return confirm('Hapus vendor ini? (akan gagal bila sudah ada dokumen)')">
                                    @csrf @method('DELETE')
                                    <button class="px-3 py-1.5 text-sm rounded-md border border-gray-200 hover:border-red-600 hover:text-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">{{ $vendors->links() }}</div>

                <div class="mt-6 text-xs text-gray-500">
                    <p><b>Format Import CSV</b>: header <code>name,npwp,email,notes</code>. Centang “Buat akun vendor” jika ingin otomatis membuat akun login untuk setiap baris.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
