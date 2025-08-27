<x-app-layout>
    <x-slot name="header">
        {{-- Header dengan garis bawah yang lebih tegas --}}
        <div class="border-b-2 border-sky-300 pb-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 shadow-md">
                        <svg class="h-6 w-6 text-sky-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-6a2 2 0 002-2h-2a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900">Manajemen Dokumen Vendor</h2>
                        <p class="text-sm text-gray-600">Lihat dan unduh dokumen perusahaan Anda secara aman.</p>
                    </div>
                </div>

                @if (\Illuminate\Support\Facades\Route::has('vendor.documents.create'))
                    <a href="{{ route('vendor.documents.create') }}"
                       class="inline-flex items-center gap-2 justify-center rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Upload Dokumen
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    {{-- Latar belakang keseluruhan yang diubah menjadi biru terang (blue-100) --}}
    <div class="min-h-screen bg-blue-100 py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Alerts --}}
            @if (session('success'))
                <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-100 px-4 py-3 text-emerald-800 shadow-md">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div class="mb-4 rounded-xl border border-red-300 bg-red-100 px-4 py-3 text-red-800 shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            @if (!$vendor)
                <div class="rounded-xl border border-amber-300 bg-amber-100 px-4 py-3 text-amber-800 shadow-md">
                    Akun Anda belum terhubung ke vendor.
                </div>
            @else
                {{-- Kartu Vendor dengan gradien dan bayangan kuat --}}
                <div class="mb-6 rounded-2xl border border-blue-200 bg-gradient-to-r from-blue-50 to-white p-6 shadow-xl">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-4 sm:items-center">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-200 ring-2 ring-blue-300">
                                <svg class="h-5 w-5 text-blue-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-500">Vendor</div>
                                <div class="text-xl font-extrabold text-gray-900">
                                    {{ $vendor->name }}
                                    <span class="ml-2 align-middle rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-blue-700 ring-1 ring-blue-300">
                                        NPWP: {{ $vendor->npwp }}
                                    </span>
                                </div>
                                @if($vendor->email)
                                    <div class="text-sm text-gray-600">{{ $vendor->email }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- Pencarian + filter dengan tombol yang dipertegas --}}
                        <form method="GET" class="mt-3 sm:mt-0">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                                    </svg>
                                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama file…"
                                           class="w-full rounded-lg border-gray-300 py-2 pl-9 pr-3 text-sm focus:border-blue-500 focus:ring-blue-500/50 sm:w-64 shadow-sm">
                                </div>
                                <input type="month" name="period" value="{{ request('period') }}"
                                       class="rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/50 shadow-sm">
                                <button class="inline-flex items-center justify-center rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                    Cari
                                </button>
                                @if(request()->hasAny(['q','period']))
                                    <a href="{{ route('vendor.documents.index') }}"
                                       class="inline-flex items-center justify-center rounded-lg border border-gray-400 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-100 hover:border-gray-500">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kontainer data --}}
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
                    @if ($docs->isEmpty())
                        <div class="p-10 text-center bg-gray-50">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100">
                                <svg class="h-7 w-7 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8m-5 4h5M4 6a2 2 0 012-2h8.5L20 7.5V18a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                </svg>
                            </div>
                            <div class="font-bold text-gray-700">Tidak ada dokumen ditemukan</div>
                            <div class="text-sm text-gray-500">Coba ubah kata kunci atau filter periode.</div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('vendor.documents.downloadZip') }}" id="download-form">
                            @csrf
                            
                            {{-- 1. Tampilan Desktop (Tabel) --}}
                            <div class="hidden md:block">
                                <table class="min-w-full text-sm text-gray-800">
                                    {{-- PERUBAHAN DI SINI: Latar header tabel diubah agar lebih kontras --}}
                                    <thead class="bg-slate-100 text-slate-700 uppercase">
                                        <tr>
                                            {{-- PERUBAHAN DI SINI: Menambahkan label "ALL" di sebelah checkbox --}}
                                            <th class="px-4 py-3 text-left">
                                                <label for="check-all-desktop" class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" id="check-all-desktop" class="cursor-pointer rounded border-black-300 text-blue-600 focus:ring-blue-500"/>
                                                    <span class="text-xs font-semibold tracking-wider">ALL</span>
                                                </label>
                                            </th>
                                            <th class="px-4 py-3 text-left font-bold">Waktu</th>
                                            <th class="px-4 py-3 text-left font-bold">Periode</th>
                                            <th class="px-4 py-3 text-left font-bold">Nama File</th>
                                            <th class="px-4 py-3 text-left font-bold">Ukuran</th>
                                            <th class="px-4 py-3 text-left font-bold">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($docs as $d)
                                            <tr class="hover:bg-blue-50">
                                                <td class="px-4 py-3">
                                                    <input type="checkbox" name="docs[]" value="{{ $d->id }}" class="checkbox rounded border-black-300 text-blue-600 focus:ring-blue-500"/>
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">{{ $d->created_at->format('Y-m-d H:i') }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="rounded-md bg-blue-200 px-2 py-0.5 text-xs font-bold text-blue-800 ring-1 ring-blue-300">
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $d->period)->format('F Y') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-3">
                                                        <span class="inline-flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-red-100 ring-1 ring-red-300">
                                                            <svg class="h-4 w-4 text-red-600" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z"/></svg>
                                                        </span>
                                                        <div class="max-w-[40ch] truncate font-semibold text-gray-800" title="{{ $d->original_name ?? $d->stored_name }}">
                                                            {{ $d->original_name ?? $d->stored_name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-gray-700">{{ number_format($d->size / 1024 / 1024, 2) }} MB</td>
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('vendor.documents.download', $d) }}"
                                                       class="inline-flex items-center gap-1.5 rounded-md border border-blue-300 bg-white px-3 py-1.5 text-sm font-medium text-blue-700 shadow-sm transition-all duration-200 hover:bg-blue-50 hover:border-blue-400">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/></svg>
                                                        Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- 2. Tampilan Mobile (Kartu) --}}
                            <div class="divide-y divide-gray-100 md:hidden">
                                {{-- PERUBAHAN DI SINI: Mengubah latar belakang agar lebih kontras --}}
                                <div class="flex items-center gap-3 bg-slate-100 p-4 border-b border-slate-200">
                                    <input type="checkbox" id="check-all-mobile" class="cursor-pointer rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                                    <label for="check-all-mobile" class="text-sm font-semibold text-gray-700">Pilih Semua</label>
                                </div>
                                @foreach($docs as $d)
                                <div class="flex items-start gap-4 p-4 bg-white hover:bg-blue-50">
                                    <input type="checkbox" name="docs[]" value="{{ $d->id }}" class="checkbox mt-1.5 shrink-0 rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                                    <div class="flex-grow">
                                        <div class="mb-2 font-semibold text-gray-800" title="{{ $d->original_name ?? $d->stored_name }}">
                                            {{ $d->original_name ?? $d->stored_name }}
                                        </div>
                                        <div class="mb-3">
                                            <span class="rounded-md bg-blue-200 px-2 py-0.5 text-xs font-bold text-blue-800 ring-1 ring-blue-300">
                                                {{ \Carbon\Carbon::createFromFormat('Y-m', $d->period)->format('F Y') }}
                                            </span>
                                        </div>
                                        
                                        <div class="mb-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-600">
                                            <span>{{ $d->created_at->format('d M Y, H:i') }}</span>
                                            <span class="hidden sm:inline">|</span>
                                            <span>{{ number_format($d->size / 1024 / 1024, 2) }} MB</span>
                                        </div>

                                        <a href="{{ route('vendor.documents.download', $d) }}"
                                           class="inline-flex items-center gap-1.5 rounded-md border border-blue-300 bg-white px-3 py-1.5 text-sm font-medium text-blue-700 shadow-sm transition-all duration-200 hover:bg-blue-50 hover:border-blue-400">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/></svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- 3. Aksi Tombol (Download ZIP) --}}
                            <div class="border-t border-gray-200 bg-slate-50/70 px-4 py-3">
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-emerald-500 px-3 py-2 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:bg-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                        id="download-zip-btn" disabled>
                                    Download ZIP
                                </button>
                                <span id="selection-count" class="ml-3 text-sm text-gray-700 font-medium"></span>
                            </div>
                        </form>

                        {{-- Pagination --}}
                        @if ($docs->hasPages())
                            <div class="border-t border-gray-200 bg-white px-4 py-3">
                                {{ $docs->withQueryString()->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        {{-- Script tetap sama --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const checkAllDesktop = document.getElementById('check-all-desktop');
                const checkAllMobile = document.getElementById('check-all-mobile');
                const checkboxes = document.querySelectorAll('.checkbox');
                const downloadBtn = document.getElementById('download-zip-btn');
                const selectionCountEl = document.getElementById('selection-count');

                function toggleAll(source) {
                    checkboxes.forEach(checkbox => checkbox.checked = source.checked);
                    updateButtonState();
                }

                function updateButtonState() {
                    const checkedCount = document.querySelectorAll('.checkbox:checked').length;
                    
                    if (checkedCount > 0) {
                        downloadBtn.disabled = false;
                        selectionCountEl.textContent = `${checkedCount} file dipilih.`;
                    } else {
                        downloadBtn.disabled = true;
                        selectionCountEl.textContent = '';
                    }

                    // Update "check-all" states
                    const allChecked = checkedCount === checkboxes.length && checkboxes.length > 0;
                    if(checkAllDesktop) checkAllDesktop.checked = allChecked;
                    if(checkAllMobile) checkAllMobile.checked = allChecked;
                }

                if (checkAllDesktop) {
                    checkAllDesktop.addEventListener('change', function () {
                        toggleAll(this);
                    });
                }

                if (checkAllMobile) {
                    checkAllMobile.addEventListener('change', function () {
                        toggleAll(this);
                    });
                }
                
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateButtonState);
                });

                // Initial check
                updateButtonState();
            });
        </script>
    @endpush
</x-app-layout>