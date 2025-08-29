<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 leading-tight">Dashboard</h2>
                <p class="text-gray-600 mt-1">Ringkasan aktivitas unggahan dokumen.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('officer.vendors.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-700 text-white text-sm font-semibold hover:bg-slate-800">
                    Kelola Vendor
                </a>
                <a href="{{ route('officer.bulk.form') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-sky-600 text-white text-sm font-semibold hover:bg-sky-700">
                    Bulk Upload
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Kartu ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-lg">
            <div class="text-sm text-gray-500">Total Dokumen</div>
            <div class="mt-1 text-3xl font-extrabold text-gray-900">{{ number_format($totalDocs) }}</div>
        </div>

        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-lg">
            <div class="text-sm text-gray-500">Terakhir Upload</div>
            @if($latest)
                <div class="mt-1 text-lg font-semibold text-gray-900">
                    {{ $latest->created_at->diffForHumans() }}
                </div>
                <div class="text-sm text-gray-600">
                    Vendor: <span class="font-medium">{{ $latest->vendor->name ?? '—' }}</span>
                    @if($latest->vendor?->npwp)
                        <span class="font-mono">({{ $latest->vendor->npwp }})</span>
                    @endif
                </div>
                <div class="text-sm text-gray-600">
                    Oleh: <span class="font-medium">{{ $latest->uploader->name ?? '—' }}</span>
                </div>
            @else
                <div class="mt-1 text-gray-600">—</div>
            @endif
        </div>

        <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-lg">
            <div class="text-sm text-gray-500">Total Vendor</div>
            <div class="mt-1 text-3xl font-extrabold text-gray-900">{{ number_format($totalVendors) }}</div>
        </div>
    </div>

    {{-- Tabel 10 upload terbaru --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-lg text-gray-900">Upload Terbaru</h3>
                <p class="text-sm text-gray-500">10 unggahan terakhir.</p>
            </div>
            <a href="{{ route('officer.vendors.index') }}"
               class="text-sm font-semibold text-sky-700 hover:underline">Lihat vendor</a>
        </div>

        <div class="overflow-x-auto border-t border-gray-200">
            <table class="min-w-full">
                <thead class="text-xs uppercase tracking-wider font-bold text-slate-800 bg-slate-100 border-b-2 border-slate-200">
                    <tr>
                        <th class="text-left py-3 px-4">Waktu</th>
                        <th class="text-left py-3 px-4">Vendor</th>
                        <th class="text-left py-3 px-4">Uploader</th>
                        <th class="text-left py-3 px-4">Nama Dokumen</th>
                        <th class="text-left py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($recentUploads as $doc)
                    <tr class="hover:bg-sky-50/50">
                        <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $doc->created_at->format('Y-m-d H:i') }}
                            <span class="text-gray-500"> ({{ $doc->created_at->diffForHumans() }})</span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <div class="font-semibold text-gray-900">{{ $doc->vendor->name ?? '—' }}</div>
                            @if($doc->vendor?->npwp)
                                <div class="font-mono text-gray-600">{{ $doc->vendor->npwp }}</div>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-700">
                            {{ $doc->uploader->name ?? '—' }}
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-700">
                            {{ $doc->original_name ?? '—' }}
                        </td>
                        <td class="py-3 px-4 text-sm">
                            @if($doc->vendor)
                                <a href="{{ route('officer.vendors.show', $doc->vendor) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">
                                    Lihat Vendor
                                </a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500">Belum ada upload.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
