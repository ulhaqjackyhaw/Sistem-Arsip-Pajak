<x-app-layout>
    <x-slot name="header">
        {{-- Header dibuat agar bisa wrap di mobile --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Vendor</h2>
                <p class="text-sm text-gray-500 mt-1">Pilih vendor untuk melihat/unggah dokumen.</p>
            </div>

            <a href="{{ route('officer.bulk.form') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 -ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3.75 3.75M12 9.75l3.75 3.75M3.75 19.5h16.5" />
                </svg>
                <span>Bulk Upload</span>
            </a>
        </div>
    </x-slot>

    {{-- Tips Bulk Upload --}}
    <div class="bg-blue-50 border border-blue-200 sm:rounded-2xl p-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="shrink-0 h-10 w-10 rounded-xl bg-blue-600/10 flex items-center justify-center ring-1 ring-inset ring-blue-600/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3.75 3.75M12 9.75l3.75 3.75M3.75 19.5h16.5" />
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-gray-800">Coba Fitur Bulk Upload</div>
                    <p class="text-sm text-gray-600 mt-1">
                        Unggah ratusan file sekaligus. Sistem akan memetakan file secara otomatis.
                        <br class="hidden sm:block">
                        Contoh: <code class="text-xs bg-gray-200 px-1 py-0.5 rounded">BLU_9876543210987654_2025-08.pdf</code>
                    </p>
                </div>
            </div>
            <a href="{{ route('officer.bulk.form') }}"
               class="shrink-0 inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 bg-white hover:border-gray-400 text-sm font-medium">
                Buka Halaman Bulk
            </a>
        </div>
    </div>

    {{-- Toolbar: jumlah & pencarian --}}
    <div class="bg-white border border-gray-100 sm:rounded-2xl shadow-sm p-5">
        {{-- flex-wrap agar form pencarian bisa turun di mobile --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div class="text-sm text-gray-600">
                Menampilkan <span class="font-medium text-gray-900">{{ $vendors->firstItem() }}-{{ $vendors->lastItem() }}</span> dari <span class="font-medium text-gray-900">{{ $vendors->total() }}</span> vendor
            </div>

            <form method="GET" action="{{ route('officer.vendors.index') }}" class="flex items-center gap-2">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ $q ?? request('q') }}"
                           placeholder="Cari NPWP / Nama..."
                           class="w-full sm:w-64 rounded-md border-gray-300 pl-9 focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                @if(request('q'))
                    <a href="{{ route('officer.vendors.index') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700">Back</a>
                @endif
            </form>
        </div>

        {{-- Tabel vendor --}}
        <div class="overflow-x-auto border-t border-gray-200">
            <table class="min-w-full text-sm">
                <thead class="text-xs uppercase tracking-wide text-gray-600 bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4">NPWP</th>
                        <th class="text-left py-3 px-4">Nama</th>
                        <th class="text-left py-3 px-4">Dokumen</th>
                        <th class="text-left py-3 px-4">Terakhir Upload</th>
                        <th class="py-3 px-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($vendors as $v)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 font-mono text-gray-800 whitespace-nowrap">{{ $v->npwp }}</td>
                            <td class="py-3 px-4">{{ $v->name }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 border border-gray-200 text-gray-700 text-xs">
                                    {{ $v->documents_count }} dokumen
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                {{ $v->latestDocument?->created_at?->diffForHumans() ?? '—' }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('officer.vendors.show', $v) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-gray-300 bg-white hover:border-blue-600 hover:text-blue-700 transition text-xs">
                                    <span>Buka</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada vendor ditemukan.</td>
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