<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Masuk sebagai <strong>Vendor</strong> menggunakan NPWP + Password.
    </div>

    <form method="POST" action="{{ route('vendor.login') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="npwp" value="NPWP" />
            <x-text-input id="npwp" name="npwp" type="text" class="block mt-1 w-full"
                          required autofocus autocomplete="off" value="{{ old('npwp') }}" />
            <x-input-error :messages="$errors->get('npwp')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 me-2">
                Ingat saya
            </label>

            <x-primary-button>Masuk</x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-sm">
        Bukan vendor? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login Petugas/Admin</a>
    </div>
</x-guest-layout>
