{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased h-full">
    <div class="flex min-h-full flex-col justify-center items-center px-6 py-12 lg:px-8 bg-slate-50">
        {{-- Logo di Atas Card --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-sm mb-8">
            <a href="/">
                {{-- Ganti dengan logo aplikasi Anda --}}
                <x-application-logo class="w-20 h-20 fill-current text-slate-500 mx-auto" />
            </a>
        </div>

        {{-- Card Konten --}}
        <div class="w-full sm:max-w-md p-8 bg-white shadow-xl shadow-slate-200/50 rounded-2xl ring-1 ring-slate-900/5">
            {{ $slot }}
        </div>
    </div>
</body>
</html>