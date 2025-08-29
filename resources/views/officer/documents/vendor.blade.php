<x-app-layout>
    @php $tz = config('app.timezone'); @endphp
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="shrink-0 h-10 w-10 rounded-xl bg-blue-600/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6m0 0l-3-3m3 3l3-3M6 18h12" />
                </svg>
            </div>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $vendor->name }}
                    <span class="ml-2 text-sm px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-700 font-medium">
                        {{ $vendor->npwp }}
                    </span>
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Kelola & arsipkan bukti pajak vendor</p>
            </div>
            <div class="ml-auto">
                <a href="{{ route('officer.vendors.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Daftar Vendor
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if (session('ok'))
                <div class="bg-green-50 text-green-800 border border-green-200 rounded-xl px-4 py-3">
                    {{ session('ok') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 border border-red-200 rounded-xl px-4 py-3">
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- Kartu Upload --}}
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <form method="POST" action="{{ route('officer.documents.store') }}" enctype="multipart/form-data" class="grid md:grid-cols-3 gap-4">
                    @csrf
                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}" />

                    <div>
                        <x-input-label for="period" value="Periode (YYYY-MM)" />
                        <input id="period" type="month" name="period"
                               class="mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500"
                               required />
                        @error('period')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="file" value="File dokumen" />
                        <input id="file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                               class="mt-1 block w-full border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                               required />
                        <p class="text-xs text-gray-500 mt-1">Terima: PDF, JPG/PNG • Maks 5 MB</p>
                        @error('file')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="md:col-span-3">
                        <x-primary-button class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -ml-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/>
                            </svg>
                            Upload
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Kartu List Dokumen --}}
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Dokumen</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">
                        Total: {{ $docs->total() }}
                    </span>
                </div>

                @if ($docs->count() === 0)
                    <div class="text-center py-16">
                        <div class="mx-auto h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M8 7h8M8 11h8m-5 4h5M4 6a2 2 0 012-2h8.5L20 7.5V18a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                            </svg>
                        </div>
                        <p class="mt-3 text-gray-600">Belum ada dokumen untuk vendor ini.</p>
                        <p class="text-sm text-gray-500">Unggah dokumen pertama melalui form di atas.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-xs uppercase tracking-wide text-gray-600 bg-gray-50">
                                <tr>
                                    <th class="text-left py-3 px-3">Waktu</th>
                                    <th class="text-left py-3 px-3">Periode</th>
                                    <th class="text-left py-3 px-3">Nama File</th>
                                    <th class="text-left py-3 px-3">Uploader</th>
                                    <th class="text-left py-3 px-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($docs as $d)
                                    @php
                                        $ext = \Illuminate\Support\Str::lower(pathinfo($d->original_name ?? $d->stored_name, PATHINFO_EXTENSION));
                                        $isPdf = $ext === 'pdf';
                                    @endphp
                                    <tr class="hover:bg-gray-50/80">
                                        <td class="py-3 px-3 text-gray-700">
                                            {{ optional($d->created_at)->timezone($tz)->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="px-2 py-0.5 text-xs rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $d->period }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white">
                                                    @if($isPdf)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                                                            <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z"/>
                                                        </svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 16l4-4 4 4 4-4 4 4M4 8h16"/>
                                                        </svg>
                                                    @endif
                                                </span>
                                                <div class="truncate max-w-[32ch]" title="{{ $d->original_name ?? $d->stored_name }}">
                                                    {{ $d->original_name ?? $d->stored_name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3 text-gray-700">{{ $d->uploader->name ?? '—' }}</td>
                                        <td class="py-3 px-3">
                                            <div class="flex items-center gap-2">
                                                <a class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-gray-200 hover:border-blue-600 hover:text-blue-700 transition"
                                                   href="{{ route('officer.documents.download', $d) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/>
                                                    </svg>
                                                    <span class="text-sm">Download</span>
                                                </a>

                                                {{-- Hapus pakai modal SweetAlert2 --}}
                                                <form method="POST"
                                                      action="{{ route('officer.documents.destroy', $d) }}"
                                                      class="needs-confirmation"
                                                      data-confirm-title="Hapus Dokumen?"
                                                      data-confirm-text="Dokumen {{ addslashes($d->original_name ?? $d->stored_name) }} akan dihapus dari server. Tindakan ini tidak dapat dibatalkan."
                                                      data-confirm-icon="warning"
                                                      data-confirm-button="Ya, Hapus!">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-gray-200 hover:border-red-600 hover:text-red-700 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a1 1 0 011-1h6a1 1 0 011 1m-8 0H5"/>
                                                        </svg>
                                                        <span class="text-sm">Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $docs->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- SweetAlert2 + handler konfirmasi global --}}
    @once
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        document.addEventListener('submit', async function (e) {
          const form = e.target;
          if (!form.classList || !form.classList.contains('needs-confirmation')) return;
          if (form.dataset.confirmed === '1') return; // prevent double
          e.preventDefault();

          const title  = form.dataset.confirmTitle  || 'Yakin?';
          const text   = form.dataset.confirmText   || 'Tindakan ini tidak dapat dibatalkan.';
          const icon   = form.dataset.confirmIcon   || 'warning';
          const cta    = form.dataset.confirmButton || 'Ya, lanjut';
          const cancel = form.dataset.cancelButton  || 'Batal';

          if (typeof Swal === 'undefined') {
            if (confirm(text)) { form.dataset.confirmed='1'; form.submit(); }
            return;
          }

          const res = await Swal.fire({
            title, html: text, icon,
            showCancelButton: true,
            confirmButtonText: cta,
            cancelButtonText: cancel,
            reverseButtons: true,
            focusCancel: true
          });

          if (res.isConfirmed) { form.dataset.confirmed='1'; form.submit(); }
        }, true);
        </script>
    @endonce
</x-app-layout>
