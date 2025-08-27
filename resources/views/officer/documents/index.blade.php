<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                {{-- Judul dibuat lebih besar dan tebal --}}
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">Daftar Vendor</h2>
                {{-- Deskripsi diperbesar --}}
                <p class="text-base text-gray-600 mt-1">Pilih vendor untuk melihat atau mengunggah dokumen.</p>
            </div>

        </div>
    </x-slot>

    {{-- Banner "Bulk Upload" --}}
    <div class="bg-gradient-to-r from-sky-500 to-blue-600 sm:rounded-2xl p-6 shadow-lg">
        <div class="flex flex-wrap items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="shrink-0 h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center ring-1 ring-inset ring-white/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3.75 3.75M12 9.75l3.75 3.75M3.75 19.5h16.5" />
                    </svg>
                </div>
                <div>
                    {{-- Ukuran font judul banner dinaikkan --}}
                    <div class="font-bold text-xl text-white">Coba Fitur Bulk Upload</div>
                    {{-- Ukuran font deskripsi dinaikkan dan diberi jarak antar baris --}}
                    <p class="text-base text-blue-100 mt-1 max-w-xl leading-relaxed">
                        Unggah ratusan file sekaligus. Sistem akan memetakan file secara otomatis kedalam folder para vendor berdasarkan nama file.
                        Contoh: <code class="text-sm bg-black/20 text-white px-1.5 py-1 rounded-md">BLU_9876543210987654_2025-08.pdf</code>
                    </p>
                </div>
            </div>
            <a href="{{ route('officer.bulk.form') }}"
               class="shrink-0 inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-transparent bg-white text-blue-700 text-sm font-bold shadow-md hover:bg-blue-50 transition-colors">
                Buka Halaman Bulk
            </a>
        </div>
    </div>

    {{-- Toolbar & Tabel Vendor --}}
    <div class="bg-white border border-gray-200 sm:rounded-2xl shadow-lg p-5">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
            {{-- Ukuran font info diperbesar --}}
            <div class="text-base text-gray-600">
                Menampilkan <span class="font-bold text-gray-900">{{ $vendors->firstItem() }}-{{ $vendors->lastItem() }}</span> dari <span class="font-bold text-gray-900">{{ $vendors->total() }}</span> vendor
            </div>

            <form method="GET" action="{{ route('officer.vendors.index') }}" class="flex items-center gap-2">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    {{-- Ukuran font input diperbesar --}}
                    <input type="text" name="q" value="{{ $q ?? request('q') }}"
                           placeholder="Cari NPWP / Nama..."
                           class="w-full sm:w-64 rounded-md border-gray-300 pl-10 focus:border-sky-500 focus:ring-sky-500 text-base py-2">
                </div>
                @if(request('q'))
                    <a href="{{ route('officer.vendors.index') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tabel vendor --}}
        <div class="overflow-x-auto border-t border-gray-200">
            <table class="min-w-full">
                {{-- PERUBAHAN PALING SIGNIFIKAN: Header Tabel diperbesar dan dipertegas --}}
                <thead class="text-sm uppercase tracking-wider font-bold text-slate-800 bg-slate-100 border-b-2 border-slate-200">
                    <tr>
                        <th class="text-left py-4 px-4">NPWP</th>
                        <th class="text-left py-4 px-4">Nama</th>
                        <th class="text-left py-4 px-4">Dokumen</th>
                        <th class="text-left py-4 px-4">Terakhir Upload</th>
                        <th class="py-4 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($vendors as $v)
                        <tr class="hover:bg-sky-50/50">
                            {{-- Ukuran font isi tabel menjadi text-base --}}
                            <td class="py-4 px-4 font-mono text-gray-800 whitespace-nowrap text-base">{{ $v->npwp }}</td>
                            <td class="py-4 px-4 font-semibold text-gray-900 text-base">{{ $v->name }}</td>
                            <td class="py-4 px-4">
                                {{-- Ukuran badge dinaikkan --}}
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold ring-1 ring-inset ring-blue-200">
                                    {{ $v->documents_count }} dokumen
                                </span>
                            </td>
                            <td class="py-4 px-4 text-gray-600 whitespace-nowrap text-base">
                                {{ $v->latestDocument?->created_at?->diffForHumans() ?? '—' }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                {{-- Ukuran font tombol dinaikkan --}}
                                <a href="{{ route('officer.vendors.show', $v) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-sky-500 text-white font-semibold shadow-md hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 text-sm transition-all">
                                    <span>Lihat Dokumen</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 text-base">Tidak ada vendor ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($vendors->hasPages())
            <div class="mt-5 border-t border-gray-200 pt-4">
                {{ $vendors->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-app-layout>