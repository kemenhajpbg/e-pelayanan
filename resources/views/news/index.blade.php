@extends('layouts.app')

@section('title', 'Laporan Pembuatan Berita Tayang')
@section('page_title', 'Laporan Berita Tayang')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="newsAiGenerator()">
    
    <!-- Left Column: Input Form (5 cols on lg) -->
    <div class="lg:col-span-5 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
            
            <!-- Header Card -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <i class="fa-solid fa-wand-magic-sparkles text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pembuatan Berita</h3>
                        <p class="text-xs text-slate-500">Buat & publikasikan rilis berita Kemenhaj</p>
                    </div>
                </div>

                <!-- Mode Switcher Pill -->
                <div class="flex bg-slate-100 p-1 rounded-xl text-[11px] font-bold">
                    <button type="button" @click="mode = 'ai'"
                            class="px-3 py-1.5 rounded-lg transition duration-200 flex items-center space-x-1"
                            :class="mode === 'ai' ? 'bg-white text-teal-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'">
                        <i class="fa-solid fa-robot"></i>
                        <span>Bantu AI</span>
                    </button>
                    <button type="button" @click="mode = 'manual'"
                            class="px-3 py-1.5 rounded-lg transition duration-200 flex items-center space-x-1"
                            :class="mode === 'manual' ? 'bg-white text-teal-600 shadow-xs' : 'text-slate-500 hover:text-slate-800'">
                        <i class="fa-solid fa-keyboard"></i>
                        <span>Manual</span>
                    </button>
                </div>
            </div>

            <!-- ================= MODE 1: ASISTEN AI (CHATGPT / GEMINI) ================= -->
            <div x-show="mode === 'ai'" class="space-y-5">
                
                <!-- Info Banner AI -->
                <div class="bg-gradient-to-r from-teal-500/10 via-emerald-500/10 to-teal-500/10 border border-teal-500/20 rounded-2xl p-4 text-xs text-slate-700 space-y-1">
                    <div class="flex items-center space-x-2 font-bold text-teal-800">
                        <i class="fa-solid fa-brain text-teal-600 text-sm"></i>
                        <span>Asisten AI Pembuat Berita (Gemini / ChatGPT)</span>
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Cukup isi 3 poin di bawah ini, lalu AI akan otomatis menyusun <strong>Judul Menarik</strong> dan <strong>Isi Berita (~5 kalimat)</strong> yang sesuai kaidah jurnalistik resmi.
                    </p>
                </div>

                <!-- Input AI Step 1: 3 Kolom Isian Utama -->
                <div class="space-y-4 bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                    
                    <!-- 1. Kegiatan -->
                    <div>
                        <label for="ai_kegiatan" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center space-x-1.5">
                            <span class="h-5 w-5 rounded-full bg-teal-600 text-white flex items-center justify-center text-[10px] font-bold">1</span>
                            <span>Nama Kegiatan</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fa-solid fa-bullhorn text-xs"></i>
                            </span>
                            <input type="text" id="ai_kegiatan" x-model="aiKegiatan"
                                   class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-white placeholder-slate-400 transition"
                                   placeholder="Contoh: Bimbingan Manasik Haji Tingkat Kabupaten Purbalingga">
                        </div>
                    </div>

                    <!-- 2. Tempat dan Waktu -->
                    <div>
                        <label for="ai_tempat_waktu" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center space-x-1.5">
                            <span class="h-5 w-5 rounded-full bg-teal-600 text-white flex items-center justify-center text-[10px] font-bold">2</span>
                            <span>Tempat dan Waktu Pelaksanaan</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fa-solid fa-location-dot text-xs"></i>
                            </span>
                            <input type="text" id="ai_tempat_waktu" x-model="aiTempatWaktu"
                                   class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-white placeholder-slate-400 transition"
                                   placeholder="Contoh: Gedung IPHI Purbalingga, Selasa 15 September 2026 pukul 08.30 WIB">
                        </div>
                    </div>

                    <!-- 3. Isian Garis Besarnya -->
                    <div>
                        <label for="ai_garis_besar" class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center space-x-1.5">
                            <span class="h-5 w-5 rounded-full bg-teal-600 text-white flex items-center justify-center text-[10px] font-bold">3</span>
                            <span>Isian Garis Besarnya</span>
                        </label>
                        <textarea id="ai_garis_besar" x-model="aiGarisBesar" rows="3"
                                  class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-white placeholder-slate-400 transition"
                                  placeholder="Contoh: Dihadiri 450 calon jemaah, materi seputar rukun dan wajib haji, tata cara tawaf dan sai, pemeriksaan kesehatan lansia, serta pembagian buku panduan."></textarea>
                    </div>

                    <!-- Pilihan Provider & Kunci API Opsional -->
                    <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase">Engine:</label>
                            <select x-model="aiProvider" class="rounded-lg border border-slate-200 py-1 px-2 text-[11px] font-semibold bg-white text-slate-700 focus:outline-none focus:border-teal-500">
                                <option value="auto">Auto (Gemini / ChatGPT / Smart Engine)</option>
                                <option value="gemini">Google Gemini AI</option>
                                <option value="chatgpt">ChatGPT (OpenAI)</option>
                            </select>
                        </div>
                        <button type="button" @click="showApiKeyInput = !showApiKeyInput" class="text-[10px] text-teal-600 hover:text-teal-700 font-bold">
                            <span x-text="showApiKeyInput ? 'Tutup API Key' : '+ API Key'"></span>
                        </button>
                    </div>

                    <!-- Input API Key jika ingin kustom -->
                    <div x-show="showApiKeyInput" class="pt-1" x-cloak>
                        <input type="password" x-model="customApiKey"
                               placeholder="Masukkan Gemini atau OpenAI API Key (Opsional)..."
                               class="w-full rounded-xl border border-slate-200 py-1.5 px-3 text-[11px] bg-white focus:outline-none focus:border-teal-500">
                    </div>

                    <!-- Error Alert AI -->
                    <div x-show="aiError" class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-[11px] text-rose-600 font-semibold" x-cloak>
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                        <span x-text="aiError"></span>
                    </div>

                    <!-- Tombol Generate AI -->
                    <button type="button" @click="generateNews()" :disabled="isLoading"
                            class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-600 hover:to-emerald-600 text-white font-bold text-xs transition duration-200 shadow-md shadow-teal-500/20 flex items-center justify-center space-x-2 disabled:opacity-50">
                        <template x-if="!isLoading">
                            <span class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                <span>Buat Judul & Isi Berita dengan AI</span>
                            </span>
                        </template>
                        <template x-if="isLoading">
                            <span class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                                <span>Menyusun Berita via AI...</span>
                            </span>
                        </template>
                    </button>
                </div>

                <!-- Hasil Generate & Form Rekam Berita -->
                <div x-show="isSuccessGenerated" class="space-y-4 pt-2 border-t border-slate-100" x-cloak>
                    
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-teal-50 text-teal-700 text-[10px] font-bold border border-teal-100">
                            <i class="fa-solid fa-check-circle mr-1 text-teal-600"></i>
                            Dihasilkan oleh: <span class="ml-1" x-text="generatedProvider"></span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium">
                            <i class="fa-regular fa-file-lines mr-0.5"></i> Standar: ~5 Kalimat
                        </span>
                    </div>

                    <!-- Form Simpan & Rekam ke Database -->
                    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <!-- Judul Berita (Hasil AI) -->
                        <div>
                            <label for="ai_form_judul" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Judul Berita (Hasil AI - Dapat Diedit)</label>
                            <input type="text" name="judul" id="ai_form_judul" required x-model="formJudul"
                                   class="w-full rounded-2xl border border-teal-500/40 py-2.5 px-4 text-xs font-bold text-slate-800 bg-teal-50/20 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition">
                        </div>

                        <!-- Tanggal Tayang & Kategori Grid -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="ai_form_tanggal" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Tayang</label>
                                <input type="date" name="tanggal_tayang" id="ai_form_tanggal" required x-model="formTanggalTayang"
                                       class="w-full rounded-2xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                            </div>

                            <div>
                                <label for="ai_form_kategori" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kategori</label>
                                <select name="kategori" id="ai_form_kategori" required x-model="formKategori"
                                        class="w-full rounded-2xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                                    <option value="Kegiatan">Kegiatan Kemenhaj</option>
                                    <option value="Pengumuman">Pengumuman</option>
                                    <option value="Edukasi">Edukasi / Himbauan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <!-- Narasi Berita (~5 Kalimat Hasil AI) -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="ai_form_narasi" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Isi Kalimat Berita (~5 Kalimat)</label>
                                <span class="text-[10px] text-teal-600 font-bold bg-teal-50 px-2 py-0.5 rounded">Runtut 5W+1H</span>
                            </div>
                            <textarea name="narasi" id="ai_form_narasi" rows="5" required x-model="formNarasi"
                                      class="w-full rounded-2xl border border-teal-500/40 py-2.5 px-4 text-xs leading-relaxed text-slate-800 bg-teal-50/20 focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition"></textarea>
                        </div>

                        <!-- Link Berita Opsional -->
                        <div>
                            <label for="ai_form_link" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Link Publikasi Website / Medsos (Opsional)</label>
                            <input type="url" name="link_berita" id="ai_form_link" x-model="formLinkBerita"
                                   class="w-full rounded-2xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                                   placeholder="https://purbalingga.kemenhaj.go.id/berita/...">
                        </div>

                        <!-- Dokumen / Foto Bukti -->
                        <div>
                            <label for="ai_file_dokumen" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Upload Foto Dokumentasi / Bukti Tayang (Opsional)</label>
                            <input type="file" name="file_dokumen" id="ai_file_dokumen" accept=".pdf,image/*,.doc,.docx"
                                   class="block w-full text-xs text-slate-500 border border-slate-200 rounded-2xl file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer" />
                        </div>

                        <!-- Tombol Rekam & Simpan -->
                        <div class="pt-2 flex space-x-2">
                            <button type="submit" 
                                    class="flex-1 py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition duration-200 shadow-md flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-floppy-disk text-teal-400"></i>
                                <span>Rekam & Simpan Berita</span>
                            </button>
                            <button type="button" @click="generateNews()"
                                    class="py-3 px-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition" title="Generate ulang variasi lain">
                                <i class="fa-solid fa-rotate"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ================= MODE 2: INPUT MANUAL ================= -->
            <div x-show="mode === 'manual'" class="space-y-4">
                <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <!-- Judul Berita -->
                    <div>
                        <label for="manual_judul" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Judul Berita / Publikasi</label>
                        <input type="text" name="judul" id="manual_judul" required value="{{ old('judul') }}"
                               class="w-full rounded-2xl border border-slate-200 py-2.5 px-4 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                               placeholder="Judul tayangan berita">
                    </div>

                    <!-- Tanggal Tayang & Kategori Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="manual_tanggal_tayang" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Tayang</label>
                            <input type="date" name="tanggal_tayang" id="manual_tanggal_tayang" required value="{{ old('tanggal_tayang', date('Y-m-d')) }}"
                                   class="w-full rounded-2xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
                        </div>

                        <div>
                            <label for="manual_kategori" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kategori</label>
                            <select name="kategori" id="manual_kategori" required
                                    class="w-full rounded-2xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-700">
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
                        <label for="manual_link_berita" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Link Publikasi (Opsional)</label>
                        <input type="url" name="link_berita" id="manual_link_berita" value="{{ old('link_berita') }}"
                               class="w-full rounded-2xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                               placeholder="https://purbalingga.kemenhaj.go.id/read/...">
                    </div>

                    <!-- Narasi / Ringkasan -->
                    <div>
                        <label for="manual_narasi" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Narasi Berita</label>
                        <textarea name="narasi" id="manual_narasi" rows="4" required
                                  class="w-full rounded-2xl border border-slate-200 py-2.5 px-4 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                                  placeholder="Ketik narasi berita manual...">{{ old('narasi') }}</textarea>
                    </div>

                    <!-- Dokumen Bukti -->
                    <div>
                        <label for="manual_file_dokumen" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Dokumen Bukti Tayang</label>
                        <input type="file" name="file_dokumen" id="manual_file_dokumen" accept=".pdf,image/*,.doc,.docx"
                               class="block w-full text-xs text-slate-500 border border-slate-200 rounded-2xl file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-bold text-xs hover:from-teal-600 hover:to-emerald-600 transition duration-300 shadow-md shadow-teal-500/20 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Simpan Laporan Berita</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Right Column: List & Search (7 cols on lg) -->
    <div class="lg:col-span-7 space-y-6">
        
        <!-- Filter Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
            <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-sliders text-teal-500"></i>
                <span>Filter & Pencarian Berita</span>
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
                               placeholder="Cari judul berita atau narasi...">
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
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Daftar Berita Tayang Terdata</h3>
                    <p class="text-[11px] text-slate-400">Arsip publikasi resmi dan berita hasil asisten AI</p>
                </div>
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
                            <td class="py-4 px-6 space-y-1 whitespace-nowrap">
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
                            <td class="py-4 px-6 whitespace-nowrap">
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
                            <td class="py-4 px-6 text-center whitespace-nowrap">
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

                <!-- Mobile Card List -->
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

