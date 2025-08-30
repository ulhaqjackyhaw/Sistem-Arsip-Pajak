{{-- resources/views/layouts/app.blade.php --}}
@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-sky-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title: pakai prop kalau ada, fallback ke APP_NAME --}}
    <title>{{ $title ? ($title . ' – ') : '' }}{{ config('app.name', 'SiAJAK') }}</title>

    {{-- Favicon/logo tab (gunakan public/images/logo.png) --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">
    <div class="min-h-screen flex flex-col">

        {{-- Navigasi Utama --}}
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white/90 backdrop-blur shadow-sm border-b border-slate-200">
                <div class="mx-auto max-w-screen-2xl px-4 py-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Konten Utama --}}
        <main class="flex-grow">
            <div class="mx-auto max-w-screen-2xl py-6 sm:px-6 lg:px-8">

                {{-- Flash Message (Opsional) --}}
                @if (session('success') || session('error'))
                    @php
                        $isError = session()->has('error');
                        $message = session('success') ?? session('error');
                    @endphp
                    <div class="rounded-lg {{ $isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }} p-4 mx-4 sm:mx-0 mb-6 border {{ $isError ? 'border-red-200' : 'border-green-200' }}">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if($isError)
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Slot Konten Utama --}}
                {{ $slot }}
            </div>
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-slate-200 mt-auto">
            <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <p class="text-center text-xs text-slate-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                </p>
            </div>
        </footer>
    </div>

    @stack('scripts')
    {{-- Alpine untuk navbar (x-data), SweetAlert, HTMX untuk live search --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
</body>
</html>