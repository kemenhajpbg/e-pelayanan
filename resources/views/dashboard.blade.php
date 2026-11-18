@extends('layouts.app')

@section('title', 'Dashboard Statistik')
@section('page_title', 'Dashboard Awal')

@section('content')
<!-- Banner Ekspor Excel & Email -->
<div class="bg-gradient-to-r from-slate-900 to-teal-950 rounded-3xl p-6 shadow-md text-white border border-teal-500/20 flex flex-col md:flex-row items-center justify-between">
    <div class="space-y-1.5 mb-4 md:mb-0">
        <h3 class="text-base font-bold flex items-center space-x-2 text-white">
            <i class="fa-solid fa-file-excel text-teal-400 text-lg"></i>
            <span>Ekspor Laporan Excel / Spreadsheet</span>
        </h3>
        <p class="text-xs text-slate-300">
            Kirimkan seluruh berkas rekapitulasi data buku tamu, berita tayang, serta laporan kinerja harian dalam format Excel ke email <span class="text-teal-400 font-bold">izzulhaq014@gmail.com</span>.
        </p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto mt-4 md:mt-0">
        @if(env('GOOGLE_SHEET_WEBHOOK_URL'))
        <a href="https://docs.google.com/spreadsheets/d/1Mv_-ofOAwLDU_xD8Tvv_ySSoY_k-V1O5MEZu-gG6O-c/edit?usp=sharing" target="_blank"
           class="px-5 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 text-teal-400 border border-teal-500/20 font-bold text-xs transition duration-200 flex items-center justify-center space-x-2">
            <i class="fa-solid fa-file-excel"></i>
            <span>Buka Google Spreadsheet</span>
        </a>
        @endif
        <form action="{{ route('export.email') }}" method="POST" class="w-full sm:w-auto">
            @csrf
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-teal-400 text-slate-900 font-bold text-xs hover:bg-teal-300 transition duration-200 shadow-lg shadow-teal-400/20 flex items-center justify-center space-x-2 group">
                <i class="fa-solid fa-paper-plane group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                <span>Kirim Laporan ke Email</span>
            </button>
        </form>
    </div>
</div>

<!-- Metric Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
    
    <!-- Total Pengunjung -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 flex items-center justify-between group hover:border-teal-500/30 transition-all duration-300 transform hover:-translate-y-1">
        <div class="space-y-2">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Tamu</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $totalVisitors }}</div>
            <p class="text-[10px] text-slate-500 font-medium">Buku tamu pelayanan</p>
        </div>
        <div class="h-12 w-12 rounded-2xl bg-teal-50 text-teal-500 flex items-center justify-center transition-colors group-hover:bg-teal-500 group-hover:text-white">
            <i class="fa-solid fa-users text-lg"></i>
        </div>
    </div>

    <!-- Berita Tayang -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 flex items-center justify-between group hover:border-sky-500/30 transition-all duration-300 transform hover:-translate-y-1">
        <div class="space-y-2">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Berita Tayang</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $totalNews }}</div>
            <p class="text-[10px] text-slate-500 font-medium">Berita dipublikasikan</p>
        </div>
        <div class="h-12 w-12 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center transition-colors group-hover:bg-sky-500 group-hover:text-white">
            <i class="fa-solid fa-newspaper text-lg"></i>
        </div>
    </div>

    <!-- Kinerja Hari Ini -->
    <a href="{{ route('daily-reports.index', ['tab' => 'daily']) }}" 
       class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 flex items-center justify-between group hover:border-emerald-500/30 transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="space-y-2">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kinerja Hari Ini</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $totalReportsToday }}</div>
            <p class="text-[10px] text-emerald-600 font-semibold">Lihat tugas hari ini &rarr;</p>
        </div>
        <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center transition-colors group-hover:bg-emerald-50 group-hover:text-white">
            <i class="fa-solid fa-clipboard-check text-lg"></i>
        </div>
    </a>

    <!-- Kinerja Bulan Ini -->
    <a href="{{ route('daily-reports.index', ['tab' => 'monthly']) }}" 
       class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 flex items-center justify-between group hover:border-blue-500/30 transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="space-y-2">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kinerja Bulan Ini</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $totalReportsThisMonth }}</div>
            <p class="text-[10px] text-blue-600 font-semibold">Rekapitulasi bulanan &rarr;</p>
        </div>
        <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center transition-colors group-hover:bg-blue-500 group-hover:text-white">
            <i class="fa-solid fa-calendar-week text-lg"></i>
        </div>
    </a>

    <!-- Rata-rata Kepuasan -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 flex items-center justify-between group hover:border-amber-500/30 transition-all duration-300 transform hover:-translate-y-1">
        <div class="space-y-2">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kepuasan</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight flex items-baseline">
                <span>{{ $avgSatisfaction }}</span>
                <span class="text-xs font-bold text-slate-400 ml-1">/5</span>
            </div>
            <div class="flex text-[8px] text-amber-400">
                @for($i=1; $i<=5; $i++)
                    <i class="{{ $i <= $avgSatisfaction ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                @endfor
            </div>
        </div>
        <div class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center transition-colors group-hover:bg-amber-50 group-hover:text-white">
            <i class="fa-solid fa-face-smile text-lg"></i>
        </div>
    </div>

