<x-app-layout>
    <x-slot name="header">
        {{-- Header dengan gaya gelap yang konsisten --}}
        <div class="bg-slate-800 p-6 sm:p-8 rounded-2xl shadow-lg">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-2xl text-white leading-tight">Edit Vendor</h2>
                    <p class="text-base text-slate-300 mt-1">Perbarui data PT & ubah password akun vendor.</p>
                </div>
                <a href="{{ route('admin.vendors.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-500 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white shadow-lg sm:rounded-2xl p-6 sm:p-8 border border-gray-100">
                {{-- Judul seksi dibuat lebih besar dan jelas --}}
                <div class="border-b border-slate-200 pb-4 mb-6">
                    <h3 class="font-bold text-xl text-gray-900">Data Vendor</h3>
                    <p class="text-base text-gray-500 mt-1">Perbarui informasi sesuai database.</p>
                </div>

                <form method="POST" action="{{ route('admin.vendors.update', $vendor) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        {{-- Label diperbesar dan dipertegas --}}
                        <x-input-label for="name" value="Nama PT / Vendor" class="text-base font-semibold" />
                        {{-- Input field diperbesar --}}
                        <input id="name" name="name" type="text" value="{{ old('name',$vendor->name) }}" required
                               class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="npwp" value="NPWP" class="text-base font-semibold" />
                        <input id="npwp" name="npwp" type="text" inputmode="numeric"
                               value="{{ old('npwp',$vendor->npwp) }}" required
                               oninput="this.value=this.value.replace(/\D/g,'')"
                               class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4 font-mono" />
                        <p class="text-sm text-gray-500 mt-2">Masukkan angka saja (tanpa titik/dash).</p>
                        <x-input-error :messages="$errors->get('npwp')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email (opsional)" class="text-base font-semibold" />
                        <input id="email" name="email" type="email" value="{{ old('email',$vendor->email) }}"
                               class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Catatan (opsional)" class="text-base font-semibold" />
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4">{{ old('notes',$vendor->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    {{-- Tombol aksi dibuat lebih besar dan jelas --}}
                    <div class="pt-4 flex items-center gap-4">
                        <x-primary-button class="px-6 py-3 text-base font-bold">Update Data Vendor</x-primary-button>
                        <a href="{{ route('admin.vendors.index') }}" class="rounded-lg px-6 py-3 text-base font-semibold border border-slate-300 text-slate-700 hover:bg-slate-50">Batal</a>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-lg sm:rounded-2xl p-6 sm:p-8 border border-gray-100">
                <div class="border-b border-slate-200 pb-4 mb-6">
                    <h3 class="font-bold text-xl text-gray-900">Ubah Password Akun Vendor</h3>
                    <p class="text-base text-gray-500 mt-1">Password ini dipakai saat login vendor (NPWP + password).</p>
                </div>

                <form method="POST" action="{{ route('admin.vendors.password.update', $vendor) }}" class="grid sm:grid-cols-2 gap-x-6 gap-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="new_password" value="Password baru" class="text-base font-semibold" />
                        <input id="new_password" name="new_password" type="password" required
                               class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4" />
                        <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="new_password_confirmation" value="Konfirmasi password baru" class="text-base font-semibold" />
                        <input id="new_password_confirmation" name="new_password_confirmation" type="password" required
                               class="mt-2 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 text-base py-2.5 px-4" />
                    </div>

                    <div class="sm:col-span-2 pt-2">
                        <x-primary-button class="px-6 py-3 text-base font-bold">Update Password</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>