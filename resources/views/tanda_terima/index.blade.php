@extends('layouts.app')

@section('title', 'Riwayat Tanda Terima Berkas')
@section('page_title', 'Riwayat Tanda Terima Berkas')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Navigation Banner -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm shadow-slate-100/50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Riwayat Penyerahan Berkas</h3>
            <p class="text-xs text-slate-500">Daftar tanda terima berkas jemaah Kementerian Haji & Umrah Kab. Purbalingga</p>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto justify-end">
            <a href="{{ route('tanda-terima.laporan') }}" 
               class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-chart-pie text-teal-600"></i>
                <span>Rekap & Laporan</span>
            </a>

            <a href="{{ route('tanda-terima.create') }}" 
               class="px-5 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-900 font-extrabold text-xs transition duration-200 shadow-md shadow-teal-500/20 flex items-center space-x-2">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Buat Tanda Terima Baru</span>
            </a>
        </div>
    </div>

    <!-- Summary Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Hari Ini -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm shadow-slate-100/50 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hari Ini (WIB)</span>
                <div class="text-2xl font-extrabold text-slate-900">{{ $stats['today'] }}</div>
                <p class="text-[11px] text-teal-600 font-medium">Transaksi tanda terima</p>
            </div>
            <div class="h-11 w-11 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <i class="fa-regular fa-calendar-check text-lg"></i>
            </div>
        </div>

        <!-- Bulan Ini -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm shadow-slate-100/50 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Bulan Ini</span>
                <div class="text-2xl font-extrabold text-slate-900">{{ $stats['this_month'] }}</div>
                <p class="text-[11px] text-blue-600 font-medium">{{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('MMMM YYYY') }}</p>
            </div>
            <div class="h-11 w-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-calendar-days text-lg"></i>
            </div>
        </div>

        <!-- Total Keseluruhan -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm shadow-slate-100/50 flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Tanda Terima</span>
                <div class="text-2xl font-extrabold text-slate-900">{{ $stats['total'] }}</div>
                <p class="text-[11px] text-slate-400 font-medium">Seluruh arsip data</p>
            </div>
            <div class="h-11 w-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                <i class="fa-solid fa-receipt text-lg"></i>
            </div>
        </div>

        <!-- Status Summary Pill Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm shadow-slate-100/50 flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Sebaran Status</span>
            <div class="grid grid-cols-3 gap-1.5 text-center text-[10px] font-bold">
                <div class="bg-amber-50 text-amber-700 py-1 px-1 rounded-lg border border-amber-200">
                    <div>{{ $stats['diterima'] }}</div>
                    <span class="text-[9px] font-normal">Diterima</span>
                </div>
                <div class="bg-sky-50 text-sky-700 py-1 px-1 rounded-lg border border-sky-200">
                    <div>{{ $stats['diproses'] }}</div>
                    <span class="text-[9px] font-normal">Diproses</span>
                </div>
                <div class="bg-emerald-50 text-emerald-700 py-1 px-1 rounded-lg border border-emerald-200">
                    <div>{{ $stats['selesai'] }}</div>
                    <span class="text-[9px] font-normal">Selesai</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
        <form action="{{ route('tanda-terima.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">
                
                <!-- Search Box -->
                <div class="lg:col-span-4">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Cari Data</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="No TTB / Penyetor / No HP / Catatan..."
                               class="w-full rounded-2xl border border-slate-200 py-2.5 pl-9 pr-4 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 bg-slate-50/50 focus:bg-white transition">
                    </div>
                </div>

                <!-- Jenis Berkas Filter -->
                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Berkas</label>
                    <select name="jenis_berkas"
                            class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50/50 focus:bg-white transition">
                        <option value="">Semua Jenis Berkas</option>
                        @foreach($jenisBerkasList as $jb)
                        <option value="{{ $jb }}" {{ $jenisBerkas == $jb ? 'selected' : '' }}>{{ $jb }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status"
                            class="w-full rounded-2xl border border-slate-200 py-2.5 px-3.5 text-xs font-medium focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50/50 focus:bg-white transition">
                        <option value="">Semua Status</option>
                        @foreach($statusList as $st)
                        <option value="{{ $st }}" {{ $status == $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dari Tanggal -->
                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Filter Rentang Tanggal</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" name="dari_tanggal" value="{{ $dariTanggal }}"
                               class="w-full rounded-xl border border-slate-200 py-2 px-2 text-[11px] focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/10 bg-slate-50/50">
                        <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal }}"
                               class="w-full rounded-xl border border-slate-200 py-2 px-2 text-[11px] focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/10 bg-slate-50/50">
                    </div>
                </div>

            </div>

            <!-- Submit / Reset Buttons -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                <span class="text-slate-400">
                    Menampilkan <strong class="text-slate-700">{{ $tandaTerimaList->total() }}</strong> tanda terima ditemukan
                </span>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('tanda-terima.index') }}" 
                       class="px-4 py-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 font-semibold transition">
                        Reset Filter
                    </a>
                    <button type="submit" 
                            class="px-5 py-2 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-900 font-bold transition flex items-center space-x-1.5 shadow-sm">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm shadow-slate-100/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-4 px-5">Nomor TTB</th>
                        <th class="py-4 px-5">Nama Penyetor</th>
                        <th class="py-4 px-5">No. HP</th>
                        <th class="py-4 px-5">Jenis Berkas</th>
                        <th class="py-4 px-5">Waktu Setor (WIB)</th>
                        <th class="py-4 px-5">Status</th>
                        <th class="py-4 px-5">Petugas</th>
                        <th class="py-4 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($tandaTerimaList as $item)
                    <tr class="hover:bg-teal-50/30 transition-colors group">
                        
                        <!-- Nomor TTB -->
                        <td class="py-4 px-5 font-mono font-bold text-slate-900 whitespace-nowrap">
                            <a href="{{ route('tanda-terima.show', $item->id) }}" class="text-teal-700 hover:underline">
                                {{ $item->nomor_tanda_terima }}
                            </a>
                        </td>

                        <!-- Nama Penyetor -->
                        <td class="py-4 px-5 font-semibold text-slate-900 whitespace-nowrap">
                            {{ $item->nama_penyetor }}
                        </td>

                        <!-- No HP -->
                        <td class="py-4 px-5 text-slate-600 whitespace-nowrap">
                            {{ $item->no_hp }}
                        </td>

                        <!-- Jenis Berkas -->
                        <td class="py-4 px-5">
                            <div class="flex flex-wrap gap-1 max-w-xs">
                                @foreach($item->berkas as $b)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $b->jenis_berkas }}
                                </span>
                                @endforeach
                            </div>
                        </td>

                        <!-- Tanggal & Waktu -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <div class="font-medium text-slate-800">{{ $item->formatted_short_date }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">{{ $item->formatted_short_time }}</div>
                        </td>

                        <!-- Status -->
                        <td class="py-4 px-5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold border {{ $item->status_badge_classes['bg'] }} {{ $item->status_badge_classes['text'] }} {{ $item->status_badge_classes['border'] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $item->status_badge_classes['dot'] }} mr-1.5"></span>
                                {{ $item->status }}
                            </span>
                        </td>

                        <!-- Petugas -->
                        <td class="py-4 px-5 text-slate-600 whitespace-nowrap">
                            {{ $item->petugas ? $item->petugas->name : 'Admin' }}
                        </td>

                        <!-- Aksi: Lihat | Cetak Ulang -->
                        <td class="py-4 px-5 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <!-- Lihat -->
                                <a href="{{ route('tanda-terima.show', $item->id) }}" 
                                   title="Lihat Detail Transaksi"
                                   class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold text-xs transition flex items-center space-x-1">
                                    <i class="fa-regular fa-eye"></i>
                                    <span>Lihat</span>
                                </a>

                                <!-- Cetak Ulang -->
                                <a href="{{ route('tanda-terima.print', $item->id) }}" target="_blank"
                                   title="Cetak Ulang Thermal 80mm"
                                   class="px-3 py-1.5 rounded-lg bg-teal-50 border border-teal-200 text-teal-700 hover:bg-teal-100 font-bold text-xs transition flex items-center space-x-1">
                                    <i class="fa-solid fa-print"></i>
                                    <span>Cetak</span>
                                </a>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <i class="fa-regular fa-folder-open text-4xl text-slate-300"></i>
                                <span class="font-medium text-sm">Belum ada data tanda terima berkas ditemukan.</span>
                                <span class="text-xs">Klik tombol "Buat Tanda Terima Baru" untuk menginput transaksi baru.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tandaTerimaList->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $tandaTerimaList->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
