@extends('layouts.app')

@section('title', 'Laporan Kinerja Harian & Rekapitulasi')
@section('page_title', 'Laporan Kinerja Harian')

@section('content')
<div class="space-y-6" x-data="{ currentTab: '{{ $tab }}' }">
    
    <!-- Top Bar: Switcher Tab Harian vs Rekap Bulanan -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-4 shadow-sm shadow-slate-100/50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-2 bg-slate-100 p-1.5 rounded-2xl w-full sm:w-auto">
            <button type="button" @click="currentTab = 'daily'"
                    class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-xs font-bold transition duration-200 flex items-center justify-center space-x-2"
                    :class="currentTab === 'daily' ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                <i class="fa-solid fa-calendar-day"></i>
                <span>Laporan Harian</span>
            </button>
            <button type="button" @click="currentTab = 'monthly'"
                    class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-xs font-bold transition duration-200 flex items-center justify-center space-x-2"
                    :class="currentTab === 'monthly' ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                <i class="fa-solid fa-calendar-week"></i>
                <span>Rekapitulasi Bulanan</span>
            </button>
        </div>

        <!-- Quick Info / Status indicator -->
        <div class="flex items-center space-x-3 text-xs text-slate-500">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-teal-50 text-teal-700 font-semibold border border-teal-100">
                <i class="fa-regular fa-clock mr-1.5 text-teal-500"></i>
                Hari ini: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Form Catat Kegiatan Baru (4.5 cols on lg) -->
        <div class="lg:col-span-5">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 sticky top-6">
                <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="h-10 w-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Catat Kinerja Harian</h3>
                        <p class="text-xs text-slate-500">Dokumentasikan aktivitas tugas pelayanan Anda</p>
                    </div>
                </div>

                <form action="{{ route('daily-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <!-- Tanggal Kegiatan -->
                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal" id="tanggal" required 
                               value="{{ old('tanggal', $selectedDate) }}"
                               class="w-full rounded-2xl border border-slate-200 py-2.5 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                    </div>

                    <!-- Waktu Mulai & Waktu Selesai -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="waktu_mulai" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Jam Mulai</label>
                            <input type="time" name="waktu_mulai" id="waktu_mulai" 
                                   value="{{ old('waktu_mulai', '08:00') }}"
                                   class="w-full rounded-2xl border border-slate-200 py-2.5 px-3 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                        </div>
                        <div>
                            <label for="waktu_selesai" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Jam Selesai</label>
                            <input type="time" name="waktu_selesai" id="waktu_selesai" 
                                   value="{{ old('waktu_selesai', '09:30') }}"
                                   class="w-full rounded-2xl border border-slate-200 py-2.5 px-3 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                        </div>
                    </div>

                    <!-- Kategori Kinerja -->
                    <div>
                        <label for="kategori" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kategori Bidang Tugas</label>
                        <select name="kategori" id="kategori" required
                                class="w-full rounded-2xl border border-slate-200 py-2.5 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Uraian Kegiatan -->
                    <div>
                        <label for="kegiatan" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Uraian Kegiatan Kinerja</label>
                        <textarea name="kegiatan" id="kegiatan" rows="3" required
                                  class="w-full rounded-2xl border border-slate-200 py-2.5 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                                  placeholder="Contoh: Melayani pendaftaran haji reguler, input berkas SPH ke aplikasi SISKOHAT...">{{ old('kegiatan') }}</textarea>
                    </div>

                    <!-- Output / Hasil Kerja & Volume -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                        <div class="sm:col-span-7">
                            <label for="output_hasil" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Hasil / Output Kerja</label>
                            <input type="text" name="output_hasil" id="output_hasil" 
                                   value="{{ old('output_hasil') }}"
                                   class="w-full rounded-2xl border border-slate-200 py-2.5 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                                   placeholder="Contoh: Berkas pendaftaran diverifikasi">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="volume" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Jml</label>
                            <input type="number" name="volume" id="volume" min="1" required 
                                   value="{{ old('volume', 1) }}"
                                   class="w-full rounded-2xl border border-slate-200 py-2.5 px-3 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition text-center">
                        </div>
                        <div class="sm:col-span-3">
                            <label for="satuan" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Satuan</label>
                            <input type="text" name="satuan" id="satuan" required 
                                   value="{{ old('satuan', 'Berkas') }}"
                                   class="w-full rounded-2xl border border-slate-200 py-2.5 px-3 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition">
                        </div>
                    </div>

                    <!-- Status Kinerja -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Status Penyelesaian</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="relative flex items-center justify-center p-2 rounded-xl border border-slate-200 cursor-pointer select-none text-xs font-semibold has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-500 has-[:checked]:text-emerald-700 transition">
                                <input type="radio" name="status" value="selesai" class="sr-only" {{ old('status', 'selesai') === 'selesai' ? 'checked' : '' }}>
                                <i class="fa-solid fa-circle-check mr-1.5 text-emerald-500 text-xs"></i> Selesai
                            </label>
                            <label class="relative flex items-center justify-center p-2 rounded-xl border border-slate-200 cursor-pointer select-none text-xs font-semibold has-[:checked]:bg-amber-50 has-[:checked]:border-amber-500 has-[:checked]:text-amber-700 transition">
                                <input type="radio" name="status" value="proses" class="sr-only" {{ old('status') === 'proses' ? 'checked' : '' }}>
                                <i class="fa-solid fa-spinner mr-1.5 text-amber-500 text-xs"></i> Proses
                            </label>
                            <label class="relative flex items-center justify-center p-2 rounded-xl border border-slate-200 cursor-pointer select-none text-xs font-semibold has-[:checked]:bg-rose-50 has-[:checked]:border-rose-500 has-[:checked]:text-rose-700 transition">
                                <input type="radio" name="status" value="tertunda" class="sr-only" {{ old('status') === 'tertunda' ? 'checked' : '' }}>
                                <i class="fa-solid fa-circle-pause mr-1.5 text-rose-500 text-xs"></i> Tertunda
                            </label>
                        </div>
                    </div>

                    <!-- Catatan / Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Catatan Tambahan (Opsional)</label>
                        <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}"
                               class="w-full rounded-2xl border border-slate-200 py-2.5 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                               placeholder="Keterangan tambahan / kendala jika ada...">
                    </div>

                    <!-- Upload Foto / Dokumen Dokumentasi -->
                    <div>
                        <label for="file_dokumentasi" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Bukti Dokumentasi (Foto/PDF)</label>
                        <input type="file" name="file_dokumentasi" id="file_dokumentasi" accept=".pdf,image/*,.doc,.docx"
                               class="block w-full text-xs text-slate-500 border border-slate-200 rounded-2xl file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer" />
                    </div>

                    <!-- Tombol Simpan -->
                    <button type="submit" 
                            class="w-full mt-2 py-3 px-4 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-bold text-sm hover:from-teal-600 hover:to-emerald-600 transition duration-300 shadow-md shadow-teal-500/20 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-plus-circle"></i>
                        <span>Simpan Kegiatan Kinerja</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Tab Content (7.5 cols on lg) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- ================= TAB 1: LAPORAN HARIAN ================= -->
            <div x-show="currentTab === 'daily'" class="space-y-6">
                
                <!-- Filter Tanggal Harian & Metrik -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-calendar-day text-teal-500"></i>
                                <span>Laporan Kinerja Tanggal:</span>
                                <span class="text-teal-600">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</span>
                            </h3>
                            <p class="text-xs text-slate-400">Pilih tanggal untuk melihat atau mencatat laporan kerja pada hari tersebut</p>
                        </div>

                        <!-- Date Picker Form -->
                        <form action="{{ route('daily-reports.index') }}" method="GET" class="flex items-center space-x-2">
                            <input type="hidden" name="tab" value="daily">
                            <input type="date" name="tanggal" value="{{ $selectedDate }}" 
                                   onchange="this.form.submit()"
                                   class="rounded-xl border border-slate-200 py-1.5 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700 font-semibold cursor-pointer">
                            
                            @if($selectedDate !== date('Y-m-d'))
                            <a href="{{ route('daily-reports.index', ['tab' => 'daily', 'tanggal' => date('Y-m-d')]) }}" 
                               class="px-2.5 py-1.5 rounded-xl bg-teal-50 text-teal-600 hover:bg-teal-100 text-xs font-semibold transition" title="Kembali ke Hari Ini">
                                Hari Ini
                            </a>
                            @endif
                        </form>
                    </div>

                    <!-- Mini Stat Badges Hari Ini -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100 flex items-center space-x-3">
                            <div class="h-9 w-9 rounded-xl bg-teal-100/70 text-teal-600 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total</span>
                                <div class="text-base font-extrabold text-slate-800">{{ $statsToday['total'] }} Kegiatan</div>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100 flex items-center space-x-3">
                            <div class="h-9 w-9 rounded-xl bg-emerald-100/70 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Selesai</span>
                                <div class="text-base font-extrabold text-emerald-600">{{ $statsToday['selesai'] }}</div>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-3 border border-slate-100 flex items-center space-x-3">
                            <div class="h-9 w-9 rounded-xl bg-amber-100/70 text-amber-600 flex items-center justify-center font-bold text-sm">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Proses/Tertunda</span>
                                <div class="text-base font-extrabold text-amber-600">{{ $statsToday['proses'] + $statsToday['tertunda'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Daftar Kegiatan Hari Tersebut -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm shadow-slate-100/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h4 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                            <i class="fa-solid fa-timeline text-teal-500"></i>
                            <span>Aktivitas Kinerja Terdata</span>
                        </h4>
                        <span class="text-xs text-slate-400 font-medium">{{ count($dailyReports) }} item terdata</span>
                    </div>

                    @if(count($dailyReports) > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($dailyReports as $report)
                        <div class="p-5 hover:bg-slate-50/60 transition group">
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-1.5 flex-1">
                                    <!-- Time & Category Badges -->
                                    <div class="flex items-center flex-wrap gap-2">
                                        @if($report->waktu_mulai)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-bold">
                                            <i class="fa-regular fa-clock mr-1 text-slate-400 text-[10px]"></i>
                                            {{ $report->waktu_mulai }}{{ $report->waktu_selesai ? ' - ' . $report->waktu_selesai : '' }}
                                        </span>
                                        @endif

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-teal-50 text-teal-700 text-[11px] font-semibold border border-teal-100">
                                            {{ $report->kategori }}
                                        </span>

                                        <!-- Status Badge -->
                                        @if($report->status === 'selesai')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold border border-emerald-100">
                                            <i class="fa-solid fa-check mr-1"></i> Selesai
                                        </span>
                                        @elseif($report->status === 'proses')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-50 text-amber-600 text-[10px] font-bold border border-amber-100">
                                            <i class="fa-solid fa-spinner mr-1"></i> Proses
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 text-rose-600 text-[10px] font-bold border border-rose-100">
                                            <i class="fa-solid fa-circle-pause mr-1"></i> Tertunda
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Uraian Kegiatan -->
                                    <h5 class="text-sm font-bold text-slate-800 leading-snug pt-1">
                                        {{ $report->kegiatan }}
                                    </h5>

                                    <!-- Output & Satuan -->
                                    @if($report->output_hasil)
                                    <div class="text-xs text-slate-500 flex items-center space-x-1.5">
                                        <i class="fa-solid fa-arrow-right text-[10px] text-teal-500"></i>
                                        <span>Output: <strong>{{ $report->volume }} {{ $report->satuan }}</strong> ({{ $report->output_hasil }})</span>
                                    </div>
                                    @endif

                                    <!-- Keterangan Catatan -->
                                    @if($report->keterangan)
                                    <p class="text-xs text-slate-400 italic bg-slate-50 p-2 rounded-xl border border-slate-100">
                                        "{{ $report->keterangan }}"
                                    </p>
                                    @endif

                                    <!-- File Lampiran -->
                                    @if($report->file_dokumentasi)
                                    <div class="pt-1">
                                        <a href="{{ asset('storage/' . $report->file_dokumentasi) }}" target="_blank" 
                                           class="inline-flex items-center space-x-1.5 text-xs text-teal-600 hover:text-teal-700 font-semibold bg-teal-50/80 hover:bg-teal-100 px-3 py-1 rounded-xl transition">
                                            <i class="fa-solid fa-paperclip"></i>
                                            <span>Lihat Bukti Dokumentasi</span>
                                        </a>
                                    </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-1 shrink-0">
                                    <form action="{{ route('daily-reports.destroy', $report) }}" method="POST" 
                                          onsubmit="return confirm('Hapus catatan kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus Kegiatan">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="p-12 text-center space-y-3">
                        <div class="h-16 w-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl">
                            <i class="fa-regular fa-folder-open"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-700">Belum Ada Kegiatan pada Tanggal Ini</h4>
                        <p class="text-xs text-slate-400 max-w-sm mx-auto">
                            Silakan masukkan catatan tugas kinerja harian pada form di sebelah kiri untuk mengisi agenda hari ini.
                        </p>
                    </div>
                    @endif
                </div>
            </div>


            <!-- ================= TAB 2: REKAPITULASI BULANAN ================= -->
            <div x-show="currentTab === 'monthly'" class="space-y-6">
                
                <!-- Filter Bulan & Aksi Rekap -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 space-y-5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-calendar-check text-teal-500"></i>
                                <span>Rekapitulasi Kinerja Bulanan</span>
                            </h3>
                            <p class="text-xs text-slate-400">Ringkasan seluruh kegiatan untuk keperluan pelaporan resmi</p>
                        </div>

                        <!-- Tombol Cetak & Ekspor -->
                        <div class="flex items-center space-x-2.5">
                            <!-- Cetak Resmi / PDF -->
                            <a href="{{ route('daily-reports.print-monthly', ['bulan' => $selectedMonth, 'tahun' => $selectedYear]) }}" target="_blank"
                               class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition duration-200 shadow-sm flex items-center space-x-2">
                                <i class="fa-solid fa-print"></i>
                                <span>Cetak Rekap (PDF)</span>
                            </a>

                            <!-- Download Excel/CSV -->
                            <a href="{{ route('daily-reports.export-monthly', ['bulan' => $selectedMonth, 'tahun' => $selectedYear]) }}"
                               class="px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-bold text-xs transition duration-200 shadow-sm flex items-center space-x-2">
                                <i class="fa-solid fa-file-excel"></i>
                                <span>Unduh CSV/Excel</span>
                            </a>
                        </div>
                    </div>

                    <!-- Selector Bulan & Tahun -->
                    <form action="{{ route('daily-reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 border-t border-slate-100">
                        <input type="hidden" name="tab" value="monthly">
                        
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Pilih Bulan</label>
                            <select name="bulan" onchange="this.form.submit()"
                                    class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs font-semibold focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700">
                                @php
                                    $bulanNames = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                @endphp
                                @foreach($bulanNames as $num => $nama)
                                    <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Pilih Tahun</label>
                            <select name="tahun" onchange="this.form.submit()"
                                    class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs font-semibold focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700">
                                @for($y = date('Y'); $y >= 2024; $y--)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Kategori Filter</label>
                            <select name="kategori" onchange="this.form.submit()"
                                    class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs font-semibold focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Monthly Metric Overview Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Kegiatan</span>
                        <div class="text-2xl font-black text-slate-800 mt-1">{{ $monthlyStats['total'] }}</div>
                        <span class="text-[10px] text-teal-600 font-medium">Bulan {{ $bulanNames[$selectedMonth] }}</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Output</span>
                        <div class="text-2xl font-black text-slate-800 mt-1">{{ $monthlyStats['total_volume'] }}</div>
                        <span class="text-[10px] text-slate-400 font-medium">Satuan capaian</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Hari Aktif</span>
                        <div class="text-2xl font-black text-slate-800 mt-1">{{ $monthlyStats['hari_aktif'] }}</div>
                        <span class="text-[10px] text-slate-400 font-medium">Hari tercatat</span>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Tingkat Selesai</span>
                        @php
                            $percentage = $monthlyStats['total'] > 0 
                                ? round(($monthlyStats['selesai'] / $monthlyStats['total']) * 100) 
                                : 0;
                        @endphp
                        <div class="text-2xl font-black text-emerald-600 mt-1">{{ $percentage }}%</div>
                        <span class="text-[10px] text-emerald-600 font-medium">{{ $monthlyStats['selesai'] }} dari {{ $monthlyStats['total'] }} selesai</span>
                    </div>
                </div>

                <!-- Rekap Kategori Pills -->
                @if(count($kategoriSummary) > 0)
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                    <span class="text-[11px] font-bold text-slate-600 block mb-2">Sebaran Bidang Tugas:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($kategoriSummary as $katName => $count)
                        <span class="inline-flex items-center px-3 py-1 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700">
                            <span>{{ $katName }}</span>
                            <span class="ml-1.5 px-1.5 py-0.2 rounded-md bg-teal-500 text-white text-[10px] font-bold">{{ $count }}</span>
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Tabel Rekap Bulanan -->
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm shadow-slate-100/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h4 class="text-sm font-bold text-slate-800">
                            Daftar Lengkap Kinerja Bulan {{ $bulanNames[$selectedMonth] }} {{ $selectedYear }}
                        </h4>
                        <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-full font-bold">
                            Total: {{ $monthlyReports->total() }} Data
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 text-[10px] font-semibold uppercase tracking-wider">
                                    <th class="py-3 px-4">Tanggal & Waktu</th>
                                    <th class="py-3 px-4">Uraian Tugas Kinerja</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4">Output / Capaian</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @forelse($monthlyReports as $r)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-800">{{ $r->tanggal->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-slate-400">
                                            {{ $r->waktu_mulai ? $r->waktu_mulai . ($r->waktu_selesai ? ' - ' . $r->waktu_selesai : '') : '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-[280px]">
                                        <p class="font-semibold text-slate-800 leading-snug line-clamp-2" title="{{ $r->kegiatan }}">{{ $r->kegiatan }}</p>
                                        @if($r->keterangan)
                                        <span class="text-[10px] text-slate-400 italic block pt-0.5">Catatan: {{ $r->keterangan }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-semibold">
                                            {{ $r->kategori }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="font-bold text-slate-700">{{ $r->volume }} {{ $r->satuan }}</span>
                                        @if($r->output_hasil)
                                        <span class="text-[10px] text-slate-400 block">{{ $r->output_hasil }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        @if($r->status === 'selesai')
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold">Selesai</span>
                                        @elseif($r->status === 'proses')
                                        <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-600 text-[10px] font-bold">Proses</span>
                                        @else
                                        <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-600 text-[10px] font-bold">Tertunda</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-1">
                                            @if($r->file_dokumentasi)
                                            <a href="{{ asset('storage/' . $r->file_dokumentasi) }}" target="_blank" 
                                               class="p-1.5 text-teal-600 hover:bg-teal-50 rounded-lg transition" title="Lihat Lampiran">
                                                <i class="fa-solid fa-paperclip text-xs"></i>
                                            </a>
                                            @endif
                                            <form action="{{ route('daily-reports.destroy', $r) }}" method="POST" onsubmit="return confirm('Hapus data kinerja ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-slate-400 text-xs">
                                        Belum ada data kegiatan pada bulan ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($monthlyReports->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $monthlyReports->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
