<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Bulk Upload Bukti Pajak</h2>
                <p class="text-sm text-gray-500">Unggah banyak PDF sekaligus. Sistem akan menyortir otomatis berdasarkan NPWP & periode dari nama file.</p>
            </div>
            <a href="{{ route('officer.vendors.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Daftar Vendor
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Petunjuk pola nama --}}
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <h3 class="font-semibold text-gray-800">Format nama file</h3>
                <p class="text-sm text-gray-600 mt-1">Wajib diawali: <span class="font-semibold">BLU_</span> atau <span class="font-semibold">BPU_</span>, lalu <span class="font-semibold">NPWP</span>. Periode akan otomatis dicari dalam nama file.</p>
                <ul class="mt-3 grid sm:grid-cols-2 gap-2 text-sm">
                    <li class="px-3 py-2 rounded-lg bg-gray-50 border border-gray-200">
                        <span class="font-mono text-gray-800">BLU_&lt;NPWP&gt;_&lt;YYYY-MM&gt;.pdf</span>
                        <div class="text-xs text-gray-500">Contoh: BLU_9876543210987654_2025-08.pdf</div>
                    </li>
                    <li class="px-3 py-2 rounded-lg bg-gray-50 border border-gray-200">
                        <span class="font-mono text-gray-800">BPU_&lt;NPWP&gt;_&lt;MM&gt;_&lt;YYYY&gt;_....pdf</span>
                        <div class="text-xs text-gray-500">Contoh: BPU_0012025888542000_07_2025_....pdf</div>
                    </li>
                    <li class="px-3 py-2 rounded-lg bg-gray-50 border border-gray-200">
                        <span class="font-mono text-gray-800">BLU_&lt;NPWP&gt;_&lt;YYYYMM&gt;.pdf</span>
                        <div class="text-xs text-gray-500">Contoh: BLU_9876543210987654_202508.pdf</div>
                    </li>
                    <li class="px-3 py-2 rounded-lg bg-gray-50 border border-gray-200">
                        <span class="font-mono text-gray-800">BLU_&lt;NPWP&gt; &lt;NAMA PT&gt;_&lt;DDMMYYYY&gt;.pdf</span>
                        <div class="text-xs text-gray-500">Contoh: BLU_9876543210987654 PT ABC_08052025.pdf</div>
                    </li>
                </ul>
            </div>

            {{-- Form upload --}}
            <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <form method="POST" action="{{ route('officer.bulk.upload') }}" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            <ul class="list-disc pl-5 space-y-0.5">
                                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                        {{-- === DROPZONE (Pilih PDF saja) === --}}
<div id="dropzone"
     class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center transition">

    {{-- input utama yang DIKIRIM ke server --}}
    <input id="files" name="files[]" type="file" accept="application/pdf,.pdf" multiple class="hidden">

    <div class="flex items-center justify-center gap-2 flex-wrap">
            <button type="button" id="filePicker"
    class="inline-flex items-center gap-2 rounded-md border border-blue-700 bg-blue-600 text-white px-4 py-2 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
    style="background-color:#2563eb;color:#fff;border-color:#1d4ed8">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 -ml-1" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v10m0 0l-3-3m3 3l3-3M4 20h16"/>
    </svg>
    Pilih PDF
</button>

    </div>

    <p class="mt-3 text-gray-700">
        Kamu bisa memilih <b>banyak file PDF sekaligus</b> (hold <kbd>Ctrl</kbd>/<kbd>Cmd</kbd> saat memilih).
        Atau, <b>tarik & letakkan</b> file ke area ini.
    </p>
    <p class="text-xs text-gray-500 mt-1">Maks 300 file • PDF saja • 20 MB per file</p>
