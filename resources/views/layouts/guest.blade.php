<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIAJAK') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="relative min-h-screen flex flex-col items-center justify-center bg-gray-100">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/cgk.jpg') }}" alt="Background" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>

        <div class="relative z-10 flex w-full flex-col items-center justify-center px-4">
            <div class="mb-6">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 h-24 rounded-full shadow-lg transition-transform duration-300 hover:scale-110">
                </a>
            </div>

            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                    BUPORT
                </h1>
                <p class="mt-4 text-lg text-slate-300" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
                    Bukti Potong Pajak Injourney Airport
                </p>
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 sm:p-8 bg-white/10 backdrop-blur-md shadow-2xl rounded-2xl ring-1 ring-white/20">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>