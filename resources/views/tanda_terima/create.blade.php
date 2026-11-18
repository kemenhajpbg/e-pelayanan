@extends('layouts.app')

@section('title', 'Buat Tanda Terima Berkas')
@section('page_title', 'Buat Tanda Terima Berkas')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation & Info Banner -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm shadow-slate-100/50">
        <div class="flex items-center space-x-3">
            <div class="h-12 w-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-sm">
                <i class="fa-solid fa-file-signature text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Formulir Penerimaan Berkas</h3>
                <p class="text-xs text-slate-500">Kementerian Haji dan Umrah Kabupaten Purbalingga</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 w-full sm:w-auto justify-end">
            <a href="{{ route('tanda-terima.index') }}" 
               class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Ke Riwayat</span>
            </a>
        </div>
    </div>

    <!-- Alert Otomatisasi Waktu & Nomor -->
    <div class="bg-gradient-to-r from-teal-50 to-emerald-50 border border-teal-200/80 rounded-2xl p-4 text-xs text-teal-900 flex items-start space-x-3">
        <i class="fa-solid fa-shield-halved text-teal-600 text-base mt-0.5"></i>
        <div class="space-y-1">
            <p class="font-bold">Pencatatan Otomatis & Terverifikasi (WIB)</p>
            <p class="text-slate-600 leading-relaxed">
                Nomor Tanda Terima (<strong class="text-teal-800">TTB-YYYYMMDD-XXXX</strong>) serta tanggal & waktu setor direkam secara <span class="font-bold text-teal-700">otomatis oleh server</span> dengan zona waktu <strong class="text-slate-800">Asia/Jakarta (WIB)</strong> pada saat tombol <strong>Simpan & Cetak</strong> ditekan.
            </p>
        </div>
    </div>

    <!-- Form Utama -->
    <form id="formTandaTerima" action="{{ route('tanda-terima.store') }}" method="POST" class="space-y-6">
        @csrf

        <input type="hidden" name="tanda_tangan" id="tanda_tangan_input">

        <!-- 1. DATA PENYETOR -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 md:p-8 shadow-sm shadow-slate-100/50 space-y-6">
            <div class="flex items-center space-x-3 border-b border-slate-100 pb-4">
                <div class="h-8 w-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs">
                    1
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Data Penyetor Berkas</h4>
                    <p class="text-xs text-slate-400">Identitas jemaah / masyarakat yang menyerahkan berkas</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Penyetor -->
                <div>
                    <label for="nama_penyetor" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Lengkap Penyetor <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" name="nama_penyetor" id="nama_penyetor" required
                               value="{{ old('nama_penyetor') }}"
                               placeholder="Contoh: Budi Santoso"
                               class="w-full rounded-2xl border border-slate-200 py-3.5 pl-11 pr-4 text-sm font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition bg-slate-50/50 focus:bg-white">
                    </div>
                </div>

                <!-- Nomor HP -->
                <div>
                    <label for="no_hp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nomor HP / WhatsApp <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="tel" name="no_hp" id="no_hp" required
                               value="{{ old('no_hp') }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full rounded-2xl border border-slate-200 py-3.5 pl-11 pr-4 text-sm font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition bg-slate-50/50 focus:bg-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. PILIHAN JENIS BERKAS -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 md:p-8 shadow-sm shadow-slate-100/50 space-y-6">
            <div class="flex items-center space-x-3 border-b border-slate-100 pb-4">
                <div class="h-8 w-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs">
                    2
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Jenis Berkas Yang Disetorkan <span class="text-rose-500">*</span></h4>
                    <p class="text-xs text-slate-400">Petugas dapat memilih satu atau lebih jenis berkas</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                @php
                    $oldBerkas = old('jenis_berkas', []);
                    $iconMap = [
                        'Berkas Pelimpahan' => 'fa-arrows-split-up-and-left',
                        'Berkas Pembatalan' => 'fa-file-circle-xmark',
                        'Paspor' => 'fa-passport',
                        'Berkas Perbaikan Data Jemaah' => 'fa-user-pen',
                        'Berkas Pengajuan Penggabungan/Pendampingan' => 'fa-people-arrows',
                        'Berkas Pengajuan Mutasi' => 'fa-right-left',
                        'Berkas Keberangkatan Haji' => 'fa-kaaba',
                    ];
                @endphp

                @foreach($jenisBerkasList as $jenis)
                <label class="relative flex items-center p-4 rounded-2xl border cursor-pointer select-none transition-all duration-200 hover:border-teal-400 hover:bg-teal-50/20 group has-[:checked]:border-teal-600 has-[:checked]:bg-teal-50/50 has-[:checked]:ring-2 has-[:checked]:ring-teal-500/20 border-slate-200">
                    <input type="checkbox" name="jenis_berkas[]" value="{{ $jenis }}"
                           {{ in_array($jenis, $oldBerkas) ? 'checked' : '' }}
                           class="w-5 h-5 rounded-lg border-slate-300 text-teal-600 focus:ring-teal-500 focus:ring-offset-0 transition cursor-pointer">
                    <div class="ml-3.5 flex items-center space-x-2.5">
                        <i class="fa-solid {{ $iconMap[$jenis] ?? 'fa-file-lines' }} text-slate-400 group-hover:text-teal-600 text-sm w-4 text-center"></i>
                        <span class="text-sm font-semibold text-slate-800 group-hover:text-slate-900">{{ $jenis }}</span>
                    </div>
                </label>
                @endforeach
            </div>

            <!-- Keterangan Tambahan -->
            <div class="pt-3 border-t border-slate-100">
                <label for="keterangan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Keterangan / Catatan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <textarea name="keterangan" id="keterangan" rows="3"
                          placeholder="Tuliskan catatan kelengkapan dokumen atau nomor porsi jika diperlukan..."
                          class="w-full rounded-2xl border border-slate-200 p-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition bg-slate-50/50 focus:bg-white">{{ old('keterangan') }}</textarea>
            </div>
        </div>

        <!-- 3. TANDA TANGAN DIGITAL (TABLET READY) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 md:p-8 shadow-sm shadow-slate-100/50 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs">
                        3
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Tanda Tangan Digital Penyetor <span class="text-rose-500">*</span></h4>
                        <p class="text-xs text-slate-400">Penyetor menandatangani langsung pada layar tablet menggunakan jari atau stylus</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span id="signatureStatus" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500">
                        <i class="fa-solid fa-circle text-[8px] mr-1.5 text-slate-400"></i> Belum ditandatangani
                    </span>
                </div>
            </div>

            <!-- Canvas Container -->
            <div class="space-y-3">
                <div class="relative bg-slate-50 rounded-2xl border-2 border-dashed border-slate-300 p-2 overflow-hidden flex justify-center items-center">
                    <canvas id="signatureCanvas" 
                            class="bg-white rounded-xl shadow-inner w-full touch-none cursor-crosshair" 
                            height="240" 
                            style="max-width: 100%; display: block;"></canvas>

                    <!-- Watermark Guide Helper -->
                    <div id="signatureWatermark" class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-slate-300 select-none">
                        <i class="fa-solid fa-pen-nib text-3xl mb-1 text-slate-300/80"></i>
                        <span class="text-xs font-semibold tracking-wider uppercase text-slate-400">Tanda Tangan di Sini</span>
                        <span class="text-[10px] text-slate-400/80">(Gunakan jari atau stylus pada layar tablet)</span>
                    </div>
                </div>

                <!-- Tombol Hapus Tanda Tangan -->
                <div class="flex items-center justify-between pt-1">
                    <p class="text-[11px] text-slate-400 italic">
                        <i class="fa-solid fa-circle-info mr-1"></i> Goresan tanda tangan akan dicetak pada lembar thermal printer dan disimpan permanen.
                    </p>
                    <button type="button" id="btnClearSignature"
                            class="px-4 py-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 font-bold text-xs transition duration-200 flex items-center space-x-2 shadow-sm">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Hapus Tanda Tangan</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 4. RINGKASAN PETUGAS & TOMBOL SIMPAN & CETAK -->
        <div class="bg-slate-900 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-slate-900/10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-1.5 text-center md:text-left w-full md:w-auto">
                <div class="flex items-center justify-center md:justify-start space-x-2 text-teal-400 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Petugas Yang Melayani</span>
                </div>
                <div class="text-base font-bold text-white">{{ $petugasName }}</div>
                <p class="text-xs text-slate-400">
                    Sistem otomatis menerbitkan Nomor TTB & mencatat waktu setor saat Anda menekan tombol di samping.
                </p>
            </div>

            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                <a href="{{ route('tanda-terima.index') }}" 
                   class="px-6 py-4 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-sm text-center transition">
                    Batal
                </a>
                <button type="submit" id="btnSubmitForm"
                        class="px-8 py-4 rounded-2xl bg-teal-400 text-slate-900 hover:bg-teal-300 font-extrabold text-sm transition-all duration-200 shadow-lg shadow-teal-400/25 flex items-center justify-center space-x-3 group active:scale-95">
                    <i class="fa-solid fa-print text-base group-hover:scale-110 transition-transform"></i>
                    <span>SIMPAN & CETAK</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    const clearBtn = document.getElementById('btnClearSignature');
    const form = document.getElementById('formTandaTerima');
    const signatureInput = document.getElementById('tanda_tangan_input');
    const watermark = document.getElementById('signatureWatermark');
    const statusBadge = document.getElementById('signatureStatus');

    let isDrawing = false;
    let hasDrawn = false;
    let lastX = 0;
    let lastY = 0;

    // Set canvas dimensions based on CSS display width for high-DPI tablets
    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        const dpr = window.devicePixelRatio || 1;
        
        // Simpan gambar jika sudah digambar
        let savedData = null;
        if (hasDrawn) {
            savedData = canvas.toDataURL();
        }

        canvas.width = rect.width * dpr;
        canvas.height = 240 * dpr;
        ctx.scale(dpr, dpr);

        ctx.strokeStyle = '#0f172a'; // Slate-900 solid ink
        ctx.lineWidth = 2.8;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        if (savedData) {
            const img = new Image();
            img.onload = () => {
                ctx.drawImage(img, 0, 0, rect.width, 240);
            };
            img.src = savedData;
        }
    }

    resizeCanvas();
    window.addEventListener('resize', () => {
        // Debounce resize
        clearTimeout(window.resizeTimer);
        window.resizeTimer = setTimeout(resizeCanvas, 200);
    });

    function getCoords(e) {
        const rect = canvas.getBoundingClientRect();
        let clientX = e.clientX;
        let clientY = e.clientY;

        if (e.touches && e.touches.length > 0) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        }

        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function startDrawing(e) {
        e.preventDefault();
        isDrawing = true;
        const coords = getCoords(e);
        lastX = coords.x;
        lastY = coords.y;

        if (!hasDrawn) {
            hasDrawn = true;
            watermark.classList.add('hidden');
            statusBadge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200';
            statusBadge.innerHTML = '<i class="fa-solid fa-check text-[10px] mr-1.5 text-emerald-500"></i> Siap Disimpan';
        }

        // Gambar titik jika hanya sentuhan tunggal
        ctx.beginPath();
        ctx.arc(lastX, lastY, 1.4, 0, Math.PI * 2);
        ctx.fillStyle = '#0f172a';
        ctx.fill();
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        const coords = getCoords(e);

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(coords.x, coords.y);
        ctx.stroke();

        lastX = coords.x;
        lastY = coords.y;
    }

    function stopDrawing(e) {
        if (isDrawing) {
            isDrawing = false;
        }
    }

    // Touch & Pointer Event Listeners for smooth tablet/stylus response
    canvas.addEventListener('pointerdown', startDrawing);
    canvas.addEventListener('pointermove', draw);
    canvas.addEventListener('pointerup', stopDrawing);
    canvas.addEventListener('pointercancel', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', stopDrawing, { passive: false });

    // Hapus tanda tangan
    clearBtn.addEventListener('click', function () {
        const rect = canvas.getBoundingClientRect();
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasDrawn = false;
        signatureInput.value = '';
        watermark.classList.remove('hidden');
        statusBadge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-500';
        statusBadge.innerHTML = '<i class="fa-solid fa-circle text-[8px] mr-1.5 text-slate-400"></i> Belum ditandatangani';
    });

    // Form Submission Handler
    form.addEventListener('submit', function (e) {
        // Validasi Minimal 1 Checkbox Berkas
        const checkboxes = document.querySelectorAll('input[name="jenis_berkas[]"]:checked');
        if (checkboxes.length === 0) {
            e.preventDefault();
            alert('Mohon pilih minimal 1 (satu) jenis berkas yang disetorkan.');
            return false;
        }

        // Validasi Tanda Tangan
        if (!hasDrawn) {
            e.preventDefault();
            alert('Penyetor berkas wajib membubuhkan tanda tangan digital pada area tanda tangan sebelum disimpan.');
            canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        // Export canvas to base64 PNG
        const signatureData = canvas.toDataURL('image/png');
        signatureInput.value = signatureData;
    });
});
</script>
@endsection
