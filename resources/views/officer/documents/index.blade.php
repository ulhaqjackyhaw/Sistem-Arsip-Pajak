<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">Daftar Vendor</h2>
                <p class="text-base text-gray-600 mt-1">Pilih vendor untuk melihat atau mengunggah dokumen.</p>
            </div>
        </div>
    </x-slot>

    {{-- Banner --}}
    <div class="bg-gradient-to-r from-sky-500 to-blue-600 sm:rounded-2xl p-6 shadow-lg">
        <div class="flex flex-wrap items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="shrink-0 h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center ring-1 ring-inset ring-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3.75 3.75M12 9.75l3.75 3.75M3.75 19.5h16.5"/>
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-xl text-white">Coba Fitur Bulk Upload</div>
                    <p class="text-base text-blue-100 mt-1 max-w-xl leading-relaxed">
                        Unggah ratusan file sekaligus. Sistem akan memetakan file secara otomatis ke folder vendor berdasarkan nama file.
                        Contoh:
                        <span class="inline-block max-w-full overflow-x-auto align-middle">
                            <code class="font-mono text-sm whitespace-nowrap bg-black/20 text-white px-1.5 py-1 rounded-md">
                                BLU_9876543210987654_2025-08.pdf
                            </code>
                        </span>
                    </p>
                </div>
            </div>
            <a href="{{ route('officer.bulk.form') }}"
               class="shrink-0 inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-transparent bg-white text-blue-700 text-sm font-bold shadow-md hover:bg-blue-50 transition-colors">
                Buka Halaman Bulk
            </a>
        </div>
    </div>

    <div class="bg-white border border-gray-200 sm:rounded-2xl shadow-lg p-5 mt-6">

        {{-- Toolbar (tetap di view utama) --}}
        @php $isSearching = filled(request('q')); @endphp
        <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
            {{-- Kiri: info jumlah --}}
            <div id="vendorMeta" class="text-base text-gray-600">
                @if($vendors->total())
                    Menampilkan <span class="font-bold text-gray-900">{{ $vendors->firstItem() }}-{{ $vendors->lastItem() }}</span>
                    dari <span class="font-bold text-gray-900">{{ $vendors->total() }}</span> vendor
                @else
                    Tidak ada data
                @endif
            </div>

            {{-- Kanan: tombol kembali + search --}}
            <div class="ml-auto flex items-center gap-2">
                <a id="btnBack" href="{{ route('officer.vendors.index') }}"
                   class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 {{ $isSearching ? '' : 'hidden' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>

                <form method="GET"
                      action="{{ route('officer.vendors.index') }}"
                      class="relative flex items-center gap-2"
                      hx-get="{{ route('officer.vendors.index') }}"
                      hx-trigger="input delay:300ms from:#qInput"
                      hx-target="#vendorTable"
                      hx-select="#vendorTable"  {{-- hanya ambil fragment tabel --}}
                      hx-push-url="true">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                                <path stroke="currentColor" stroke-width="1.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                        </div>
                        <input id="qInput" type="text" name="q" value="{{ $q ?? request('q') }}"
                               placeholder="Cari NPWP / Nama..."
                               class="w-full sm:w-96 rounded-md border-gray-300 pl-10 pr-8 focus:border-sky-500 focus:ring-sky-500 text-base py-2">
                        <button type="button"
                                onclick="const i=document.getElementById('qInput'); i.value=''; i.dispatchEvent(new Event('input'));"
                                class="absolute inset-y-0 right-0 px-2 text-gray-400 hover:text-gray-600" aria-label="Bersihkan">✕</button>
                        <div class="htmx-indicator absolute inset-y-0 right-7 flex items-center">
                            <svg class="animate-spin h-4 w-4 text-sky-600" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"/>
                            </svg>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABEL + PAGINATION (diswap HTMX) --}}
        @include('officer.partials.vendor_table', ['vendors' => $vendors, 'q' => $q ?? request('q')])
    </div>

    {{-- JS kecil untuk toggle tombol "Kembali" saat mengetik / selesai --}}
    @push('scripts')
    <script>
      function syncBackBtn(){
        const q = (document.getElementById('qInput')?.value || '').trim();
        const btn = document.getElementById('btnBack');
        if(btn) btn.classList.toggle('hidden', q.length === 0);
      }
      document.addEventListener('DOMContentLoaded', syncBackBtn);
      document.addEventListener('input', e => { if(e.target && e.target.id==='qInput') syncBackBtn(); });
      document.body.addEventListener('htmx:afterOnLoad', syncBackBtn);
    </script>
    @endpush
</x-app-layout>
