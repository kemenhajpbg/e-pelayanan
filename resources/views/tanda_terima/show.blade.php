@extends('layouts.app')

@section('title', 'Detail Tanda Terima ' . $tandaTerima->nomor_tanda_terima)
@section('page_title', 'Detail Tanda Terima Berkas')

@section('content')
<div class="max-w-5xl mx-auto space-y-8" x-data="{ openStatusModal: false, selectedStatus: '{{ $tandaTerima->status }}' }">

    <!-- Top Action Bar -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-5 shadow-sm shadow-slate-100/50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <a href="{{ route('tanda-terima.index') }}" 
               class="h-10 w-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nomor Tanda Terima</span>
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $tandaTerima->nomor_tanda_terima }}</h3>
            </div>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto justify-end">
            <!-- Tombol Ubah Status -->
            <button type="button" @click="openStatusModal = true"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition duration-200 flex items-center space-x-2">
                <i class="fa-solid fa-pen-to-square text-teal-600"></i>
                <span>Perbarui Status</span>
            </button>

            <!-- Tombol Cetak Thermal -->
            <a href="{{ route('tanda-terima.print', $tandaTerima->id) }}" target="_blank"
               class="px-5 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-900 font-extrabold text-xs transition duration-200 shadow-md shadow-teal-500/20 flex items-center space-x-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Tanda Terima (80mm)</span>
            </a>
        </div>
    </div>

    <!-- Grid Detail Data Transaksi -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Detail Berkas & Penyetor (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 md:p-8 shadow-sm shadow-slate-100/50 space-y-6">
                
                <!-- Status Banner Card -->
                <div class="flex items-center justify-between p-4 rounded-2xl border {{ $tandaTerima->status_badge_classes['bg'] }} {{ $tandaTerima->status_badge_classes['border'] }}">
                    <div class="flex items-center space-x-3">
                        <span class="h-3 w-3 rounded-full {{ $tandaTerima->status_badge_classes['dot'] }}"></span>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Status Pelayanan Saat Ini</span>
                            <div class="text-base font-extrabold uppercase tracking-wide {{ $tandaTerima->status_badge_classes['text'] }}">
                                STATUS: {{ $tandaTerima->status }}
                            </div>
                        </div>
                    </div>
                    <span class="text-xl {{ $tandaTerima->status_badge_classes['text'] }}">
                        <i class="fa-solid {{ $tandaTerima->status_badge_classes['icon'] }}"></i>
                    </span>
                </div>

                @if($tandaTerima->alasan_status)
                <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800">
                    <span class="font-bold">Catatan / Alasan Status Terakhir:</span>
                    <p class="mt-0.5 text-slate-700 leading-relaxed">{{ $tandaTerima->alasan_status }}</p>
                </div>
                @endif

                <!-- Data Penyetor Section -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-2">
                        <i class="fa-solid fa-user text-teal-600"></i>
                        <span>Informasi Penyetor</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50/60 p-4 rounded-2xl border border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Nama Penyetor:</span>
                            <span class="text-sm font-bold text-slate-900">{{ $tandaTerima->nama_penyetor }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Nomor HP / WhatsApp:</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tandaTerima->no_hp) }}" target="_blank"
                               class="text-sm font-bold text-teal-700 hover:underline flex items-center space-x-1.5 mt-0.5">
                                <i class="fa-brands fa-whatsapp text-emerald-600"></i>
                                <span>{{ $tandaTerima->no_hp }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Waktu Setor Otomatis -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-2">
                        <i class="fa-regular fa-clock text-teal-600"></i>
                        <span>Pencatatan Waktu Resmi (Asia/Jakarta)</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50/60 p-4 rounded-2xl border border-slate-100">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Tanggal Setor:</span>
                            <span class="text-sm font-bold text-slate-900">{{ $tandaTerima->formatted_tanggal }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Waktu Setor:</span>
                            <span class="text-sm font-bold text-slate-900 font-mono">{{ $tandaTerima->formatted_waktu }}</span>
                        </div>
                    </div>
                </div>

                <!-- Berkas Yang Disetorkan -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-2">
                        <i class="fa-solid fa-folder-open text-teal-600"></i>
                        <span>Berkas Yang Disetorkan ({{ $tandaTerima->berkas->count() }})</span>
                    </h4>

                    <div class="space-y-2">
                        @foreach($tandaTerima->berkas as $item)
                        <div class="flex items-center space-x-3 p-3 bg-teal-50/40 border border-teal-100 rounded-xl">
                            <span class="h-6 w-6 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-bold">
                                ✓
                            </span>
                            <span class="text-sm font-semibold text-slate-900">{{ $item->jenis_berkas }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Keterangan Tambahan -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-2">
                        <i class="fa-solid fa-file-lines text-teal-600"></i>
                        <span>Keterangan Catatan</span>
                    </h4>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm text-slate-700 leading-relaxed">
                        {{ $tandaTerima->keterangan ?: 'Tidak ada keterangan tambahan.' }}
                    </div>
                </div>

                <!-- Petugas Penerima -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        <span>Petugas Penerima:</span>
                        <strong class="text-slate-900 ml-1">{{ $tandaTerima->petugas ? $tandaTerima->petugas->name : 'Admin Pelayanan' }}</strong>
                    </div>
                    <div>
                        <span>Sistem e-Pelayanan</span>
                    </div>
                </div>

            </div>

        </div>

        <!-- Right: Tanda Tangan & Timeline Riwayat Status (5 cols) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Card Tanda Tangan Digital -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-2">
                        <i class="fa-solid fa-signature text-teal-600"></i>
                        <span>Tanda Tangan Digital</span>
                    </h4>
                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                        Terverifikasi
                    </span>
                </div>

                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-3 flex flex-col items-center justify-center">
                    @if($tandaTerima->tanda_tangan)
                    <img src="{{ $tandaTerima->tanda_tangan }}" 
                         alt="Tanda Tangan {{ $tandaTerima->nama_penyetor }}"
                         class="max-h-40 w-auto object-contain bg-white rounded-xl p-2 border border-slate-100 shadow-sm">
                    @else
                    <span class="text-xs text-slate-400 italic py-8">Tidak ada tanda tangan</span>
                    @endif
                    <div class="mt-3 text-center">
                        <span class="text-xs font-bold text-slate-800 block underline">{{ $tandaTerima->nama_penyetor }}</span>
                        <span class="text-[10px] text-slate-400">Penyetor Berkas</span>
                    </div>
                </div>
            </div>

            <!-- Card Timeline Riwayat Status (Audit Trail) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-2">
                        <i class="fa-solid fa-timeline text-teal-600"></i>
                        <span>Riwayat Perjalanan Pelayanan</span>
                    </h4>
                    <span class="text-[10px] text-slate-400">{{ $tandaTerima->statusHistories->count() }} Perubahan</span>
                </div>

                <!-- Timeline Entries -->
                <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @foreach($tandaTerima->statusHistories as $history)
                    <div class="relative group">
                        <!-- Dot -->
                        <div class="absolute -left-6 top-1.5 h-3.5 w-3.5 rounded-full bg-white border-2 border-teal-500 group-hover:scale-125 transition-transform"></div>

                        <div class="bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100 space-y-1">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-slate-900">{{ $history->status }}</span>
                                <span class="text-slate-400 font-mono">{{ $history->formatted_date }} | {{ $history->formatted_time }} WIB</span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $history->keterangan }}</p>
                            <div class="pt-1 text-[10px] text-slate-400 flex items-center space-x-1">
                                <i class="fa-regular fa-user"></i>
                                <span>{{ $history->petugas ? $history->petugas->name : 'Petugas' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    <!-- MODAL PERBARUI STATUS TRANSAKSI -->
    <div x-show="openStatusModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white rounded-3xl max-w-lg w-full p-6 md:p-8 space-y-6 shadow-2xl border border-slate-100"
             @click.away="openStatusModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform scale-95 opacity-0"
             x-transition:enter-end="transform scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="transform scale-100 opacity-100"
             x-transition:leave-end="transform scale-95 opacity-0">

            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900">Perbarui Status Pelayanan</h4>
                        <p class="text-xs text-slate-400 font-mono">{{ $tandaTerima->nomor_tanda_terima }}</p>
                    </div>
                </div>
                <button @click="openStatusModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('tanda-terima.update-status', $tandaTerima->id) }}" method="POST" class="space-y-5">
                @csrf

                <!-- Pilihan Status Baru -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Status Baru</label>
                    <select name="status" x-model="selectedStatus" required
                            class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm font-semibold text-slate-800 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 bg-slate-50 focus:bg-white transition">
                        @foreach($statusList as $st)
                        <option value="{{ $st }}">{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Keterangan / Alasan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Alasan / Keterangan Perubahan
                        <span x-show="selectedStatus === 'Dikembalikan' || selectedStatus === 'Dibatalkan'" class="text-rose-500">* (Wajib Diisi)</span>
                    </label>
                    <textarea name="alasan" rows="3"
                              :required="selectedStatus === 'Dikembalikan' || selectedStatus === 'Dibatalkan'"
                              placeholder="Tuliskan alasan pengembalian/pembatalan atau catatan proses verifikasi..."
                              class="w-full rounded-2xl border border-slate-200 p-3.5 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition bg-slate-50 focus:bg-white"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openStatusModal = false"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-teal-500 text-slate-900 font-bold text-xs hover:bg-teal-400 transition shadow-md shadow-teal-500/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
@if($autoPrint)
<script>
    // Buka lembar cetak thermal secara otomatis setelah transaksi disimpan
    document.addEventListener('DOMContentLoaded', function () {
        const printUrl = "{{ route('tanda-terima.print', $tandaTerima->id) }}";
        window.open(printUrl, '_blank', 'width=450,height=700,menubar=no,toolbar=no,location=no,status=no');
    });
</script>
@endif
@endsection
