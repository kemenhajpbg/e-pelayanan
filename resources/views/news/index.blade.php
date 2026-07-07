@extends('layouts.app')

@section('title', 'Laporan Pembuatan Berita Tayang')
@section('page_title', 'Laporan Berita Tayang')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left Column: Input Form (5 cols on lg) -->
    <div class="lg:col-span-5">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
            <div class="flex items-center space-x-3 mb-6">
                <div class="h-10 w-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                    <i class="fa-solid fa-folder-plus text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Tambah Laporan Berita</h3>
                    <p class="text-xs text-slate-500">Laporkan berita atau konten yang ditayangkan</p>
                </div>
            </div>

            <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                <!-- Judul Berita -->
                <div>
                    <label for="judul" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Judul Berita / Publikasi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-heading"></i>
                        </span>
                        <input type="text" name="judul" id="judul" required value="{{ old('judul') }}"
                               class="w-full rounded-2xl border border-slate-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                               placeholder="Judul tayangan berita">
                    </div>
                </div>

                <!-- Tanggal Tayang & Kategori Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_tayang" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tanggal Tayang</label>
                        <input type="date" name="tanggal_tayang" id="tanggal_tayang" required value="{{ old('tanggal_tayang', date('Y-m-d')) }}"
                               class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                    </div>

                    <div>
                        <label for="kategori" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Kategori Konten</label>
                        <select name="kategori" id="kategori" required
                                class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Kegiatan" {{ old('kategori') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan Kemenhaj</option>
                            <option value="Pengumuman" {{ old('kategori') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            <option value="Edukasi" {{ old('kategori') == 'Edukasi' ? 'selected' : '' }}>Edukasi / Himbauan</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- Link/URL Berita -->
                <div>
                    <label for="link_berita" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Link Berita (URL Website/Medsos)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-link"></i>
                        </span>
                        <input type="url" name="link_berita" id="link_berita" value="{{ old('link_berita') }}"
                               class="w-full rounded-2xl border border-slate-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                               placeholder="Contoh: https://kemenhaj.go.id/read/...">
                    </div>
                </div>

                <!-- Narasi / Ringkasan -->
                <div>
                    <label for="narasi" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Narasi Berita / Ringkasan</label>
                    <textarea name="narasi" id="narasi" rows="4" required
                              class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                              placeholder="Ketik narasi singkat atau keterangan berita yang diterbitkan...">{{ old('narasi') }}</textarea>
                </div>

                <!-- Dokumen Bukti (Screenshot / PDF) -->
                <div>
                    <label for="file_dokumen" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Dokumen Bukti Tayang (PDF/Gambar)</label>
                    <input type="file" name="file_dokumen" id="file_dokumen" accept=".pdf,image/*,.doc,.docx"
                           class="block w-full text-sm text-slate-500 border border-slate-200 rounded-2xl file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer" />
                    <p class="text-[10px] text-slate-400 mt-1.5"><i class="fa-solid fa-circle-info mr-1"></i>Format yang didukung: PDF, JPG, PNG, DOCX (Maksimal 10MB)</p>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold text-sm hover:from-teal-600 hover:to-emerald-600 transition duration-300 shadow-md shadow-teal-500/20 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Kirim Laporan Berita</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: List & Search (7 cols on lg) -->
    <div class="lg:col-span-7 space-y-6">
        
        <!-- Filter Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
            <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-sliders text-teal-500"></i>
                <span>Filter & Pencarian</span>
            </h3>
            
            <form action="{{ route('news.index') }}" method="GET" class="space-y-4">
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <div class="w-full sm:w-1/3">
                        <select name="kategori" onchange="this.form.submit()"
                                class="w-full rounded-xl border border-slate-200 py-2.5 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
                            <option value="">Semua Kategori</option>
                            <option value="Kegiatan" {{ request('kategori') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan Kemenhaj</option>
                            <option value="Pengumuman" {{ request('kategori') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            <option value="Edukasi" {{ request('kategori') == 'Edukasi' ? 'selected' : '' }}>Edukasi</option>
                            <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                               placeholder="Cari judul berita atau ringkasan...">
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" class="bg-slate-800 text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-900 transition">
                            Cari
                        </button>
                        @if(request()->anyFilled(['search', 'kategori']))
                            <a href="{{ route('news.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table List -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm shadow-slate-100/50 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Daftar Laporan Berita Tayang</h3>
                <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-full font-bold">
                    Total: {{ $newsReports->total() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse hidden md:table">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 text-[10px] font-semibold uppercase tracking-wider">
                            <th class="py-4 px-6">Berita</th>
                            <th class="py-4 px-6">Info Publikasi</th>
                            <th class="py-4 px-6">Dokumen</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($newsReports as $report)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 space-y-1.5 max-w-[280px]">
                                <div class="font-bold text-slate-900 text-sm leading-snug">{{ $report->judul }}</div>
                                <div class="text-slate-500 text-[11px] line-clamp-2" title="{{ $report->narasi }}">{{ $report->narasi }}</div>
                            </td>
                            <td class="py-4 px-6 space-y-1">
                                <span class="bg-teal-50 text-teal-600 font-bold px-2 py-0.5 rounded text-[10px]">{{ $report->kategori }}</span>
                                <div class="text-[10px] text-slate-500 font-medium">
                                    <i class="fa-regular fa-calendar-check text-slate-400 mr-0.5"></i> 
                                    {{ \Carbon\Carbon::parse($report->tanggal_tayang)->translatedFormat('d M Y') }}
                                </div>
                                @if($report->link_berita)
                                <a href="{{ $report->link_berita }}" target="_blank" 
                                   class="inline-flex items-center text-teal-600 hover:text-teal-800 font-bold text-[10px] mt-1 group">
                                    <i class="fa-solid fa-arrow-up-right-from-square mr-1 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5"></i> Kunjungi Link
                                </a>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($report->file_dokumen)
                                <a href="{{ asset('storage/' . $report->file_dokumen) }}" target="_blank" 
                                   class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-medium transition">
                                    <i class="fa-solid fa-file-arrow-down text-teal-500 text-xs"></i>
                                    <span>Unduh Bukti</span>
                                </a>
                                @else
                                <span class="text-slate-400 text-[10px] font-medium italic">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('news.destroy', $report) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan berita ini?')">
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
                            <td colspan="4" class="py-8 text-center text-slate-400 font-medium">
                                <i class="fa-regular fa-folder-open text-3xl mb-2 block text-slate-300"></i>
                                Belum ada laporan berita tayang yang didata.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Card List (Laporan Berita khusus HP/Tablet) -->
                <div class="grid grid-cols-1 gap-4 p-4 md:hidden">
                    @forelse($newsReports as $report)
                    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 space-y-3 relative">
                        <div class="space-y-1">
                            <span class="bg-teal-50 text-teal-600 font-bold px-2 py-0.5 rounded text-[10px] uppercase inline-block mb-1">
                                {{ $report->kategori }}
                            </span>
                            <h4 class="font-bold text-slate-900 text-sm leading-snug">{{ $report->judul }}</h4>
                            <p class="text-slate-500 text-[11px] leading-relaxed pt-1">{{ $report->narasi }}</p>
                        </div>
                        
                        <div class="text-xs space-y-2 border-t border-slate-200/60 pt-3 text-slate-600">
                            <div class="flex items-center space-x-1.5 text-[10px]">
                                <i class="fa-regular fa-calendar-check text-slate-400 flex-shrink-0 w-3"></i>
                                <span>Tayang: <strong>{{ \Carbon\Carbon::parse($report->tanggal_tayang)->translatedFormat('d M Y') }}</strong></span>
                            </div>
                            
                            @if($report->link_berita || $report->file_dokumen)
                            <div class="flex flex-wrap gap-2 pt-1">
                                @if($report->link_berita)
                                <a href="{{ $report->link_berita }}" target="_blank" 
                                   class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl bg-teal-50 border border-teal-100 text-teal-600 hover:bg-teal-100 font-bold text-[10px] transition">
                                    <i class="fa-solid fa-link text-xs"></i>
                                    <span>Buka Link</span>
                                </a>
                                @endif

                                @if($report->file_dokumen)
                                <a href="{{ asset('storage/' . $report->file_dokumen) }}" target="_blank" 
                                   class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-600 hover:bg-slate-200 font-bold text-[10px] transition">
                                    <i class="fa-solid fa-file-arrow-down text-xs"></i>
                                    <span>Unduh Bukti</span>
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-end border-t border-slate-200/60 pt-3">
                            <form action="{{ route('news.destroy', $report) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold flex items-center space-x-1 py-1.5 px-2 rounded-lg hover:bg-rose-50 text-[11px]">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                    <span>Hapus Laporan</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-slate-400 font-medium py-6">
                        <i class="fa-regular fa-folder-open text-2xl mb-1 block text-slate-300"></i>
                        Belum ada laporan berita tayang yang didata.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination wrapper -->
            @if($newsReports->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $newsReports->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
