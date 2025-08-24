<x-guest-layout>
    {{-- Judul dan Subjudul Form --}}
    <div>
        <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">
            Login Vendor
        </h2>
        <p class="mt-2 text-sm leading-6 text-gray-500">
            Gunakan NPWP dan Password yang terdaftar untuk masuk.
        </p>
    </div>

    <div class="mt-8">
        <form method="POST" action="{{ route('vendor.login') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="npwp" value="NPWP" />
                <div class="relative mt-2">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        {{-- Ikon Identifikasi --}}
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0z" />
                        </svg>
                    </div>
                    <x-text-input id="npwp" name="npwp" type="text" class="block w-full pl-10" required autofocus autocomplete="off" value="{{ old('npwp') }}" />
                </div>
                <x-input-error :messages="$errors->get('npwp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <div class="relative mt-2">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        {{-- Ikon Gembok --}}
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <x-text-input id="password" name="password" type="password" class="block w-full pl-10" required />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-teal-600">
                    <label for="remember" class="ml-3 block text-sm leading-6 text-gray-900">Ingat saya</label>
                </div>

                {{-- Tambahkan link lupa password jika ada fiturnya --}}
                {{-- <div class="text-sm">
                    <a href="#" class="font-semibold text-teal-600 hover:text-teal-500">
                        Lupa Password?
                    </a>
                </div> --}}
            </div>

            <div>
                {{-- Ganti warna tombol utama agar sesuai tema vendor --}}
                <x-primary-button class="flex w-full justify-center bg-teal-600 hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-800 focus:ring-teal-500">
                    Masuk
                </x-primary-button>
            </div>
        </form>

        {{-- Link ke halaman login lain --}}
        <p class="mt-8 text-center text-sm text-gray-500">
            Bukan Vendor?
            <a href="{{ route('login') }}" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
                Login sebagai Petugas / Admin
            </a>
        </p>
    </div>
</x-guest-layout>