@extends('layouts.app')

@section('title', 'Laporan Surat Masuk & Keluar')
@section('page_title', 'Laporan Surat Masuk & Keluar')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ jenisSurat: 'masuk' }">
    
    <!-- Left Column: Input Form (5 cols on lg) -->
    <div class="lg:col-span-5">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
            <div class="flex items-center space-x-3 mb-6">
                <div class="h-10 w-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                    <i class="fa-solid fa-folder-plus text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Arsip Surat Baru</h3>
                    <p class="text-xs text-slate-500">Catat surat masuk atau surat keluar</p>
                </div>
            </div>

            <form action="{{ route('letters.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <!-- Toggle Jenis Surat -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Jenis Surat</label>
                    <div class="flex space-x-2 bg-slate-100 p-1 rounded-2xl">
                        <button type="button" @click="jenisSurat = 'masuk'"
                                class="flex-1 py-2.5 rounded-xl text-xs font-bold transition duration-200"
                                :class="jenisSurat === 'masuk' ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                            <i class="fa-solid fa-inbox mr-1.5"></i> Surat Masuk
                        </button>
                        <button type="button" @click="jenisSurat = 'keluar'"
                                class="flex-1 py-2.5 rounded-xl text-xs font-bold transition duration-200"
                                :class="jenisSurat === 'keluar' ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                            <i class="fa-solid fa-paper-plane mr-1.5"></i> Surat Keluar
                        </button>
                    </div>
                    <!-- Hidden input to store chosen type -->
                    <input type="hidden" name="jenis" x-model="jenisSurat">
                </div>

                <!-- Nomor Surat -->
                <div>
                    <label for="nomor_surat" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nomor Surat</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-hashtag"></i>
                        </span>
                        <input type="text" name="nomor_surat" id="nomor_surat" required value="{{ old('nomor_surat') }}"
                               class="w-full rounded-2xl border border-slate-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                               placeholder="Contoh: B-120/Kk.11.03/1/PP.00/07/2026">
                    </div>
                </div>

                <!-- Grid Tanggal Surat & Tanggal Terima/Kirim -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_surat" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" id="tanggal_surat" required value="{{ old('tanggal_surat', date('Y-m-d')) }}"
                               class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                    </div>

                    <div>
                        <!-- Dynamic Label using Alpine.js -->
                        <label for="tanggal_terima_kirim" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
                               x-text="jenisSurat === 'masuk' ? 'Tanggal Diterima' : 'Tanggal Dikirim'"></label>
                        <input type="date" name="tanggal_terima_kirim" id="tanggal_terima_kirim" required value="{{ old('tanggal_terima_kirim', date('Y-m-d')) }}"
                               class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                    </div>
                </div>

                <!-- Pengirim / Penerima (Dynamic Label using Alpine) -->
                <div>
                    <label for="pengirim_penerima" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
                           x-text="jenisSurat === 'masuk' ? 'Pengirim Surat' : 'Penerima Surat'"></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid" :class="jenisSurat === 'masuk' ? 'fa-building-columns' : 'fa-building-user'"></i>
                        </span>
                        <input type="text" name="pengirim_penerima" id="pengirim_penerima" required value="{{ old('pengirim_penerima') }}"
                               class="w-full rounded-2xl border border-slate-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                               :placeholder="jenisSurat === 'masuk' ? 'Nama instansi pengirim surat' : 'Nama instansi/orang penerima surat'">
                    </div>
                </div>

                <!-- Perihal / Hal -->
                <div>
                    <label for="perihal" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Perihal / Subjek Surat</label>
                    <textarea name="perihal" id="perihal" rows="3" required
                              class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                              placeholder="Perihal isi ringkas surat..."></textarea>
                </div>

                <!-- Upload Scan Surat -->
                <div>
                    <label for="file_surat" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Upload Scan Dokumen (PDF/Gambar)</label>
                    <input type="file" name="file_surat" id="file_surat" accept=".pdf,image/*,.doc,.docx"
                           class="block w-full text-sm text-slate-500 border border-slate-200 rounded-2xl file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer" />
                    <p class="text-[10px] text-slate-400 mt-1.5"><i class="fa-solid fa-circle-info mr-1"></i>Dianjurkan scan dalam format PDF (Maksimal 10MB)</p>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold text-sm hover:from-teal-600 hover:to-emerald-600 transition duration-300 shadow-md shadow-teal-500/20 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-box-archive"></i>
                    <span x-text="jenisSurat === 'masuk' ? 'Arsipkan Surat Masuk' : 'Arsipkan Surat Keluar'"></span>
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: List & Filters (7 cols on lg) -->
    <div class="lg:col-span-7 space-y-6">
        
        <!-- Tabbed Header & Filter Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center space-x-2">
                    <i class="fa-solid fa-envelope-open-text text-teal-500"></i>
                    <span>Arsip Dokumen Surat</span>
                </h3>
                
                <!-- Quick Filter Tabs -->
                <div class="flex space-x-1.5 bg-slate-50 border border-slate-100 p-0.5 rounded-xl">
                    <a href="{{ route('letters.index') }}" 
                       class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition duration-150 {{ !request('jenis') ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Semua
                    </a>
                    <a href="{{ route('letters.index', ['jenis' => 'masuk']) }}" 
                       class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition duration-150 {{ request('jenis') === 'masuk' ? 'bg-white text-teal-600 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Masuk
                    </a>
                    <a href="{{ route('letters.index', ['jenis' => 'keluar']) }}" 
                       class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition duration-150 {{ request('jenis') === 'keluar' ? 'bg-white text-teal-600 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        Keluar
                    </a>
                </div>
            </div>

            <!-- Advanced Filter / Search Form -->
            <form action="{{ route('letters.index') }}" method="GET" class="space-y-4">
                @if(request('jenis'))
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Mulai Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
                    </div>
                </div>

                <div class="flex space-x-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                               placeholder="Cari nomor surat, instansi, atau perihal...">
                    </div>
                    
                    <button type="submit" class="bg-slate-800 text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-900 transition">
                        Cari
                    </button>
                    @if(request()->anyFilled(['search', 'start_date', 'end_date']))
                        <a href="{{ route('letters.index', request('jenis') ? ['jenis' => request('jenis')] : []) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table List -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm shadow-slate-100/50 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Daftar Arsip Surat</h3>
                <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-full font-bold">
                    Total: {{ $letters->total() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse hidden md:table">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 text-[10px] font-semibold uppercase tracking-wider">
                            <th class="py-4 px-6">Nomor Surat & Perihal</th>
                            <th class="py-4 px-6">Pengirim/Penerima</th>
                            <th class="py-4 px-6">Tanggal</th>
                            <th class="py-4 px-6">File</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($letters as $letter)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 space-y-1 max-w-[250px]">
                                <div class="flex items-center space-x-1.5">
                                    @if($letter->jenis === 'masuk')
                                        <span class="bg-emerald-50 text-emerald-600 text-[9px] font-bold px-1.5 py-0.5 rounded border border-emerald-100">
                                            <i class="fa-solid fa-arrow-down mr-0.5"></i> Masuk
                                        </span>
                                    @else
                                        <span class="bg-blue-50 text-blue-600 text-[9px] font-bold px-1.5 py-0.5 rounded border border-blue-100">
                                            <i class="fa-solid fa-arrow-up mr-0.5"></i> Keluar
                                        </span>
                                    @endif
                                    <span class="font-bold text-slate-800 leading-none break-all" title="{{ $letter->nomor_surat }}">{{ $letter->nomor_surat }}</span>
                                </div>
                                <div class="text-slate-500 text-[11px] font-medium leading-snug pt-0.5 break-words">{{ $letter->perihal }}</div>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-700 max-w-[150px] truncate" title="{{ $letter->pengirim_penerima }}">
                                <span>{{ $letter->pengirim_penerima }}</span>
                            </td>
                            <td class="py-4 px-6 space-y-1">
                                <div class="text-[10px] text-slate-600 font-semibold" title="Tanggal Surat">
                                    <i class="fa-regular fa-envelope text-slate-400 mr-0.5"></i> 
                                    {{ \Carbon\Carbon::parse($letter->tanggal_surat)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[9px] text-slate-400 font-medium" title="{{ $letter->jenis === 'masuk' ? 'Diterima' : 'Dikirim' }}">
                                    <i class="fa-regular fa-calendar-check text-slate-400 mr-0.5"></i>
                                    {{ \Carbon\Carbon::parse($letter->tanggal_terima_kirim)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($letter->file_surat)
                                <a href="{{ asset('storage/' . $letter->file_surat) }}" target="_blank" 
                                   class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-medium transition">
                                    <i class="fa-solid fa-file-pdf text-rose-500 text-xs"></i>
                                    <span>Unduh</span>
                                </a>
                                @else
                                <span class="text-slate-400 text-[10px] font-medium italic">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('letters.destroy', $letter) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip surat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-2 rounded-lg transition" title="Hapus">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 font-medium">
                                <i class="fa-regular fa-folder-open text-3xl mb-2 block text-slate-300"></i>
                                Belum ada arsip surat yang didata.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Card List (Arsip Surat khusus HP/Tablet) -->
                <div class="grid grid-cols-1 gap-4 p-4 md:hidden">
                    @forelse($letters as $letter)
                    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 space-y-3 relative">
                        <div class="space-y-1.5">
                            <div class="flex items-center space-x-1.5">
                                @if($letter->jenis === 'masuk')
                                    <span class="bg-emerald-50 text-emerald-600 text-[9px] font-bold px-1.5 py-0.5 rounded border border-emerald-100">
                                        Masuk
                                    </span>
                                @else
                                    <span class="bg-blue-50 text-blue-600 text-[9px] font-bold px-1.5 py-0.5 rounded border border-blue-100">
                                        Keluar
                                    </span>
                                @endif
                                <span class="font-bold text-slate-900 break-all text-xs">{{ $letter->nomor_surat }}</span>
                            </div>
                            <p class="text-slate-500 text-[11px] font-medium leading-relaxed pt-0.5">{{ $letter->perihal }}</p>
                        </div>
                        
                        <div class="text-xs space-y-1.5 border-t border-slate-200/60 pt-3 text-slate-600">
                            <div class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-building-columns"></i>
                                <span>{{ $letter->jenis === 'masuk' ? 'Pengirim' : 'Penerima' }}: <strong>{{ $letter->pengirim_penerima }}</strong></span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <i class="fa-regular fa-envelope text-slate-400 flex-shrink-0 w-3"></i>
                                <span>Tanggal Surat: <strong>{{ \Carbon\Carbon::parse($letter->tanggal_surat)->translatedFormat('d M Y') }}</strong></span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <i class="fa-regular fa-calendar-check text-slate-400 flex-shrink-0 w-3"></i>
                                <span>{{ $letter->jenis === 'masuk' ? 'Diterima' : 'Dikirim' }}: <strong>{{ \Carbon\Carbon::parse($letter->tanggal_terima_kirim)->translatedFormat('d M Y') }}</strong></span>
                            </div>
                        </div>

                        @if($letter->file_surat)
                        <div class="pt-1">
                            <a href="{{ asset('storage/' . $letter->file_surat) }}" target="_blank" 
                               class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 hover:bg-slate-200 text-slate-600 font-bold text-[10px] transition">
                                <i class="fa-solid fa-file-pdf text-rose-500 text-xs"></i>
                                <span>Unduh Scan Surat</span>
                            </a>
                        </div>
                        @endif
                        
                        <div class="flex justify-end border-t border-slate-200/60 pt-3">
                            <form action="{{ route('letters.destroy', $letter) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip surat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold flex items-center space-x-1 py-1.5 px-2 rounded-lg hover:bg-rose-50 text-[11px]">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                    <span>Hapus Surat</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-slate-400 font-medium py-6">
                        <i class="fa-regular fa-folder-open text-2xl mb-1 block text-slate-300"></i>
                        Belum ada arsip surat yang didata.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination wrapper -->
            @if($letters->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $letters->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
