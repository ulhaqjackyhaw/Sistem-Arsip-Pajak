{{-- resources/views/layouts/guest.blade.php --}}
@props(['card' => 'sm:max-w-2xl'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">  {{-- tambahkan h-full --}}
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name', 'BUPORT') }}</title>

  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
  <meta name="theme-color" content="#0ea5e9">

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans text-gray-900 antialiased"> {{-- min-h-screen --}}
  {{-- BACKGROUND: fixed full viewport, tidak akan “bolong” di bawah --}}
  <div class="fixed inset-0 -z-10">
    <img src="{{ asset('images/cgk.jpg') }}" alt="" class="h-full w-full object-cover">
    <div class="absolute inset-0 bg-black/60"></div>
  </div>

  <div class="relative min-h-screen flex flex-col items-center justify-center px-4">
    {{-- Logo --}}
    <div class="mb-6">
      <a href="{{ route('home') }}">
        <img src="{{ asset('images/logo.png') }}" alt="Logo"
             class="w-24 h-24 rounded-full shadow-lg transition-transform duration-300 hover:scale-110"
             loading="lazy" decoding="async">
      </a>
    </div>

    {{-- Headline --}}
    <div class="text-center mb-8">
      <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)]">
        {{ config('app.name', 'BUPORT') }}
      </h1>
      <p class="mt-4 text-lg text-slate-300 drop-shadow-[0_1px_2px_rgba(0,0,0,0.5)]">
        Bukti Potong Pajak Injourney Airport
      </p>
    </div>

    {{-- Card konten (login/register) --}}
    <div class="w-full {{ $card }} mt-6 p-6 sm:p-8 bg-white/10 backdrop-blur-md shadow-2xl rounded-2xl ring-1 ring-white/20">
      {{ $slot }}
    </div>
  </div>
</body>
</html>
