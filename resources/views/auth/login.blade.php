<x-guest-layout>
    {{-- Form Heading and Subheading --}}
    <div class="flex flex-col items-center justify-center text-center">
        <h2 class="text-2xl font-bold leading-9 tracking-tight text-white">
            Login Tax Officer / Admin
        </h2>
        <p class="mt-2 text-sm leading-6 text-slate-300">
            Selamat datang kembali! Silakan masukkan kredensial Anda.
        </p>
    </div>

    <div class="mt-8">
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- Email Input --}}
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-white" />
                <div class="relative mt-2">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path
                                d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                            <path
                                d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                        </svg>
                    </div>
                    <x-text-input id="email"
                        class="block w-full pl-10 bg-white/5 border-white/20 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
            </div>

{{-- Password Input --}}
<div>
    <x-input-label for="password" :value="__('Password')" class="text-white" />
    <div class="relative mt-2">
        {{-- ikon kunci di kiri --}}
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-indigo-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                 fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                      d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                      clip-rule="evenodd" />
            </svg>
        </div>

        {{-- input password --}}
        <x-text-input
            id="password"
            name="password"
            type="password"
            required
            autocomplete="current-password"
            class="block w-full pl-10 pr-10 bg-white/5 border-white/20 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
        />

        {{-- tombol mata di kanan --}}
        <button type="button"
                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-300 hover:text-white"
                aria-label="Tampilkan/sembunyikan kata sandi"
                onclick="togglePassword('password', this)">
            {{-- eye (lihat) --}}
            <svg data-eye class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.07.206.07.438 0 .644C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            {{-- eye-off (sembunyikan) --}}
            <svg data-eye-off class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3l18 18M10.584 10.59A2 2 0 0112 10c1.105 0 2 .895 2 2 0 .418-.128.806-.346 1.124M9.88 4.603A9.956 9.956 0 0112 4c4.64 0 8.577 2.51 9.964 6.678.07.206.07.438 0 .644a12.09 12.09 0 01-3.15 4.61M6.228 6.23A12.09 12.09 0 002.036 12.322c-.07.206-.07.438 0 .644C3.423 16.49 7.36 19 12 19c1.21 0 2.37-.168 3.458-.48" />
            </svg>
        </button>
    </div>
    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
</div>


            {{-- Remember Me and Forgot Password --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-400 focus:ring-indigo-400">
                    <label for="remember_me"
                        class="ml-3 block text-sm leading-6 text-white">{{ __('Remember me') }}</label>
                </div>

            </div>

            {{-- Login Button --}}
            <div>
                <x-primary-button class="flex w-full justify-center bg-indigo-600 hover:bg-indigo-500">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
<script>
function togglePassword(inputId, btn) {
  const input = document.getElementById(inputId);
  const toText = input.type === 'password';
  input.type = toText ? 'text' : 'password';
  const eye = btn.querySelector('[data-eye]');
  const eyeOff = btn.querySelector('[data-eye-off]');
  if (eye && eyeOff) {
    eye.classList.toggle('hidden', !toText);
    eyeOff.classList.toggle('hidden', toText);
  }
}
</script>
