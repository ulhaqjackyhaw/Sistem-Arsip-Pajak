<x-app-layout>
    <x-slot name="header">
        {{-- Header dengan gaya gelap yang konsisten --}}
        <div class="bg-slate-800 p-6 sm:p-8 rounded-2xl shadow-lg">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-2xl text-white leading-tight">Bulk Upload Bukti Pajak</h2>
                    <p class="text-base text-slate-300 mt-1">Unggah banyak PDF sekaligus. Sistem akan menyortir otomatis.</p>
                </div>
                <a href="{{ route('officer.vendors.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-500 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 transition-colors">
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

            {{-- Petunjuk format file dibuat menonjol dengan warna latar --}}
            <div class="bg-sky-50 shadow-lg sm:rounded-2xl p-6 border border-sky-200 ring-4 ring-sky-100">
                <div class="flex items-start gap-4">
                    <div class="shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <div>
                         <h3 class="font-bold text-lg text-gray-900">Perhatian: Format Nama File Wajib Sesuai</h3>
                         <p class="text-base text-gray-600 mt-1 leading-relaxed">
                             Sistem hanya akan memproses file PDF yang namanya diawali <code class="font-bold text-sky-700">BLU_</code> atau <code class="font-bold text-sky-700">BPU_</code>, diikuti langsung dengan **NPWP** dan mengandung **periode** tanggal.
                         </p>
                    </div>
                </div>
                <ul class="mt-4 grid sm:grid-cols-2 gap-3 text-base">
                    {{-- Contoh format dengan font lebih besar --}}
                    <li class="px-4 py-3 rounded-lg bg-slate-50 border border-slate-200">
                        <span class="font-mono text-slate-800">BLU_&lt;NPWP&gt;_&lt;YYYY-MM&gt;.pdf</span>
                        <div class="text-sm text-slate-500 mt-1">Contoh: BLU_9876543210987654_2025-08.pdf</div>
                    </li>
                    <li class="px-4 py-3 rounded-lg bg-slate-50 border border-slate-200">
                        <span class="font-mono text-slate-800">BPU_&lt;NPWP&gt;_&lt;MM&gt;_&lt;YYYY&gt;....pdf</span>
                        <div class="text-sm text-slate-500 mt-1">Contoh: BPU_0012025888542000_07_2025_....pdf</div>
                    </li>
                </ul>
            </div>

            {{-- Form upload --}}
            <div class="bg-white shadow-lg sm:rounded-2xl p-6 border border-gray-100">
                <form method="POST" action="{{ route('officer.bulk.upload') }}" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-base">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Dropzone dibuat lebih interaktif --}}
                    <div id="dropzone" class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center transition flex flex-col items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <input id="files" name="files[]" type="file" accept="application/pdf,.pdf" multiple class="hidden">
                        <button type="button" id="filePicker" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 text-white px-5 py-2.5 text-base font-semibold shadow-md hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 -ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Pilih File PDF
                        </button>
                        <p class="mt-3 text-base text-gray-700">
                            atau <b>tarik & letakkan</b> file ke area ini.
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Maks 300 file • PDF saja • 20 MB per file</p>
                    </div>

                    {{-- Info ringkas dengan tombol "Bersihkan" yang jelas --}}
                    <div id="stats" class="hidden mt-4 flex items-center gap-3">
                        <div class="inline-flex items-center gap-2 rounded-lg bg-slate-100 border border-slate-200 px-3 py-1.5 text-base text-slate-800">
                            <span id="count" class="font-bold">0</span> file dipilih
                            <span class="text-gray-300">•</span>
                            total <span id="totalSize" class="font-bold">0 B</span>
                        </div>
                        <button id="clearBtn" type="button" class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-red-600 font-semibold">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                             </svg>
                            Bersihkan
                        </button>
                    </div>

                    {{-- Preview tabel dengan header lebih jelas --}}
                    <div id="previewWrap" class="hidden mt-4 overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full text-base">
                            <thead class="bg-slate-100 text-sm font-bold text-slate-800 uppercase tracking-wider">
                                <tr>
                                    <th class="text-left py-3 px-3">File</th>
                                    <th class="text-left py-3 px-3">NPWP (deteksi)</th>
                                    <th class="text-left py-3 px-3">Periode (deteksi)</th>
                                    <th class="text-left py-3 px-3">Ukuran</th>
                                    <th class="text-left py-3 px-3">Status</th>
                                </tr>
                            </thead>
                            <tbody id="previewBody" class="divide-y"></tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button id="submitBtn" disabled class="px-6 py-3 text-base font-bold">Mulai Upload</x-primary-button>
                        <span class="text-sm text-gray-500">Pastikan kolom deteksi sudah benar sebelum mengunggah.</span>
                    </div>
                </form>
            </div>

            {{-- Hasil proses dengan header yang lebih jelas --}}
            @if (session('results'))
                @php $res = session('results'); @endphp
                <div class="bg-white shadow-lg sm:rounded-2xl border border-gray-100">
                    <div class="p-6 border-b border-gray-200 bg-slate-50 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-lg text-gray-900">Hasil Proses Upload</h3>
                            <div class="flex items-center gap-2 text-sm font-semibold">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Berhasil: {{ count($res['success'] ?? []) }}
                                </span>
                                <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-800 border border-red-200">
                                    Gagal: {{ count($res['failed'] ?? []) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        @if (!empty($res['success']))
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-base">
                                    <thead class="bg-emerald-100 text-sm font-bold text-emerald-800">
                                        <tr>
                                            <th class="text-left py-3 px-3">File</th>
                                            <th class="text-left py-3 px-3">Vendor</th>
                                            <th class="text-left py-3 px-3">Periode</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach ($res['success'] as $row)
                                            <tr>
                                                <td class="py-3 px-3">{{ $row['name'] }}</td>
                                                <td class="py-3 px-3">{{ $row['vendor'] }}</td>
                                                <td class="py-3 px-3">{{ $row['period'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if (!empty($res['failed']))
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-base">
                                    <thead class="bg-red-100 text-sm font-bold text-red-800">
                                        <tr>
                                            <th class="text-left py-3 px-3">File</th>
                                            <th class="text-left py-3 px-3">Alasan Gagal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach ($res['failed'] as $row)
                                            <tr>
                                                <td class="py-3 px-3">{{ $row['name'] }}</td>
                                                <td class="py-3 px-3">{{ $row['reason'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Script JavaScript tidak perlu diubah --}}
    @push('scripts')
    <script>
        // Script Anda yang sudah ada di sini
        (function(){
            const dz = document.getElementById('dropzone');
            const picker = document.getElementById('filePicker');
            const input = document.getElementById('files');
            const stats = document.getElementById('stats');
            const countEl = document.getElementById('count');
            const totalEl = document.getElementById('totalSize');
            const clearBtn = document.getElementById('clearBtn');
            const previewWrap = document.getElementById('previewWrap');
            const previewBody = document.getElementById('previewBody');
            const submitBtn = document.getElementById('submitBtn');

            function fmtSize(bytes) {
                if (bytes < 1024) return bytes + ' B';
                const units = ['KB','MB','GB']; let i=-1; do { bytes/=1024; i++; } while (bytes>=1024 && i<units.length-1);
                return bytes.toFixed(1) + ' ' + units[i];
            }

            function parseName(filename) {
                const stem = filename.replace(/\.[^.]+$/,'').trim();
                const m0 = stem.match(/^(?:BLU|BPU)[\s_\-]+(.+)$/i);
                if (!m0) return null;
                let tail = m0[1].trim();
                const m1 = tail.match(/^(\d{8,20})(.*)$/);
                if (!m1) return null;
                const npwp = (m1[1] || '').replace(/\D/g,'');
                const after = (m1[2] || '').trim();
                let period = null;
                let ym = after.match(/\b((?:19|20)\d{2})-(0[1-9]|1[0-2])\b/);
                if (ym) period = ym[1] + '-' + ym[2];
                if (!period) {
                    const tokens = after.split(/[\s_\-]+/).filter(Boolean);
                    for (let i=0;i<tokens.length-1 && !period;i++) {
                        const a=tokens[i], b=tokens[i+1];
                        const isMM  = /^(0[1-9]|1[0-2])$/.test(a);
                        const isYY  = /^(?:19|20)\d{2}$/.test(b);
                        const isYY2 = /^(?:19|20)\d{2}$/.test(a);
                        const isMM2 = /^(0[1-9]|1[0-2])$/.test(b);
                        if (isMM && isYY) period = b + '-' + a;
                        else if (isYY2 && isMM2) period = a + '-' + b;
                    }
                }
                if (!period) {
                    const found = after.match(/\d{8}/g);
                    if (found) {
                        for (const d8 of found) {
                            const dd = +d8.slice(0,2), mm = +d8.slice(2,4), yy = +d8.slice(4);
                            if (yy>=1900 && mm>=1 && mm<=12 && dd>=1 && dd<=31) {
                                period = String(yy).padStart(4,'0') + '-' + String(mm).padStart(2,'0');
                                break;
                            }
                        }
                    }
                }
                if (!period) {
                    const found6 = after.match(/\d{6}/g);
                    if (found6) {
                        for (const d6 of found6) {
                            const yyyy = +d6.slice(0,4), mm = +d6.slice(4);
                            if (yyyy>=1900 && mm>=1 && mm<=12) { period = `${yyyy}-${String(mm).padStart(2,'0')}`; break; }
                            const mm2 = +d6.slice(0,2), yyyy2 = +d6.slice(2);
                            if (yyyy2>=1900 && mm2>=1 && mm2<=12) { period = `${yyyy2}-${String(mm2).padStart(2,'0')}`; break; }
                        }
                    }
                }
                if (!npwp || !period) return null;
                return { npwp, period };
            }

            function renderPreview(files) {
                let total = 0;
                previewBody.innerHTML = '';
                Array.from(files).forEach(f => {
                    total += f.size;
                    const meta = parseName(f.name);
                    const ok = !!meta;
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50/70';
                    tr.innerHTML = `
                        <td class="py-3 px-3">${f.name}</td>
                        <td class="py-3 px-3 ${ok ? 'font-mono text-slate-700' : 'text-red-600 font-semibold'}">${ok ? meta.npwp : '—'}</td>
                        <td class="py-3 px-3 ${ok ? 'font-mono text-slate-700' : 'text-red-600 font-semibold'}">${ok ? meta.period : 'Tidak terdeteksi'}</td>
                        <td class="py-3 px-3">${fmtSize(f.size)}</td>
                        <td class="py-3 px-3">
                            ${ok
                                ? '<span class="text-emerald-800 bg-emerald-100 border border-emerald-200 text-sm font-semibold px-2.5 py-0.5 rounded-full">OK</span>'
                                : '<span class="text-red-800 bg-red-100 border border-red-200 text-sm font-semibold px-2.5 py-0.5 rounded-full">Nama tidak sesuai</span>'}
                        </td>
                    `;
                    previewBody.appendChild(tr);
                });
                countEl.textContent = files.length;
                totalEl.textContent = fmtSize(total);
                stats.classList.toggle('hidden', files.length === 0);
                previewWrap.classList.toggle('hidden', files.length === 0);
                submitBtn.disabled = files.length === 0;
            }

            picker.addEventListener('click', () => input.click());
            clearBtn.addEventListener('click', () => {
                input.value = '';
                renderPreview([]);
            });
            input.addEventListener('change', () => renderPreview(input.files));
            ['dragenter','dragover'].forEach(ev => dz.addEventListener(ev, e => {
                e.preventDefault();
                e.stopPropagation();
                dz.classList.remove('border-gray-300','bg-gray-50');
                dz.classList.add('border-sky-500','bg-sky-50', 'ring-4', 'ring-sky-200');
            }));
            ['dragleave','drop'].forEach(ev => dz.addEventListener(ev, e => {
                e.preventDefault();
                e.stopPropagation();
                dz.classList.remove('border-sky-500','bg-sky-50', 'ring-4', 'ring-sky-200');
                dz.classList.add('border-gray-300','bg-gray-50');
            }));
            dz.addEventListener('drop', e => {
                const dt = e.dataTransfer;
                if (!dt || !dt.files) return;
                
                // Filter hanya untuk PDF
                const pdfFiles = Array.from(dt.files).filter(file => file.type === "application/pdf");
                
                // Buat DataTransfer baru untuk menyimpan file yang difilter
                const dataTransfer = new DataTransfer();
                pdfFiles.forEach(file => dataTransfer.items.add(file));

                input.files = dataTransfer.files;
                renderPreview(input.files);
            });
        })();
    </script>
    @endpush
</x-app-layout>