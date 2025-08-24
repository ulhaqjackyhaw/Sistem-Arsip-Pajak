<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600/10">
                    <svg class="h-5 w-5 text-blue-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Manajemen Dokumen Vendor</h2>
                    <p class="text-sm text-gray-500">Lihat dan unduh dokumen perusahaan Anda secara aman.</p>
                </div>
            </div>

            @if (\Illuminate\Support\Facades\Route::has('vendor.documents.create'))
                <a href="{{ route('vendor.documents.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-3 py-2 text-sm text-white hover:bg-green-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Upload Dokumen
                </a>
            @endif
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-6">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
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
                        <div class="flex items-start gap-3 sm:items-center">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100">
                                <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
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
                                           class="w-full rounded-lg border-gray-300 py-2 pl-9 pr-3 text-sm focus:border-blue-500 focus:ring-blue-500/30 sm:w-64">
                                    <svg class="absolute left-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M10 18a8 8 0 100-16 8 8 0 000 16z"/>
                                    </svg>
                                </div>
                                <input type="month" name="period" value="{{ request('period') }}"
                                       class="rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500/30">
                                <button class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                    Cari
                                </button>
                                @if(request()->hasAny(['q','period']))
<a href="{{ route('vendor.documents.index') }}"
    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">
    Back
</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kontainer data --}}
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    @if ($docs->isEmpty())
                        <div class="p-10 text-center">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8M8 11h8m-5 4h5M4 6a2 2 0 012-2h8.5L20 7.5V18a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                </svg>
                            </div>
                            <div class="font-medium text-gray-700">Tidak ada dokumen ditemukan</div>
                            <div class="text-sm text-gray-500">Coba ubah kata kunci atau filter periode.</div>
                        </div>
                    @else
                        {{-- Form untuk download ZIP sekarang membungkus kedua tampilan (desktop dan mobile) --}}
                        <form method="POST" action="{{ route('vendor.documents.downloadZip') }}" id="download-form">
                            @csrf
                            
                            {{-- 1. Tampilan Desktop (Tabel): Terlihat di layar 'md' ke atas --}}
                            <div class="hidden md:block">
                                <table class="min-w-full text-sm text-gray-800">
                                    <thead class="bg-gray-50 text-gray-600">
                                        <tr>
                                            <th class="px-4 py-3 text-left">
                                                <input type="checkbox" id="check-all-desktop" class="cursor-pointer rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                                            </th>
                                            <th class="px-4 py-3 text-left">Waktu</th>
                                            <th class="px-4 py-3 text-left">Periode</th>
                                            <th class="px-4 py-3 text-left">Nama File</th>
                                            <th class="px-4 py-3 text-left">Ukuran</th>
                                            <th class="px-4 py-3 text-left">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($docs as $d)
                                            <tr class="hover:bg-gray-50/80">
                                                <td class="px-4 py-3">
                                                    <input type="checkbox" name="docs[]" value="{{ $d->id }}" class="checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                                                </td>
                                                <td class="px-4 py-3">{{ $d->created_at->format('Y-m-d H:i') }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="rounded-md border border-blue-100 bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $d->period)->format('F Y') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white">
                                                            <svg class="h-4 w-4 text-red-600" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z"/></svg>
                                                        </span>
                                                        <div class="max-w-[40ch] truncate" title="{{ $d->original_name ?? $d->stored_name }}">
                                                            {{ $d->original_name ?? $d->stored_name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3">{{ number_format($d->size / 1024 / 1024, 2) }} MB</td>
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('vendor.documents.download', $d) }}"
                                                       class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-sm hover:border-blue-600 hover:text-blue-700">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/></svg>
                                                        Download
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- 2. Tampilan Mobile (Kartu): Terlihat di layar di bawah 'md' --}}
                            <div class="divide-y md:hidden">
                                {{-- Opsi "Pilih Semua" untuk mobile --}}
                                <div class="flex items-center gap-3 bg-gray-50 p-4">
                                     <input type="checkbox" id="check-all-mobile" class="cursor-pointer rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                                     <label for="check-all-mobile" class="text-sm font-medium text-gray-700">Pilih Semua</label>
                                </div>
                                @foreach($docs as $d)
                                <div class="flex items-start gap-4 p-4">
                                    <input type="checkbox" name="docs[]" value="{{ $d->id }}" class="checkbox mt-1.5 shrink-0 rounded border-gray-300 text-blue-600 focus:ring-blue-500"/>
                                    <div class="flex-grow">
                                        {{-- Nama file dan periode --}}
                                        <div class="mb-2 font-medium text-gray-800" title="{{ $d->original_name ?? $d->stored_name }}">
                                            {{ $d->original_name ?? $d->stored_name }}
                                        </div>
                                        <div class="mb-3">
                                             <span class="rounded-md border border-blue-100 bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">
                                                {{ \Carbon\Carbon::createFromFormat('Y-m', $d->period)->format('F Y') }}
                                            </span>
                                        </div>
                                        
                                        {{-- Detail Waktu dan Ukuran --}}
                                        <div class="mb-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                                            <span>{{ $d->created_at->format('d M Y, H:i') }}</span>
                                            <span class="hidden sm:inline">|</span>
                                            <span>{{ number_format($d->size / 1024 / 1024, 2) }} MB</span>
                                        </div>

                                        {{-- Tombol Aksi --}}
                                        <a href="{{ route('vendor.documents.download', $d) }}"
                                           class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/></svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- 3. Aksi Tombol (Download ZIP) - Terlihat di semua ukuran layar --}}
                            <div class="border-t border-gray-200 bg-gray-50/70 px-4 py-3">
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50"
                                        id="download-zip-btn" disabled>
                                    Download ZIP
                                </button>
                                <span id="selection-count" class="ml-3 text-sm text-gray-600"></span>
                            </div>

                        </form>

                        {{-- Pagination --}}
                        <div class="border-t border-gray-200 px-4 py-3">
                            {{ $docs->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
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