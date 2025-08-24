<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Vendor (PT)</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif
            @if (session('ok'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                    {{ session('ok') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <form method="POST" action="{{ route('admin.vendors.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Nama PT / Vendor" />
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <x-input-label for="npwp" value="NPWP" />
                        <input id="npwp" name="npwp" type="text" inputmode="numeric" value="{{ old('npwp') }}" required
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                        <p class="text-xs text-gray-500 mt-1">Masukkan angka saja (tanpa titik/dash)</p>
                        @error('npwp') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <x-input-label for="email" value="Email (opsional)" />
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500" />
                        @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <x-input-label for="notes" value="Catatan (opsional)" />
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">{{ old('notes') }}</textarea>
                        @error('notes') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="border-t pt-4">
                        <label class="inline-flex items-center gap-2 mt-1">
                            <input type="checkbox" name="create_account" value="1" class="rounded border-gray-300"
                                   {{ old('create_account') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Sekalian buat akun login untuk vendor ini</span>
                        </label>

                        <div class="grid sm:grid-cols-2 gap-4 mt-3">
                            <div>
                                <x-input-label for="password" value="Password sementara (opsional)" />
                                <input id="password" name="password" type="text" value="{{ old('password') }}"
                                       class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500"
                                       placeholder="Kosongkan untuk auto-generate" />
                                <p class="text-xs text-gray-500 mt-1">Login vendor: NPWP + password.</p>
                                @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="flex items-end">
                                <x-primary-button>Simpan</x-primary-button>
                                <a href="{{ route('admin.vendors.index') }}" class="ml-3 text-gray-600 hover:text-gray-800">Batal</a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
