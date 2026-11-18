@extends('layouts.app')

@section('title', 'Laporan & Rekapitulasi Tanda Terima Berkas')
@section('page_title', 'Laporan & Rekapitulasi Tanda Terima')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Navigation Banner -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm shadow-slate-100/50 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-100">
                    Periode: {{ $labelPeriode }}
                </span>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mt-1">Laporan Penyerahan Berkas Pelayanan</h3>
            <p class="text-xs text-slate-500">Statistik dan rincian transaksi pelayanan berkas haji & umrah</p>
        </div>

        <!-- Export Buttons: Excel & PDF -->
        <div class="flex items-center space-x-3 w-full md:w-auto justify-end">
            <!-- Export Excel -->
            <a href="{{ route('tanda-terima.export-excel', request()->all()) }}" 
               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition duration-200 shadow-md shadow-emerald-600/20 flex items-center space-x-2">
                <i class="fa-solid fa-file-excel"></i>
                <span>Export Excel</span>
            </a>

            <!-- Export PDF / Cetak Laporan -->
            <a href="{{ route('tanda-terima.export-pdf', request()->all()) }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition duration-200 shadow-md shadow-slate-900/20 flex items-center space-x-2">
                <i class="fa-solid fa-file-pdf text-rose-400"></i>
                <span>Cetak / Export PDF</span>
            </a>
        </div>
    </div>

    <!-- Filter Periode & Kriteria Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50" x-data="{ modePeriode: '{{ $periode }}' }">
        <form action="{{ route('tanda-terima.laporan') }}" method="GET" class="space-y-4">
            
            <!-- Radio Selector Jenis Periode -->
            <div class="flex flex-wrap gap-2 border-b border-slate-100 pb-4">
                <label class="cursor-pointer">
                    <input type="radio" name="periode" value="harian" x-model="modePeriode" class="hidden">
                    <span class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5"
                          :class="modePeriode === 'harian' ? 'bg-teal-500 text-slate-900 shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                        <i class="fa-regular fa-calendar-day"></i>
                        <span>Harian</span>
                    </span>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="periode" value="bulanan" x-model="modePeriode" class="hidden">
                    <span class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5"
                          :class="modePeriode === 'bulanan' ? 'bg-teal-500 text-slate-900 shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                        <i class="fa-regular fa-calendar"></i>
                        <span>Bulanan</span>
                    </span>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="periode" value="tahunan" x-model="modePeriode" class="hidden">
                    <span class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5"
                          :class="modePeriode === 'tahunan' ? 'bg-teal-500 text-slate-900 shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>Tahunan</span>
                    </span>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="periode" value="rentang" x-model="modePeriode" class="hidden">
                    <span class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5"
                          :class="modePeriode === 'rentang' ? 'bg-teal-500 text-slate-900 shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                        <i class="fa-solid fa-calendar-week"></i>
                        <span>Rentang Tanggal</span>
                    </span>
                </label>
            </div>

            <!-- Dynamic Input Fields based on modePeriode -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- If Harian -->
                <div x-show="modePeriode === 'harian'">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                           class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50 focus:bg-white">
                </div>

                <!-- If Bulanan -->
                <div x-show="modePeriode === 'bulanan'" class="contents">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Bulan</label>
                        <select name="bulan"
                                class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50 focus:bg-white">
                            @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(2026, $m, 1)->locale('id')->isoFormat('MMMM') }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tahun</label>
                        <input type="number" name="tahun" value="{{ $tahun }}" min="2020" max="2050"
                               class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <!-- If Tahunan -->
                <div x-show="modePeriode === 'tahunan'">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Tahun</label>
                    <input type="number" name="tahun" value="{{ $tahun }}" min="2020" max="2050"
                           class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50 focus:bg-white">
                </div>

                <!-- If Rentang Tanggal -->
                <div x-show="modePeriode === 'rentang'" class="contents">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" value="{{ $dariTanggal }}"
                               class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal }}"
                               class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <!-- Filter Kategori Berkas -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Jenis Berkas</label>
                    <select name="jenis_berkas"
                            class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50 focus:bg-white">
                        <option value="">Semua Jenis Berkas</option>
                        @foreach($jenisBerkasList as $jb)
                        <option value="{{ $jb }}" {{ $jenisBerkas == $jb ? 'selected' : '' }}>{{ $jb }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status"
                            class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50 focus:bg-white">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\TandaTerima::STATUS_LIST as $st)
                        <option value="{{ $st }}" {{ $status == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-900 font-bold text-xs transition flex items-center space-x-2 shadow-sm">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Tampilkan Laporan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Statistik Periode Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Total -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm text-center">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Transaksi</span>
            <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalTransaksi }}</div>
        </div>

        <!-- Diterima -->
        <div class="bg-white rounded-2xl border border-amber-200 p-4 shadow-sm text-center bg-amber-50/20">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Diterima</span>
            <div class="text-2xl font-extrabold text-amber-600 mt-1">{{ $statusCounts['Diterima'] ?? 0 }}</div>
        </div>

        <!-- Diproses -->
        <div class="bg-white rounded-2xl border border-sky-200 p-4 shadow-sm text-center bg-sky-50/20">
            <span class="text-[10px] font-bold uppercase tracking-wider text-sky-700">Diproses</span>
            <div class="text-2xl font-extrabold text-sky-600 mt-1">{{ $statusCounts['Diproses'] ?? 0 }}</div>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-2xl border border-emerald-200 p-4 shadow-sm text-center bg-emerald-50/20">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Selesai</span>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $statusCounts['Selesai'] ?? 0 }}</div>
        </div>

        <!-- Dikembalikan -->
        <div class="bg-white rounded-2xl border border-purple-200 p-4 shadow-sm text-center bg-purple-50/20">
            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700">Dikembalikan</span>
            <div class="text-2xl font-extrabold text-purple-600 mt-1">{{ $statusCounts['Dikembalikan'] ?? 0 }}</div>
        </div>

        <!-- Dibatalkan -->
        <div class="bg-white rounded-2xl border border-rose-200 p-4 shadow-sm text-center bg-rose-50/20">
            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-700">Dibatalkan</span>
            <div class="text-2xl font-extrabold text-rose-600 mt-1">{{ $statusCounts['Dibatalkan'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Statistik Berdasarkan 7 Jenis Berkas -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 md:p-8 shadow-sm shadow-slate-100/50 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h4 class="text-base font-bold text-slate-900">Rekapitulasi Berdasarkan Jenis Berkas</h4>
                <p class="text-xs text-slate-500">Distribusi volume berkas yang diterima pada periode terpilih</p>
            </div>
            <span class="text-xs font-semibold text-teal-600 bg-teal-50 px-3 py-1 rounded-xl border border-teal-100">
                7 Kategori Utama
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
            @php
                $iconMap = [
                    'Berkas Pelimpahan' => ['icon' => 'fa-arrows-split-up-and-left', 'color' => 'teal'],
                    'Berkas Pembatalan' => ['icon' => 'fa-file-circle-xmark', 'color' => 'rose'],
                    'Paspor' => ['icon' => 'fa-passport', 'color' => 'sky'],
                    'Berkas Perbaikan Data Jemaah' => ['icon' => 'fa-user-pen', 'color' => 'indigo'],
                    'Berkas Pengajuan Penggabungan/Pendampingan' => ['icon' => 'fa-people-arrows', 'color' => 'purple'],
                    'Berkas Pengajuan Mutasi' => ['icon' => 'fa-right-left', 'color' => 'amber'],
                    'Berkas Keberangkatan Haji' => ['icon' => 'fa-kaaba', 'color' => 'emerald'],
                ];
            @endphp

            @foreach($jenisBerkasList as $jb)
            @php
                $count = $jenisBerkasCounts[$jb] ?? 0;
                $conf = $iconMap[$jb] ?? ['icon' => 'fa-folder', 'color' => 'slate'];
            @endphp
            <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-800 block line-clamp-1" title="{{ $jb }}">{{ $jb }}</span>
                    <span class="text-lg font-extrabold text-slate-900">{{ $count }} <span class="text-xs font-normal text-slate-400">berkas</span></span>
                </div>
                <div class="h-10 w-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 shadow-sm">
                    <i class="fa-solid {{ $conf['icon'] }}"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tabel Rincian Data Transaksi Laporan -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm shadow-slate-100/50 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h4 class="text-base font-bold text-slate-900">Rincian Transaksi Tanda Terima</h4>
            <span class="text-xs text-slate-400">Total {{ $allResults->count() }} Dokumen</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Nomor TTB</th>
                        <th class="py-3.5 px-4">Tanggal Setor</th>
                        <th class="py-3.5 px-4">Waktu (WIB)</th>
                        <th class="py-3.5 px-4">Penyetor</th>
                        <th class="py-3.5 px-4">No. HP</th>
                        <th class="py-3.5 px-4">Jenis Berkas</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Petugas</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($allResults as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4 text-slate-400 font-mono">{{ $index + 1 }}</td>
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                            <a href="{{ route('tanda-terima.show', $item->id) }}" class="text-teal-700 hover:underline">
                                {{ $item->nomor_tanda_terima }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap text-slate-700">{{ $item->formatted_short_date }}</td>
                        <td class="py-3.5 px-4 whitespace-nowrap font-mono text-slate-500">{{ $item->formatted_short_time }}</td>
                        <td class="py-3.5 px-4 font-semibold text-slate-900 whitespace-nowrap">{{ $item->nama_penyetor }}</td>
                        <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">{{ $item->no_hp }}</td>
                        <td class="py-3.5 px-4">
                            <div class="flex flex-wrap gap-1 max-w-xs">
                                @foreach($item->berkas as $b)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700">
                                    {{ $b->jenis_berkas }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold border {{ $item->status_badge_classes['bg'] }} {{ $item->status_badge_classes['text'] }} {{ $item->status_badge_classes['border'] }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">{{ $item->petugas ? $item->petugas->name : 'Admin' }}</td>
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <a href="{{ route('tanda-terima.show', $item->id) }}" class="text-teal-600 hover:text-teal-800 font-bold">
                                Detail &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-slate-400">
                            Tidak ada transaksi tanda terima pada kriteria laporan yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
