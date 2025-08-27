<x-app-layout>
    <x-slot name="header">
        {{-- Header dengan gaya gelap yang konsisten --}}
        <div class="bg-slate-800 p-6 sm:p-8 rounded-2xl shadow-lg">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-2xl text-white leading-tight">Tambah Vendor Baru</h2>
                    <p class="text-base text-slate-300 mt-1">Isi detail vendor dan buat akun login jika diperlukan.</p>
                </div>
                <a href="{{ route('admin.vendors.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-500 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Alerts --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-r-lg text-base" role="alert">
                     <p class="font-bold">Terjadi Kesalahan</p>
                    <ul class="mt-2 list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif
            @if (session('ok'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-r-lg text-base" role="alert">
                    {{ session('ok') }}
                </div>
            @endif

            <div class="bg-white shadow-lg sm:rounded-2xl p-6 sm:p-8 border border-gray-100">
                <form method="POST" action="{{ route('admin.vendors.store') }}" class="space-y-6">
                    @csrf

                    {{-- Bagian 1: Informasi Dasar Vendor --}}
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" value="Nama PT / Vendor" class="text-base font-semibold" />
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required
                                   class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4" />
                            @error('name') <div class="text-sm text-red-600 mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <x-input-label for="npwp" value="NPWP" class="text-base font-semibold" />
                            <input id="npwp" name="npwp" type="text" inputmode="numeric" value="{{ old('npwp') }}" required
                                   class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4 font-mono" />
                            <p class="text-sm text-gray-500 mt-2">Wajib diisi. Masukkan angka saja (tanpa titik/dash).</p>
                            @error('npwp') <div class="text-sm text-red-600 mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <x-input-label for="email" value="Email (opsional)" class="text-base font-semibold" />
                            <input id="email" name="email" type="email" value="{{ old('email') }}"
                                   class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4" />
                            @error('email') <div class="text-sm text-red-600 mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <x-input-label for="notes" value="Catatan (opsional)" class="text-base font-semibold" />
                            <textarea id="notes" name="notes" rows="3"
                                      class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4">{{ old('notes') }}</textarea>
                            @error('notes') <div class="text-sm text-red-600 mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Bagian 2: Pembuatan Akun --}}
                    <div class="border-t-2 border-dashed border-gray-200 pt-6 space-y-4">
                         <h3 class="font-bold text-lg text-gray-800">Akun Login Vendor</h3>
                        <label class="inline-flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 cursor-pointer">
                            <input type="checkbox" name="create_account" value="1" class="h-5 w-5 rounded border-gray-300 text-sky-600 focus:ring-sky-500"
                                   {{ old('create_account') ? 'checked' : '' }}>
                            <span class="text-base text-gray-700 font-semibold">Sekalian buat akun login untuk vendor ini</span>
                        </label>

                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="password" value="Password Sementara (opsional)" class="text-base font-semibold" />
                                <input id="password" name="password" type="text" value="{{ old('password') }}"
                                       class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4"
                                       placeholder="Kosongkan untuk auto-generate" />
                                <p class="text-sm text-gray-500 mt-2">Login vendor menggunakan NPWP + password.</p>
                                @error('password') <div class="text-sm text-red-600 mt-2">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="border-t border-gray-200 pt-6 flex items-center gap-4">
                        <x-primary-button class="px-8 py-3 text-base font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                               <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Simpan Vendor
                        </x-primary-button>
                        <a href="{{ route('admin.vendors.index') }}" class="rounded-lg px-6 py-3 text-base font-semibold border border-slate-300 text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>