<script>
function newsAiGenerator() {
    return {
        mode: 'ai', // 'ai' atau 'manual'
        aiKegiatan: '',
        aiTempatWaktu: '',
        aiGarisBesar: '',
        aiProvider: 'auto',
        customApiKey: '',
        showApiKeyInput: false,
        isLoading: false,
        aiError: '',
        isSuccessGenerated: false,
        generatedProvider: '',

        // Form fields to record and save
        formJudul: '',
        formTanggalTayang: '{{ date('Y-m-d') }}',
        formKategori: 'Kegiatan',
        formLinkBerita: '',
        formNarasi: '',

        async generateNews() {
            if (!this.aiKegiatan.trim() || !this.aiTempatWaktu.trim() || !this.aiGarisBesar.trim()) {
                this.aiError = 'Mohon lengkapi 3 kolom isian berita (1. Kegiatan, 2. Tempat & Waktu, 3. Isian Garis Besar).';
                return;
            }

            this.isLoading = true;
            this.aiError = '';

            try {
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

                const response = await fetch("{{ route('news.generate-ai') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        kegiatan: this.aiKegiatan,
                        tempat_waktu: this.aiTempatWaktu,
                        garis_besar: this.aiGarisBesar,
                        provider: this.aiProvider,
                        custom_api_key: this.customApiKey || null
                    })
                });

                const res = await response.json();

                if (response.ok && res.success) {
                    this.formJudul = res.data.judul;
                    this.formNarasi = res.data.narasi;
                    this.formKategori = res.data.kategori || 'Kegiatan';
                    this.generatedProvider = res.data.provider || 'Asisten AI';
                    this.isSuccessGenerated = true;

                    // Scroll smooth ke form hasil
                    this.$nextTick(() => {
                        const judulEl = document.getElementById('ai_form_judul');
                        if (judulEl) {
                            judulEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            judulEl.focus();
                        }
                    });
                } else {
                    this.aiError = res.message || 'Gagal menghasilkan berita via AI. Silakan periksa kembali isian.';
                }
            } catch (err) {
                this.aiError = 'Terjadi kesalahan sistem: ' + err.message;
            } finally {
                this.isLoading = false;
            }
        }
    };
}
</script>
@endsection
