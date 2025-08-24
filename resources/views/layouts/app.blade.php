{{-- layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'SiAJAK') }}</title>

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">
    <div class="min-h-full">
        {{-- Navigasi Utama --}}
        @include('layouts.navigation')

        {{-- Konten Utama --}}
        <main class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                {{-- Header (Opsional) --}}
                @isset($header)
                    <header class="mb-8">
                        <div class="mx-auto max-w-7xl">
                            <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">
                                {{ $header }}
                            </h1>
                        </div>
                    </header>
                @endisset

                {{-- Flash Message (Opsional) --}}
                @if (session('success') || session('error'))
                    @php
                        $isError = session()->has('error');
                        $message = session('success') ?? session('error');
                    @endphp
                    <div class="mb-6 rounded-md {{ $isError ? 'bg-red-50' : 'bg-green-50' }} p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if($isError)
                                    {{-- Heroicon: x-circle --}}
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    {{-- Heroicon: check-circle --}}
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium {{ $isError ? 'text-red-800' : 'text-green-800' }}">
                                    {{ $message }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif


                {{-- Slot Konten Utama --}}
                <div class="bg-white p-6 sm:p-8 shadow-sm rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="bg-slate-100 border-t border-slate-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <p class="text-center text-xs text-slate-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    {{-- Stack script halaman --}}
    @stack('scripts')
</body>
</html>