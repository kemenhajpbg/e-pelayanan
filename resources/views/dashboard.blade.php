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
            Kirimkan seluruh berkas rekapitulasi data buku tamu, berita tayang, serta surat masuk/keluar dalam format Excel ke email <span class="text-teal-400 font-bold">izzulhaq014@gmail.com</span>.
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

    <!-- Surat Masuk -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 flex items-center justify-between group hover:border-emerald-500/30 transition-all duration-300 transform hover:-translate-y-1">
        <div class="space-y-2">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Surat Masuk</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $totalLettersIn }}</div>
            <p class="text-[10px] text-slate-500 font-medium">Surat diarsipkan</p>
        </div>
        <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center transition-colors group-hover:bg-emerald-500 group-hover:text-white">
            <i class="fa-solid fa-inbox text-lg"></i>
        </div>
    </div>

    <!-- Surat Keluar -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 flex items-center justify-between group hover:border-blue-500/30 transition-all duration-300 transform hover:-translate-y-1">
        <div class="space-y-2">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Surat Keluar</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">{{ $totalLettersOut }}</div>
            <p class="text-[10px] text-slate-500 font-medium">Surat dikirim keluar</p>
        </div>
        <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center transition-colors group-hover:bg-blue-500 group-hover:text-white">
            <i class="fa-solid fa-paper-plane text-lg"></i>
        </div>
    </div>

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
        <div class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center transition-colors group-hover:bg-amber-500 group-hover:text-white">
            <i class="fa-solid fa-face-smile text-lg"></i>
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
                <h3 class="text-sm font-bold text-slate-800">Keperluan Pengunjung</h3>
                <p class="text-[10px] text-slate-400">Distribusi alasan kedatangan pengunjung</p>
            </div>
            <div class="bg-teal-50 text-teal-600 text-[10px] font-bold px-2 py-1 rounded-lg">
                <i class="fa-solid fa-pie-chart mr-1"></i> Proporsi
            </div>
        </div>
        <div class="h-80 w-full flex items-center justify-center">
            <canvas id="doughnutChartKeperluan"></canvas>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Distribusi Kelompok Usia (Bar Chart) - 6 cols on lg -->
    <div class="lg:col-span-6 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Kelompok Usia Pengunjung</h3>
                <p class="text-[10px] text-slate-400">Distribusi usia yang mengakses layanan</p>
            </div>
            <div class="bg-teal-50 text-teal-600 text-[10px] font-bold px-2 py-1 rounded-lg">
                <i class="fa-solid fa-users-rectangle mr-1"></i> Demografi
            </div>
        </div>
        <div class="h-80 w-full">
            <canvas id="barChartUsia"></canvas>
        </div>
    </div>

    <!-- Distribusi Kepuasan Pengunjung (Bar Chart) - 6 cols on lg -->
    <div class="lg:col-span-6 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Indeks Kepuasan Pengunjung</h3>
                <p class="text-[10px] text-slate-400">Akumulasi rating kepuasan pelayanan (1 - 5 bintang)</p>
            </div>
            <div class="bg-teal-50 text-teal-600 text-[10px] font-bold px-2 py-1 rounded-lg">
                <i class="fa-solid fa-face-laugh-beam mr-1"></i> Kepuasan
            </div>
        </div>
        <div class="h-80 w-full">
            <canvas id="barChartKepuasan"></canvas>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- 1. Line Chart: Tren Kunjungan Harian ---
        const trenData = @json($trenKunjungan);
        const trenLabels = Object.keys(trenData).map(dateStr => {
            // Ubah format tanggal YYYY-MM-DD ke format yang lebih santai (misal: 06 Jul)
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        });
        const trenValues = Object.values(trenData);

        const ctxTren = document.getElementById('lineChartTren').getContext('2d');
        // Create subtle gradient for Line chart area
        const gradTren = ctxTren.createLinearGradient(0, 0, 0, 300);
        gradTren.addColorStop(0, 'rgba(20, 184, 166, 0.25)'); // teal
        gradTren.addColorStop(1, 'rgba(20, 184, 166, 0.00)');

        new Chart(ctxTren, {
            type: 'line',
            data: {
                labels: trenLabels,
                datasets: [{
                    label: 'Jumlah Pengunjung',
                    data: trenValues,
                    borderColor: '#14b8a6', // teal-500
                    backgroundColor: gradTren,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#14b8a6',
                    pointHoverRadius: 6,
                    pointRadius: 4
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
