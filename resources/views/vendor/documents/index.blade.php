{{-- resources/views/vendor/documents/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-blue-600/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900">Manajemen Dokumen Vendor</h2>
                    <p class="text-gray-500 text-sm">Lihat dan unduh dokumen perusahaan Anda secara aman.</p>
                </div>
            </div>

            {{-- Tampilkan tombol upload hanya jika route-nya ada --}}
            @if (\Illuminate\Support\Facades\Route::has('vendor.documents.create'))
                <a href="{{ route('vendor.documents.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-3 py-2 text-sm hover:bg-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Upload Dokumen
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            {{-- Alerts --}}
            @if (session('success'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if (!$vendor)
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
                    Akun Anda belum terhubung ke vendor.
                </div>
            @else
                {{-- Vendor card --}}
                <div class="mb-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start sm:items-center gap-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Vendor</div>
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ $vendor->name }}
                                    <span class="ml-2 align-middle rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">
                                        NPWP: {{ $vendor->npwp }}
                                    </span>
                                </div>
                                @if($vendor->email)
                                    <div class="text-sm text-gray-500">{{ $vendor->email }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- Pencarian + filter --}}
                        <form method="GET" class="mt-3 sm:mt-0">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama file…"
                                           class="w-full sm:w-64 rounded-lg border-gray-300 pl-9 pr-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                                    </svg>
                                </div>
                                <input type="month" name="period" value="{{ request('period') }}"
                                       class="rounded-lg border-gray-300 py-2 px-3 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                                <button class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                    Cari
                                </button>
                                @if(request()->hasAny(['q','period']))
                                    <a href="{{ route('vendor.documents.index') }}" class="text-sm text-gray-600 hover:text-gray-800 underline">
                                        Back
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabel responsif --}}
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
                    @if ($docs->count() === 0)
                        <div class="p-10 text-center">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8m-5 4h5M4 6a2 2 0 012-2h8.5L20 7.5V18a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                </svg>
                            </div>
                            <div class="text-gray-700 font-medium">Tidak ada dokumen ditemukan</div>
                            <div class="text-sm text-gray-500">Coba ubah kata kunci atau filter periode.</div>
                        </div>
                    @else
                        {{-- Desktop: tabel --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full text-sm text-gray-800">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="text-left py-3 px-4">Waktu</th>
                                        <th class="text-left py-3 px-4">Periode</th>
                                        <th class="text-left py-3 px-4">Nama File</th>
                                        <th class="text-left py-3 px-4">Ukuran</th>
                                        <th class="text-left py-3 px-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($docs as $d)
                                        @php
                                            $ext = strtolower(pathinfo($d->original_name ?? $d->stored_name, PATHINFO_EXTENSION));
                                            $isPdf = $ext === 'pdf' || str_contains(($d->mime ?? ''), 'pdf');
                                            $size = $d->size ? number_format($d->size / 1024 / 1024, 2) . ' MB' : '—';
                                        @endphp
                                        <tr class="hover:bg-gray-50/80">
                                            <td class="py-3 px-4">{{ $d->created_at->format('Y-m-d H:i') }}</td>
                                            <td class="py-3 px-4">
                                                <span class="rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 border border-blue-100">
                                                    {{ $d->period }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white">
                                                        @if($isPdf)
                                                            <svg class="h-4 w-4 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4-4 4 4 4-4 4 4M4 8h16"/>
                                                            </svg>
                                                        @endif
                                                    </span>
                                                    <div class="truncate max-w-[40ch]" title="{{ $d->original_name ?? $d->stored_name }}">
                                                        {{ $d->original_name ?? $d->stored_name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">{{ $size }}</td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('vendor.documents.download', $d) }}"
                                                   class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-sm hover:border-blue-600 hover:text-blue-700">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/>
                                                    </svg>
                                                    Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile: cards --}}
                        <div class="md:hidden divide-y">
                            @foreach($docs as $d)
                                @php
                                    $ext = strtolower(pathinfo($d->original_name ?? $d->stored_name, PATHINFO_EXTENSION));
                                    $isPdf = $ext === 'pdf' || str_contains(($d->mime ?? ''), 'pdf');
                                    $size = $d->size ? number_format($d->size / 1024 / 1024, 2) . ' MB' : '—';
                                @endphp
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-start gap-3">
                                            <div class="h-10 w-10 rounded-lg border border-gray-200 bg-white flex items-center justify-center">
                                                @if($isPdf)
                                                    <svg class="h-5 w-5 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z"/>
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4-4 4 4 4-4 4 4M4 8h16"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 truncate max-w-[28ch]" title="{{ $d->original_name ?? $d->stored_name }}">
                                                    {{ $d->original_name ?? $d->stored_name }}
                                                </div>
                                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-600">
                                                    <span class="rounded bg-blue-50 px-2 py-0.5 font-medium text-blue-700 border border-blue-100">{{ $d->period }}</span>
                                                    <span>{{ $d->created_at->format('Y-m-d H:i') }}</span>
                                                    <span>•</span>
                                                    <span>{{ $size }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('vendor.documents.download', $d) }}"
                                           class="shrink-0 inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-sm hover:border-blue-600 hover:text-blue-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/>
                                            </svg>
                                            Unduh
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 px-4 py-3">
                            {{ $docs->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
