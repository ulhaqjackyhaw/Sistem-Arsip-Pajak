<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Vendor</h2>
                <p class="text-sm text-gray-500">Pilih vendor untuk melihat/unggah dokumen, atau gunakan Bulk Upload untuk unggah ratusan file sekaligus.</p>
            </div>

            <a href="{{ route('officer.bulk.form') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/>
                </svg>
                Bulk Upload
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Tips Bulk Upload --}}
            <div class="bg-white border border-gray-100 sm:rounded-2xl shadow-sm p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 h-10 w-10 rounded-xl bg-blue-600/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Bulk Upload</div>
                            <p class="text-sm text-gray-600">
                                Sistem otomatis memetakan file ke vendor & periode dari nama file.
                                Contoh: <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">BLU_9876543210987654_2025-08.pdf</code>,
                                <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">BPU_0012025888542000_07_2025_xxx.pdf</code>.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('officer.bulk.form') }}"
                       class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-200 hover:border-gray-300">
                        Buka Halaman Bulk
                    </a>
                </div>
            </div>

            {{-- Toolbar: jumlah & pencarian --}}
            <div class="bg-white border border-gray-100 sm:rounded-2xl shadow-sm p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div class="text-sm text-gray-600">
                        Total: <span class="font-medium text-gray-900">{{ $vendors->total() }}</span> vendor
                    </div>

                    <form method="GET" action="{{ route('officer.vendors.index') }}" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ $q ?? request('q') }}"
                               placeholder="Cari NPWP / Nama vendor"
                               class="w-64 max-w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @if(request('q'))
                            <a href="{{ route('officer.vendors.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Reset</a>
                        @endif
                        <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md bg-gray-800 text-white hover:bg-black text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
                            </svg>
                            Cari
                        </button>
                    </form>
                </div>

                {{-- Tabel vendor --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-xs uppercase tracking-wide text-gray-600 bg-gray-50">
                            <tr>
                                <th class="text-left py-3 px-3">NPWP</th>
                                <th class="text-left py-3 px-3">Nama</th>
                                <th class="text-left py-3 px-3">Dokumen</th>
                                <th class="text-left py-3 px-3">Terakhir Upload</th>
                                <th class="text-left py-3 px-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($vendors as $v)
                                <tr class="hover:bg-gray-50/70">
                                    <td class="py-3 px-3 font-mono text-gray-800">{{ $v->npwp }}</td>
                                    <td class="py-3 px-3">{{ $v->name }}</td>
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 border border-gray-200 text-gray-700 text-xs">
                                            {{ $v->documents_count }} dokumen
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-gray-700">
                                        {{ $v->latestDocument?->created_at?->format('Y-m-d H:i') ?? '—' }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="{{ route('officer.vendors.show', $v) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-gray-200 hover:border-blue-600 hover:text-blue-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            Buka
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

                <div class="mt-4">
                    {{ $vendors->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