</div>


                    {{-- Info ringkas --}}
                    <div id="stats" class="hidden mt-4 text-sm text-gray-700">
                        <div class="inline-flex items-center gap-2 rounded-lg bg-gray-50 border border-gray-200 px-3 py-1.5">
                            <span id="count">0</span> file dipilih
                            <span class="text-gray-400">•</span>
                            total <span id="totalSize">0 B</span>
                        </div>
                        <button id="clearBtn" type="button"
                                class="ml-2 text-gray-600 hover:text-gray-800 underline decoration-dotted">
                            bersihkan
                        </button>
                    </div>

                    {{-- Preview tabel --}}
                    <div id="previewWrap" class="hidden mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="text-left py-2 px-3">File</th>
                                    <th class="text-left py-2 px-3">NPWP (deteksi)</th>
                                    <th class="text-left py-2 px-3">Periode (deteksi)</th>
                                    <th class="text-left py-2 px-3">Nama PT (opsional)</th>
                                    <th class="text-left py-2 px-3">Ukuran</th>
                                    <th class="text-left py-2 px-3">Status</th>
                                </tr>
                            </thead>
                            <tbody id="previewBody" class="divide-y"></tbody>
                        </table>
                    </div>

                    <div class="mt-5 flex items-center gap-3">
                        <x-primary-button id="submitBtn" disabled>Mulai Upload</x-primary-button>
                        <span class="text-xs text-gray-500">Pastikan kolom “deteksi” sudah masuk akal sebelum mengunggah.</span>
                    </div>
                </form>
            </div>

            {{-- Hasil proses --}}
            @if (session('results'))
                @php $res = session('results'); @endphp
                <div class="bg-white shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Hasil Proses</h3>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Berhasil: {{ count($res['success'] ?? []) }}
                            </span>
                            <span class="px-2 py-0.5 rounded bg-red-50 text-red-700 border border-red-100">
                                Gagal: {{ count($res['failed'] ?? []) }}
                            </span>
                        </div>
                    </div>

                    @if (!empty($res['success']))
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-emerald-50 text-emerald-700">
                                    <tr>
                                        <th class="text-left py-2 px-3">File</th>
                                        <th class="text-left py-2 px-3">Vendor</th>
                                        <th class="text-left py-2 px-3">NPWP</th>
                                        <th class="text-left py-2 px-3">Periode</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach ($res['success'] as $row)
                                        <tr>
                                            <td class="py-2 px-3">{{ $row['name'] }}</td>
                                            <td class="py-2 px-3">{{ $row['vendor'] }}</td>
                                            <td class="py-2 px-3">{{ $row['npwp'] }}</td>
                                            <td class="py-2 px-3">{{ $row['period'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if (!empty($res['failed']))
                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-red-50 text-red-700">
                                    <tr>
                                        <th class="text-left py-2 px-3">File</th>
                                        <th class="text-left py-2 px-3">Alasan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach ($res['failed'] as $row)
                                        <tr>
                                            <td class="py-2 px-3">{{ $row['name'] }}</td>
                                            <td class="py-2 px-3">{{ $row['reason'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    {{-- === Script: drag&drop + preview + parser BLU/BPU === --}}
    <script>
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

            // Parser JS yang sejalan dengan server
            function parseName(filename) {
                const stem = filename.replace(/\.[^.]+$/,'').trim();

                // prefix BLU_ atau BPU_
                const m0 = stem.match(/^(?:BLU|BPU)[\s_\-]+(.+)$/i);
                if (!m0) return null;
                let tail = m0[1].trim();

                // NPWP tepat setelah prefix
                const m1 = tail.match(/^(\d{8,20})(.*)$/);
                if (!m1) return null;
                const npwp = (m1[1] || '').replace(/\D/g,'');
                const after = (m1[2] || '').trim();

                // Cari periode
                let period = null;

                // 1) YYYY-MM
                let ym = after.match(/\b((?:19|20)\d{2})-(0[1-9]|1[0-2])\b/);
                if (ym) period = ym[1] + '-' + ym[2];

                // 2) Pair MM YYYY atau YYYY MM
                if (!period) {
                    const tokens = after.split(/[\s_\-]+/).filter(Boolean);
                    for (let i=0;i<tokens.length-1 && !period;i++) {
                        const a=tokens[i], b=tokens[i+1];
                        const isMM  = /^(0[1-9]|1[0-2])$/.test(a);
                        const isYY  = /^(?:19|20)\d{2}$/.test(b);
                        const isYY2 = /^(?:19|20)\d{2}$/.test(a);
                        const isMM2 = /^(0[1-9]|1[0-2])$/.test(b);
                        if (isMM && isYY)        period = b + '-' + a;
                        else if (isYY2 && isMM2) period = a + '-' + b;
                    }
                }

                // 3) Substring 8 digit DDMMYYYY (termasuk di token panjang)
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

                // 4) Substring 6 digit YYYYMM atau MMYYYY
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

                // vendorName opsional (kasar: buang angka)
                const vendorName = after.replace(/\d+/g,' ').trim();
                return { npwp, period, vendorName };
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
                        <td class="py-2 px-3">${f.name}</td>
                        <td class="py-2 px-3 ${ok?'':'text-red-600'}">${ok?meta.npwp:'—'}</td>
                        <td class="py-2 px-3 ${ok?'':'text-red-600'}">${ok?meta.period:'Tidak terdeteksi'}</td>
                        <td class="py-2 px-3">${ok?meta.vendorName:'—'}</td>
                        <td class="py-2 px-3">${fmtSize(f.size)}</td>
                        <td class="py-2 px-3">
                            ${ok
                                ? '<span class="text-emerald-700 bg-emerald-50 border border-emerald-100 text-xs px-2 py-0.5 rounded">OK</span>'
                                : '<span class="text-red-700 bg-red-50 border border-red-100 text-xs px-2 py-0.5 rounded">Nama tidak sesuai</span>'}
                        </td>
                    `;
                    previewBody.appendChild(tr);
                });
                countEl.textContent = files.length;
                totalEl.textContent = fmtSize(total);
                stats.classList.toggle('hidden', files.length===0);
                previewWrap.classList.toggle('hidden', files.length===0);
                submitBtn.disabled = files.length===0;
            }

            // click picker
            picker.addEventListener('click', ()=> input.click());
            clearBtn.addEventListener('click', ()=>{
                input.value = '';
                renderPreview([]);
            });

            // change input
            input.addEventListener('change', ()=> renderPreview(input.files));

            // drag&drop
            ['dragenter','dragover'].forEach(ev => dz.addEventListener(ev, e=>{
                e.preventDefault(); e.stopPropagation();
                dz.classList.remove('border-gray-300','bg-gray-50');
                dz.classList.add('border-blue-500','bg-blue-50');
            }));
            ['dragleave','drop'].forEach(ev => dz.addEventListener(ev, e=>{
                e.preventDefault(); e.stopPropagation();
                dz.classList.remove('border-blue-500','bg-blue-50');
                dz.classList.add('border-gray-300','bg-gray-50');
            }));
            dz.addEventListener('drop', e=>{
                const dt = e.dataTransfer;
                if (!dt || !dt.files) return;
                input.files = dt.files;
                renderPreview(input.files);
            });
        })();
    </script>
</x-app-layout>
