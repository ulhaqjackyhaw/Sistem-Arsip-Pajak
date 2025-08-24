<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Edit Vendor</h2>
                <p class="text-sm text-gray-500">Perbarui data PT & ubah password akun vendor.</p>
            </div>
            <a href="{{ route('admin.vendors.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- FORM UPDATE DATA VENDOR -->
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <h3 class="font-semibold text-gray-800">Data Vendor</h3>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi sesuai database.</p>

                <form method="POST" action="{{ route('admin.vendors.update', $vendor) }}" class="mt-4 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="name" value="Nama PT / Vendor" />
                        <input id="name" name="name" type="text" value="{{ old('name',$vendor->name) }}" required
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="npwp" value="NPWP" />
                        <input id="npwp" name="npwp" type="text" inputmode="numeric"
                               value="{{ old('npwp',$vendor->npwp) }}" required
                               oninput="this.value=this.value.replace(/\D/g,'')"
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                        <p class="text-xs text-gray-500 mt-1">Masukkan angka saja (tanpa titik/dash).</p>
                        <x-input-error :messages="$errors->get('npwp')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email (opsional)" />
                        <input id="email" name="email" type="email" value="{{ old('email',$vendor->email) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Catatan (opsional)" />
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">{{ old('notes',$vendor->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>

                    <div class="pt-2 flex items-center gap-3">
                        <x-primary-button>Update</x-primary-button>
                        <a href="{{ route('admin.vendors.index') }}" class="text-gray-600 hover:text-gray-800">Batal</a>
                    </div>
                </form>
            </div>

            <!-- FORM UBAH PASSWORD AKUN VENDOR -->
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <h3 class="font-semibold text-gray-800">Ubah Password Akun Vendor</h3>
                <p class="text-sm text-gray-500 mt-1">Password ini dipakai saat login vendor (NPWP + password).</p>

                <form method="POST" action="{{ route('admin.vendors.password.update', $vendor) }}" class="mt-4 grid sm:grid-cols-2 gap-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="new_password" value="Password baru" />
                        <input id="new_password" name="new_password" type="password" required
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                        <x-input-error :messages="$errors->get('new_password')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="new_password_confirmation" value="Konfirmasi password baru" />
                        <input id="new_password_confirmation" name="new_password_confirmation" type="password" required
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-primary-button>Update Password</x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
