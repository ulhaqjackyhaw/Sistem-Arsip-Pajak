<div id="vendorTable" hx-swap="outerHTML">
    {{-- Update info jumlah di toolbar (OOB) --}}
    {{-- <div id="vendorMeta" hx-swap-oob="true" class="text-base text-gray-600">
        @if($vendors->total())
            Menampilkan <span class="font-bold text-gray-900">{{ $vendors->firstItem() }}-{{ $vendors->lastItem() }}</span>
            dari <span class="font-bold text-gray-900">{{ $vendors->total() }}</span> vendor
        @else
            Tidak ada data
        @endif
    </div> --}}

    <div class="overflow-x-auto border-t border-gray-200">
        <table class="min-w-full">
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
                        <td class="py-4 px-4 font-mono text-gray-800 whitespace-nowrap text-base">{{ $v->npwp }}</td>
                        <td class="py-4 px-4 font-semibold text-gray-900 text-base">{{ $v->name }}</td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold ring-1 ring-inset ring-blue-200">
                                {{ $v->documents_count }} dokumen
                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-600 whitespace-nowrap text-base">
                            {{ optional($v->latestDocument?->created_at)->timezone(config('app.timezone'))?->diffForHumans() ?? '—' }}
                        </td>
                        <td class="py-4 px-4 text-right">
                            <a href="{{ route('officer.vendors.show', $v) }}"
                               hx-boost="false"
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
        <div class="mt-5 border-t border-gray-200 pt-4" hx-boost="false">
            {{ $vendors->withQueryString()->links() }}
        </div>
    @endif
</div>
