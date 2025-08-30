<x-guest-layout>
    {{-- Judul dan Subjudul Form --}}
    <div>
        <h2 class="text-2xl font-bold leading-9 tracking-tight text-white text-center">
            Login Vendor
        </h2>
        <p class="mt-2 text-sm leading-6 text-slate-300 text-center">
            Gunakan NPWP dan Password yang terdaftar untuk masuk.
        </p>
    </div>

    <div class="mt-8">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('vendor.login') }}" class="space-y-6">
            @csrf

            {{-- NPWP Input --}}
            <div>
                <x-input-label for="npwp" value="NPWP" class="text-white"/>
                <div class="relative mt-2">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        {{-- Ikon Identifikasi --}}
                        <svg class="h-5 w-5 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0z" />
                        </svg>
                    </div>
                    <x-text-input id="npwp" name="npwp" type="text" class="block w-full pl-10 bg-white/5 border-white/20 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500" required autofocus autocomplete="off" value="{{ old('npwp') }}" />
                </div>
                <x-input-error :messages="$errors->get('npwp')" class="mt-2 text-red-400" />
            </div>

            {{-- Password Input (dengan tombol mata) --}}
            <div x-data="{ show: false }">
                <x-input-label for="password" value="Password" class="text-white" />

                <div class="relative mt-2">
                    {{-- ikon gembok kiri --}}
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    {{-- input password (toggle type) --}}
                    <x-text-input id="password"
                        x-bind:type="show ? 'text' : 'password'"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="block w-full pl-10 pr-10 bg-white/5 border-white/20 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500" />

                    {{-- tombol mata kanan --}}
                    <button type="button"
                            @click="show = !show"
                            :aria-label="show ? 'Sembunyikan password' : 'Lihat password'"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-indigo-300 hover:text-white">
                        {{-- eye --}}
                        <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{-- eye-off --}}
                        <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18M10.477 10.477A3 3 0 0012 15a3 3 0 002.523-4.523M9.88 9.88A5 5 0 0112 7c4.477 0 8.268 2.943 9.542 7a9.967 9.967 0 01-4.132 4.132M6.228 6.228A9.969 9.969 0 002.458 12c1.274 4.057 5.065 7 9.542 7 1.121 0 2.2-.184 3.204-.523"/>
                        </svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
            </div>

            {{-- Remember Me Checkbox dan Forgot Password --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-400 focus:ring-indigo-400">
                    <label for="remember" class="ml-3 block text-sm leading-6 text-white">Ingat saya</label>
                </div>

        
            </div>

            {{-- Submit Button --}}
            <div>
                <x-primary-button class="flex w-full justify-center bg-indigo-600 hover:bg-indigo-500">
                    Masuk
                </x-primary-button>
            </div>
        </form>

        {{-- Link to Petugas/Admin login --}}
        {{-- <p class="mt-8 text-center text-white text-sm text-gray-500">
            Bukan Vendor?
            <a href="{{ route('login') }}" class="font-semibold leading-6 text-white-600 hover:text-indigo-500">
                Login sebagai Petugas / Admin
            </a>
        </p> --}}
    </div>
    
</x-guest-layout>
<style>[x-cloak]{display:none!important}</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