</div>

<!-- SECTION MODUL: TANDA TERIMA BERKAS (SECTION 12) -->
<div class="bg-white rounded-3xl border border-slate-200/80 p-6 md:p-8 shadow-sm shadow-slate-100/50 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
        <div class="flex items-center space-x-3.5">
            <div class="h-12 w-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-sm">
                <i class="fa-solid fa-file-signature text-xl"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">Tanda Terima Berkas Pelayanan</h3>
                <p class="text-xs text-slate-400">Monitoring tanda terima berkas jemaah haji & umrah secara terpadu</p>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('tanda-terima.index') }}" 
               class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition flex items-center space-x-1.5">
                <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                <span>Riwayat Transaksi</span>
            </a>
            <a href="{{ route('tanda-terima.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-900 font-extrabold text-xs transition shadow-md shadow-teal-500/20 flex items-center space-x-1.5">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Buat Tanda Terima</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan Kartu Metrik TTB -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Tanda Terima Hari Ini -->
        <a href="{{ route('tanda-terima.index', ['tanggal' => date('Y-m-d')]) }}" 
           class="p-5 rounded-2xl bg-gradient-to-br from-teal-50/80 to-white border border-teal-200/80 hover:border-teal-400 transition-all group flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Tanda Terima Hari Ini</span>
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalTtbToday }}</div>
                <p class="text-[11px] text-teal-600 font-medium">Buku penerimaan hari ini &rarr;</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-teal-500 text-slate-900 flex items-center justify-center font-bold text-lg shadow-sm group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </a>

        <!-- Tanda Terima Bulan Ini -->
        <a href="{{ route('tanda-terima.laporan', ['periode' => 'bulanan', 'bulan' => date('m'), 'tahun' => date('Y')]) }}" 
           class="p-5 rounded-2xl bg-gradient-to-br from-sky-50/80 to-white border border-sky-200/80 hover:border-sky-400 transition-all group flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-sky-700 uppercase tracking-wider">Tanda Terima Bulan Ini</span>
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalTtbThisMonth }}</div>
                <p class="text-[11px] text-sky-600 font-medium">Rekapitulasi bulanan &rarr;</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-sky-500 text-white flex items-center justify-center font-bold text-lg shadow-sm group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </a>

        <!-- Total Tanda Terima -->
        <a href="{{ route('tanda-terima.index') }}" 
           class="p-5 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-200 hover:border-slate-400 transition-all group flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Total Tanda Terima</span>
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalTtb }}</div>
                <p class="text-[11px] text-slate-500 font-medium">Seluruh arsip penerimaan &rarr;</p>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-slate-900 text-teal-400 flex items-center justify-center font-bold text-lg shadow-sm group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </a>
    </div>

    <!-- Statistik Berdasarkan 7 Kategori Berkas -->
    <div class="pt-2">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Statistik Berdasarkan Jenis Berkas</span>
            <a href="{{ route('tanda-terima.laporan') }}" class="text-xs font-semibold text-teal-600 hover:underline">
                Lihat Laporan Lengkap &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3">
            @php
                $berkasIcons = [
                    'Berkas Pelimpahan' => ['icon' => 'fa-arrows-split-up-and-left', 'short' => 'Pelimpahan'],
                    'Berkas Pembatalan' => ['icon' => 'fa-file-circle-xmark', 'short' => 'Pembatalan'],
                    'Paspor' => ['icon' => 'fa-passport', 'short' => 'Paspor'],
                    'Berkas Perbaikan Data Jemaah' => ['icon' => 'fa-user-pen', 'short' => 'Perbaikan Data'],
                    'Berkas Pengajuan Penggabungan/Pendampingan' => ['icon' => 'fa-people-arrows', 'short' => 'Penggabungan'],
                    'Berkas Pengajuan Mutasi' => ['icon' => 'fa-right-left', 'short' => 'Mutasi'],
                    'Berkas Keberangkatan Haji' => ['icon' => 'fa-kaaba', 'short' => 'Keberangkatan'],
                ];
            @endphp

            @foreach(\App\Models\TandaTerimaBerkas::JENIS_BERKAS_LIST as $jb)
            @php
                $itemConf = $berkasIcons[$jb] ?? ['icon' => 'fa-folder', 'short' => $jb];
                $count = $ttbJenisStats[$jb] ?? 0;
            @endphp
            <a href="{{ route('tanda-terima.index', ['jenis_berkas' => $jb]) }}"
               class="p-3.5 rounded-2xl bg-slate-50 hover:bg-teal-50/50 border border-slate-100 hover:border-teal-200 transition-all text-center group block">
                <div class="h-8 w-8 mx-auto rounded-xl bg-white text-slate-600 group-hover:text-teal-600 flex items-center justify-center text-xs shadow-sm mb-2">
                    <i class="fa-solid {{ $itemConf['icon'] }}"></i>
                </div>
                <div class="text-base font-extrabold text-slate-900">{{ $count }}</div>
                <div class="text-[10px] font-semibold text-slate-500 group-hover:text-slate-800 line-clamp-1 mt-0.5" title="{{ $jb }}">
                    {{ $itemConf['short'] }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Tren Kunjungan Tamu (Line Chart) - 7 cols on lg -->
    <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Tren Kunjungan Harian</h3>
                <p class="text-[10px] text-slate-400">Perkembangan jumlah pengunjung 7 hari terakhir</p>
            </div>
            <div class="bg-teal-50 text-teal-600 text-[10px] font-bold px-2 py-1 rounded-lg">
                <i class="fa-solid fa-chart-line mr-1"></i> Tren Harian
            </div>
        </div>
        <div class="h-80 w-full">
            <canvas id="lineChartTren"></canvas>
        </div>
    </div>

    <!-- Distribusi Keperluan Pelayanan (Doughnut Chart) - 5 cols on lg -->
    <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Distribusi Keperluan</h3>
                <p class="text-[10px] text-slate-400">Kategori urusan kedatangan jemaah</p>
            </div>
            <div class="bg-teal-50 text-teal-600 text-[10px] font-bold px-2 py-1 rounded-lg">
                <i class="fa-solid fa-chart-pie mr-1"></i> Keperluan
            </div>
        </div>
        <div class="h-80 w-full flex items-center justify-center">
            <canvas id="doughnutChartKeperluan"></canvas>
        </div>
    </div>

</div>

<!-- Additional Analytics Grid (Kelompok Usia & Kepuasan) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Kelompok Usia (Bar Chart) - 6 cols on lg -->
    <div class="lg:col-span-6 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Demografi Kelompok Usia</h3>
                <p class="text-[10px] text-slate-400">Sebaran usia jemaah & pengunjung</p>
            </div>
            <div class="bg-sky-50 text-sky-600 text-[10px] font-bold px-2 py-1 rounded-lg">
                <i class="fa-solid fa-users mr-1"></i> Usia Jemaah
            </div>
        </div>
        <div class="h-72 w-full">
            <canvas id="barChartUsia"></canvas>
        </div>
    </div>

    <!-- Tingkat Kepuasan (Bar Chart) - 6 cols on lg -->
    <div class="lg:col-span-6 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Evaluasi Indeks Kepuasan (IKM)</h3>
                <p class="text-[10px] text-slate-400">Rating kepuasan pelayanan masyarakat (Bintang 1-5)</p>
            </div>
            <div class="bg-amber-50 text-amber-600 text-[10px] font-bold px-2 py-1 rounded-lg">
                <i class="fa-solid fa-star mr-1"></i> Rating IKM
            </div>
        </div>
        <div class="h-72 w-full">
            <canvas id="barChartKepuasan"></canvas>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- 1. Line Chart: Tren Kunjungan Harian ---
        const trenData = @json($trenKunjungan);
        const trenLabels = Object.keys(trenData).map(d => {
            const date = new Date(d);
            return date.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
        });
        const trenValues = Object.values(trenData);

        const ctxTren = document.getElementById('lineChartTren').getContext('2d');
        new Chart(ctxTren, {
            type: 'line',
            data: {
                labels: trenLabels,
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: trenValues,
                    borderColor: '#14b8a6', // teal-500
                    backgroundColor: 'rgba(20, 184, 166, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0f766e', // teal-700
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    },
                    y: {
                        border: { dash: [4, 4] },
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#94a3b8', font: { size: 10 }, stepSize: 1 }
                    }
                }
            }
        });


        // --- 2. Doughnut Chart: Keperluan Pelayanan ---
        const keperluanData = @json($keperluanChart);
        const keperluanLabels = Object.keys(keperluanData).map(k => k.charAt(0).toUpperCase() + k.slice(1));
        const keperluanValues = Object.values(keperluanData);

        const ctxKeperluan = document.getElementById('doughnutChartKeperluan').getContext('2d');
        new Chart(ctxKeperluan, {
            type: 'doughnut',
            data: {
                labels: keperluanLabels,
                datasets: [{
                    data: keperluanValues,
                    backgroundColor: [
                        '#0ea5e9', // sky-500 (pendaftaran)
                        '#14b8a6', // teal-500 (konsultasi)
                        '#f59e0b', // amber-500 (pelimpahan)
                        '#ef4444', // rose-500 (pembatalan)
                        '#64748b'  // slate-500 (lainnya)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#475569',
                            font: { size: 10, weight: 'semibold' },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                },
                cutout: '65%'
            }
        });


        // --- 3. Bar Chart: Kelompok Usia ---
        const usiaData = @json($usiaChart);
        const usiaLabels = Object.keys(usiaData);
        const usiaValues = Object.values(usiaData);

        const ctxUsia = document.getElementById('barChartUsia').getContext('2d');
        new Chart(ctxUsia, {
            type: 'bar',
            data: {
                labels: usiaLabels,
                datasets: [{
                    data: usiaValues,
                    backgroundColor: 'rgba(14, 165, 233, 0.8)', // sky-500
                    hoverBackgroundColor: '#0ea5e9',
                    borderRadius: 8,
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    },
                    y: {
                        border: { dash: [4, 4] },
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#94a3b8', font: { size: 10 }, stepSize: 1 }
                    }
                }
            }
        });


        // --- 4. Bar Chart: Tingkat Kepuasan ---
        const kepuasanData = @json($kepuasanChart);
        const kepuasanLabels = Object.keys(kepuasanData).map(k => `${k} Bintang`);
        const kepuasanValues = Object.values(kepuasanData);

        const ctxKepuasan = document.getElementById('barChartKepuasan').getContext('2d');
        new Chart(ctxKepuasan, {
            type: 'bar',
            data: {
                labels: kepuasanLabels,
                datasets: [{
                    data: kepuasanValues,
                    backgroundColor: 'rgba(245, 158, 11, 0.8)', // amber-500
                    hoverBackgroundColor: '#f59e0b',
                    borderRadius: 8,
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    },
                    y: {
                        border: { dash: [4, 4] },
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#94a3b8', font: { size: 10 }, stepSize: 1 }
                    }
                }
            }
        });

    });
</script>
@endsection
