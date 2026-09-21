@extends('layouts.app')

@section('title', 'Buku Tamu Pengunjung')
@section('page_title', 'Buku Tamu / Pengunjung')

@section('content')
<div class="space-y-6" x-data="{ currentTab: '{{ $tab }}' }">
    
    <!-- Top Bar: Switcher Tab Register Harian vs Rekapitulasi Bulanan -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-4 shadow-sm shadow-slate-100/50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-2 bg-slate-100 p-1.5 rounded-2xl w-full sm:w-auto">
            <button type="button" @click="currentTab = 'daily'"
                    class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-xs font-bold transition duration-200 flex items-center justify-center space-x-2"
                    :class="currentTab === 'daily' ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                <i class="fa-solid fa-clipboard-user"></i>
                <span>Register & Input Harian</span>
            </button>
            <button type="button" @click="currentTab = 'monthly'"
                    class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl text-xs font-bold transition duration-200 flex items-center justify-center space-x-2"
                    :class="currentTab === 'monthly' ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                <i class="fa-solid fa-calendar-week"></i>
                <span>Rekapitulasi Bulanan</span>
            </button>
        </div>

        <div class="flex items-center space-x-3 text-xs text-slate-500">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-teal-50 text-teal-700 font-semibold border border-teal-100">
                <i class="fa-regular fa-clock mr-1.5 text-teal-500"></i>
                Hari ini: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- ================= TAB 1: REGISTER & INPUT HARIAN ================= -->
    <div x-show="currentTab === 'daily'" class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="visitorForm()">
        
        <!-- Left Column: Form Input (5 cols on lg) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <i class="fa-solid fa-user-plus text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Tambah Pengunjung</h3>
                        <p class="text-xs text-slate-500">Isi data pelayanan secara real-time</p>
                    </div>
                </div>

                <form action="{{ route('visitors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    
                    <!-- Input Nama -->
                    <div>
                        <label for="nama" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" name="nama" id="nama" required value="{{ old('nama') }}"
                                   class="w-full rounded-2xl border border-slate-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                                   placeholder="Nama lengkap pengunjung">
                        </div>
                    </div>

                    <!-- Input No HP -->
                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nomor HP / WhatsApp</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <input type="text" name="no_hp" id="no_hp" required value="{{ old('no_hp') }}"
                                   class="w-full rounded-2xl border border-slate-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                                   placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <!-- Kelompok Usia & Keperluan Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="kelompok_usia" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Kelompok Usia</label>
                            <select name="kelompok_usia" id="kelompok_usia" required
                                    class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white">
                                <option value="" disabled selected>Pilih Usia</option>
                                <option value="Anak-anak" {{ old('kelompok_usia') == 'Anak-anak' ? 'selected' : '' }}>Anak-anak (<12)</option>
                                <option value="Remaja" {{ old('kelompok_usia') == 'Remaja' ? 'selected' : '' }}>Remaja (12-18)</option>
                                <option value="Dewasa" {{ old('kelompok_usia') == 'Dewasa' ? 'selected' : '' }}>Dewasa (19-59)</option>
                                <option value="Lansia" {{ old('kelompok_usia') == 'Lansia' ? 'selected' : '' }}>Lansia (≥60)</option>
                            </select>
                        </div>

                        <div>
                            <label for="keperluan" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Keperluan</label>
                            <select name="keperluan" id="keperluan" required
                                    class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white">
                                <option value="" disabled selected>Pilih Keperluan</option>
                                <option value="pendaftaran" {{ old('keperluan') == 'pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                                <option value="konsultasi" {{ old('keperluan') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                                <option value="pelimpahan" {{ old('keperluan') == 'pelimpahan' ? 'selected' : '' }}>Pelimpahan</option>
                                <option value="pembatalan" {{ old('keperluan') == 'pembatalan' ? 'selected' : '' }}>Pembatalan</option>
                                <option value="lainnya" {{ old('keperluan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <!-- Input Alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Alamat Domisili</label>
                        <div class="relative">
                            <span class="absolute top-3.5 left-0 flex items-center pl-4 text-slate-400">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <textarea name="alamat" id="alamat" rows="2" required
                                      class="w-full rounded-2xl border border-slate-200 py-3 pl-11 pr-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                                      placeholder="Alamat lengkap pengunjung">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <!-- Tingkat Kepuasan (Rating Bintang) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tingkat Kepuasan Pelayanan</label>
                        <div class="flex items-center space-x-2 bg-slate-50 border border-slate-100 rounded-2xl p-3">
                            <template x-for="i in 5">
                                <button type="button" @click="rating = i" class="text-2xl focus:outline-none transition-transform hover:scale-110">
                                    <i class="fa-solid fa-star transition-colors" :class="i <= rating ? 'text-amber-400' : 'text-slate-200'"></i>
                                </button>
                            </template>
                            <span class="text-xs font-bold text-slate-600 ml-3" x-text="getRatingLabel(rating)"></span>
                        </div>
                        <input type="hidden" name="tingkat_kepuasan" :value="rating">
                    </div>

                    <!-- Pilihan Sumber Foto: Upload / Webcam -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Dokumentasi Foto Pengunjung</label>
                        
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <button type="button" @click="photoMode = 'upload'; stopWebcam()" 
                                    :class="photoMode === 'upload' ? 'bg-teal-50 text-teal-700 border-teal-200 font-bold' : 'bg-white text-slate-600 border-slate-200'"
                                    class="py-2 px-3 text-xs rounded-xl border flex items-center justify-center space-x-2 transition">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span>Upload File</span>
                            </button>
                            <button type="button" @click="photoMode = 'webcam'; startWebcam()" 
                                    :class="photoMode === 'webcam' ? 'bg-teal-50 text-teal-700 border-teal-200 font-bold' : 'bg-white text-slate-600 border-slate-200'"
                                    class="py-2 px-3 text-xs rounded-xl border flex items-center justify-center space-x-2 transition">
                                <i class="fa-solid fa-camera"></i>
                                <span>Foto Kamera Langsung</span>
                            </button>
                        </div>

                        <!-- Mode 1: Upload File -->
                        <div x-show="photoMode === 'upload'">
                            <input type="file" name="foto_file" id="foto_file" accept="image/*"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-slate-200 rounded-2xl cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WebP (Maks. 5MB)</p>
                        </div>

                        <!-- Mode 2: Webcam / Kamera Langsung -->
                        <div x-show="photoMode === 'webcam'" class="space-y-3">
                            <div class="relative w-full aspect-video bg-slate-900 rounded-2xl overflow-hidden flex items-center justify-center border border-slate-200">
                                <video id="webcam" autoplay playsinline class="w-full h-full object-cover -scale-x-100" x-show="!capturedPhoto"></video>
                                <img :src="capturedPhoto" class="w-full h-full object-cover" x-show="capturedPhoto">
                                <div x-show="cameraError" class="absolute inset-0 bg-slate-900/90 flex items-center justify-center p-4 text-center">
                                    <p class="text-xs text-rose-400 font-medium" x-text="cameraError"></p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <button type="button" x-show="!capturedPhoto" @click="capturePhoto()" 
                                        class="w-full py-2.5 px-4 rounded-xl bg-teal-500 text-white font-bold text-xs hover:bg-teal-600 transition shadow-sm flex items-center justify-center space-x-1.5">
                                    <i class="fa-solid fa-camera"></i>
                                    <span>Ambil Foto</span>
                                </button>
                                <button type="button" x-show="capturedPhoto" @click="resetCapture()"
                                        class="w-full py-2.5 px-4 rounded-xl bg-rose-500 text-white font-bold text-xs hover:bg-rose-600 transition shadow-sm">
                                    <i class="fa-solid fa-rotate-left mr-1.5"></i> Ambil Ulang
                                </button>
                            </div>

                            <input type="hidden" name="foto_captured" x-model="capturedPhoto">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-semibold text-sm hover:from-teal-600 hover:to-emerald-600 transition duration-300 shadow-md shadow-teal-500/20 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-floppy-disk text-base"></i>
                        <span>Simpan Data Pengunjung</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: List & Filters (7 cols on lg) -->
        <div class="lg:col-span-7 space-y-6" x-data="{ modalOpen: false, activePhoto: '' }">
            
            <!-- Filter Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center space-x-2">
                    <i class="fa-solid fa-sliders text-teal-500"></i>
                    <span>Filter & Pencarian Register Tamu</span>
                </h3>
                
                <form action="{{ route('visitors.index') }}" method="GET" class="space-y-4">
                    <input type="hidden" name="tab" value="daily">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Tanggal Kunjungan</label>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()"
                                   class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Kelompok Usia</label>
                            <select name="kelompok_usia" onchange="this.form.submit()"
                                    class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
                                <option value="">Semua Usia</option>
                                <option value="Anak-anak" {{ request('kelompok_usia') == 'Anak-anak' ? 'selected' : '' }}>Anak-anak</option>
                                <option value="Remaja" {{ request('kelompok_usia') == 'Remaja' ? 'selected' : '' }}>Remaja</option>
                                <option value="Dewasa" {{ request('kelompok_usia') == 'Dewasa' ? 'selected' : '' }}>Dewasa</option>
                                <option value="Lansia" {{ request('kelompok_usia') == 'Lansia' ? 'selected' : '' }}>Lansia</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Keperluan</label>
                            <select name="keperluan" onchange="this.form.submit()"
                                    class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
                                <option value="">Semua Keperluan</option>
                                <option value="pendaftaran" {{ request('keperluan') == 'pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                                <option value="konsultasi" {{ request('keperluan') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                                <option value="pelimpahan" {{ request('keperluan') == 'pelimpahan' ? 'selected' : '' }}>Pelimpahan</option>
                                <option value="pembatalan" {{ request('keperluan') == 'pembatalan' ? 'selected' : '' }}>Pembatalan</option>
                                <option value="lainnya" {{ request('keperluan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Tingkat Kepuasan</label>
                            <select name="tingkat_kepuasan" onchange="this.form.submit()"
                                    class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
                                <option value="">Semua Rating</option>
                                @for($i=5; $i>=1; $i--)
                                    <option value="{{ $i }}" {{ request('tingkat_kepuasan') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                                   placeholder="Cari nama, alamat, no hp...">
                        </div>
                        <button type="submit" class="bg-slate-800 text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-slate-900 transition">
                            Cari
                        </button>
                        @if(request()->anyFilled(['search', 'tanggal', 'kelompok_usia', 'keperluan', 'tingkat_kepuasan']))
                            <a href="{{ route('visitors.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm shadow-slate-100/50 overflow-hidden">
                
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">
                            Daftar Register Tamu
                            @if(request('tanggal'))
                                <span class="text-teal-600 font-bold">({{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d M Y') }})</span>
                            @endif
                        </h3>
                        <p class="text-[11px] text-slate-400">Rekapitulasi data pengunjung pelayanan front office harian</p>
                    </div>

                    <div class="flex items-center flex-wrap gap-2">
                        <!-- Cetak Register Tamu Harian PDF -->
                        <form action="{{ route('visitors.print-daily') }}" method="GET" target="_blank" class="flex items-center space-x-1.5">
                            <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}"
                                   class="rounded-xl border border-slate-200 py-1.5 px-2.5 text-xs font-semibold focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700 cursor-pointer"
                                   title="Pilih tanggal register tamu untuk dicetak">
                            <select name="petugas" 
                                    class="rounded-xl border border-slate-200 py-1.5 px-2.5 text-xs font-semibold focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700 cursor-pointer"
                                    title="Pilih Petugas Piket / FO">
                                <option value="M. Ainul Fikri">1. M. Ainul Fikri</option>
                                <option value="M Haidar Izzul haq">2. M Haidar Izzul haq</option>
                            </select>
                            <button type="submit" 
                                    class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition shadow-sm"
                                    title="Cetak register buku tamu dalam format PDF">
                                <i class="fa-solid fa-print text-teal-400"></i>
                                <span>Cetak Harian (PDF)</span>
                            </button>
                        </form>

                        <a href="{{ env('GOOGLE_SPREADSHEET_URL', 'https://docs.google.com/spreadsheets/d/1Mv_-ofOAwLDU_xD8Tvv_ySSoY_k-V1O5MEZu-gG6O-c/edit?usp=sharing') }}" target="_blank"
                           class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition">
                            <i class="fa-solid fa-file-excel text-emerald-600"></i>
                            <span>Spreadsheet</span>
                        </a>

                        <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1.5 rounded-xl font-bold">
                            Total: {{ $visitors->total() }}
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse hidden md:table">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 text-[10px] font-semibold uppercase tracking-wider">
                                <th class="py-4 px-6">Foto</th>
                                <th class="py-4 px-6">Pengunjung</th>
                                <th class="py-4 px-6">Keperluan</th>
                                <th class="py-4 px-6">Kepuasan</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($visitors as $visitor)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    @if($visitor->foto_pelayanan)
                                        <button type="button" @click="activePhoto = '{{ asset('storage/' . $visitor->foto_pelayanan) }}'; modalOpen = true" 
                                                class="h-10 w-10 rounded-xl overflow-hidden border border-slate-200 block hover:opacity-80 transition group relative">
                                            <img src="{{ asset('storage/' . $visitor->foto_pelayanan) }}" alt="Foto {{ $visitor->nama }}" class="h-full w-full object-cover">
                                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                <i class="fa-solid fa-magnifying-glass-plus text-white text-[10px]"></i>
                                            </div>
                                        </button>
                                    @else
                                        <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                            <i class="fa-regular fa-image text-sm"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-800">{{ $visitor->nama }}</div>
                                    <div class="text-[11px] text-slate-400 flex items-center space-x-1.5 mt-0.5">
                                        <i class="fa-solid fa-phone text-[9px]"></i>
                                        <span>{{ $visitor->no_hp }}</span>
                                        <span>•</span>
                                        <span>{{ $visitor->kelompok_usia }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-1 line-clamp-1 max-w-xs">
                                        {{ $visitor->alamat }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold tracking-wide uppercase
                                        {{ $visitor->keperluan == 'pendaftaran' ? 'bg-teal-50 text-teal-700 border border-teal-200' : '' }}
                                        {{ $visitor->keperluan == 'konsultasi' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                        {{ $visitor->keperluan == 'pelimpahan' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : '' }}
                                        {{ $visitor->keperluan == 'pembatalan' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                                        {{ $visitor->keperluan == 'lainnya' ? 'bg-slate-50 text-slate-700 border border-slate-200' : '' }}">
                                        {{ $visitor->keperluan }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-1">
                                        <span class="font-bold text-slate-700 mr-1.5">{{ $visitor->tingkat_kepuasan }}</span>
                                        <template x-for="i in 5">
                                            <i class="fa-solid fa-star text-[10px]" :class="i <= {{ $visitor->tingkat_kepuasan }} ? 'text-amber-400' : 'text-slate-200'"></i>
                                        </template>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-1">
                                        {{ $visitor->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <form action="{{ route('visitors.destroy', $visitor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengunjung ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-500 transition p-2 rounded-lg hover:bg-rose-50">
                                            <i class="fa-regular fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    <i class="fa-solid fa-inbox text-3xl text-slate-300 mb-2"></i>
                                    <p>Belum ada data pengunjung yang cocok dengan filter.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Mobile Card View -->
                    <div class="md:hidden divide-y divide-slate-100">
                        @forelse($visitors as $visitor)
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-3">
                                    @if($visitor->foto_pelayanan)
                                        <button type="button" @click="activePhoto = '{{ asset('storage/' . $visitor->foto_pelayanan) }}'; modalOpen = true" 
                                                class="h-12 w-12 rounded-xl overflow-hidden border border-slate-200 shrink-0">
                                            <img src="{{ asset('storage/' . $visitor->foto_pelayanan) }}" class="h-full w-full object-cover">
                                        </button>
                                    @else
                                        <div class="h-12 w-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 shrink-0">
                                            <i class="fa-regular fa-image text-sm"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm">{{ $visitor->nama }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $visitor->no_hp }} • {{ $visitor->kelompok_usia }}</div>
                                    </div>
                                </div>
                                <form action="{{ route('visitors.destroy', $visitor) }}" method="POST" onsubmit="return confirm('Hapus data pengunjung ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-500 p-2">
                                        <i class="fa-regular fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $visitor->alamat }}
                            </div>
                            <div class="flex items-center justify-between text-xs pt-1">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-teal-50 text-teal-700">
                                    {{ $visitor->keperluan }}
                                </span>
                                <div class="flex items-center space-x-1">
                                    <span class="font-bold text-slate-700">{{ $visitor->tingkat_kepuasan }}</span>
                                    <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-8 text-center text-slate-400">
                            Belum ada data pengunjung.
                        </div>
                        @endforelse
                    </div>
                </div>

                @if($visitors->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $visitors->links() }}
                </div>
                @endif
            </div>

            <!-- Modal Preview Foto Pelayanan -->
            <div x-show="modalOpen" x-cloak 
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                 @keydown.escape.window="modalOpen = false">
                <div class="relative bg-white rounded-3xl max-w-lg w-full p-4 shadow-2xl border border-slate-100" @click.away="modalOpen = false">
                    <button type="button" @click="modalOpen = false" 
                            class="absolute top-4 right-4 h-8 w-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <img :src="activePhoto" class="w-full h-auto rounded-2xl max-h-[70vh] object-contain">
                    <div class="p-4 text-center">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Foto Bukti Pelayanan Pengunjung</span>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- ================= TAB 2: REKAPITULASI BULANAN ================= -->
    <div x-show="currentTab === 'monthly'" class="space-y-6" x-data="{ modalOpenM: false, activePhotoM: '' }">
        
        @php
            $bulanNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
        @endphp

        <!-- Header Card: Selector Bulan, Tahun & Tombol Ekspor/Google Drive -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm shadow-slate-100/50 space-y-5">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800 flex items-center space-x-2">
                        <i class="fa-solid fa-calendar-check text-teal-600"></i>
                        <span>Rekapitulasi Bulanan Buku Tamu (PTSP)</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">
                        Rekapitulasi data pengunjung per 1 bulan untuk pencetakan PDF, unduhan spreadsheet, dan pengarsipan ke Google Drive
                    </p>
                </div>

                <!-- Tombol Aksi: Cetak PDF, Unduh CSV/Excel, Kirim ke Google Drive -->
                <div class="flex items-center flex-wrap gap-2.5">
                    <!-- Cetak PDF Bulanan -->
                    <form action="{{ route('visitors.print-monthly') }}" method="GET" target="_blank" class="inline-flex items-center space-x-1.5">
                        <input type="hidden" name="bulan" value="{{ $selectedMonth }}">
                        <input type="hidden" name="tahun" value="{{ $selectedYear }}">
                        <select name="petugas" 
                                class="rounded-xl border border-slate-200 py-2 px-2.5 text-xs font-semibold focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700 cursor-pointer"
                                title="Pilih Petugas Piket / FO">
                            <option value="M. Ainul Fikri">1. M. Ainul Fikri</option>
                            <option value="M Haidar Izzul haq">2. M Haidar Izzul haq</option>
                        </select>
                        <button type="submit" 
                                class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition duration-200 shadow-sm flex items-center space-x-2"
                                title="Cetak rekapitulasi buku tamu bulanan dalam format PDF">
                            <i class="fa-solid fa-print text-teal-400"></i>
                            <span>Cetak Rekap (PDF)</span>
                        </button>
                    </form>

                    <!-- Unduh Spreadsheet CSV/Excel -->
                    <a href="{{ route('visitors.export-monthly', ['bulan' => $selectedMonth, 'tahun' => $selectedYear]) }}"
                       class="px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs transition duration-200 shadow-sm flex items-center space-x-2">
                        <i class="fa-solid fa-file-excel text-emerald-200"></i>
                        <span>Unduh Spreadsheet</span>
                    </a>

                    <!-- Kirim Rekapitulasi ke Google Drive / Email seksiphupbg@gmail.com -->
                    <form action="{{ route('visitors.sync-drive-monthly') }}" method="POST" class="inline-block"
                          onsubmit="return confirm('Kirim file spreadsheet rekapitulasi bulan {{ $bulanNames[$selectedMonth] }} {{ $selectedYear }} ke Google Drive / Email seksiphupbg@gmail.com?')">
                        @csrf
                        <input type="hidden" name="bulan" value="{{ $selectedMonth }}">
                        <input type="hidden" name="tahun" value="{{ $selectedYear }}">
                        <button type="submit" 
                                class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition duration-200 shadow-sm flex items-center space-x-2"
                                title="Kirim spreadsheet rekap bulanan langsung ke email Google Drive seksiphupbg@gmail.com">
                            <i class="fa-brands fa-google-drive text-blue-200"></i>
                            <span>Kirim ke Google Drive</span>
                        </button>
                    </form>

                    <!-- Buka Google Spreadsheet Link -->
                    <a href="{{ env('GOOGLE_SPREADSHEET_URL', 'https://docs.google.com/spreadsheets/d/1Mv_-ofOAwLDU_xD8Tvv_ySSoY_k-V1O5MEZu-gG6O-c/edit?usp=sharing') }}" target="_blank"
                       class="px-3.5 py-2.5 rounded-xl border border-emerald-300 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs transition duration-200 shadow-sm flex items-center space-x-2"
                       title="Buka dokumen Google Spreadsheet di tab baru">
                        <i class="fa-solid fa-arrow-up-right-from-square text-emerald-600"></i>
                        <span>Buka Spreadsheet</span>
                    </a>
                </div>
            </div>

            <!-- Selector Bulan, Tahun & Filter Bulanan -->
            <form action="{{ route('visitors.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100">
                <input type="hidden" name="tab" value="monthly">
                
                <div>
                    <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Pilih Bulan</label>
                    <select name="bulan" onchange="this.form.submit()"
                            class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs font-semibold focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700">
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
                    <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Filter Keperluan</label>
                    <select name="keperluan_monthly" onchange="this.form.submit()"
                            class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs font-semibold focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700">
                        <option value="">Semua Keperluan</option>
                        <option value="pendaftaran" {{ request('keperluan_monthly') == 'pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                        <option value="konsultasi" {{ request('keperluan_monthly') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                        <option value="pelimpahan" {{ request('keperluan_monthly') == 'pelimpahan' ? 'selected' : '' }}>Pelimpahan</option>
                        <option value="pembatalan" {{ request('keperluan_monthly') == 'pembatalan' ? 'selected' : '' }}>Pembatalan</option>
                        <option value="lainnya" {{ request('keperluan_monthly') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Filter Usia</label>
                    <select name="kelompok_usia_monthly" onchange="this.form.submit()"
                            class="w-full rounded-xl border border-slate-200 py-2 px-3 text-xs font-semibold focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700">
                        <option value="">Semua Usia</option>
                        <option value="Anak-anak" {{ request('kelompok_usia_monthly') == 'Anak-anak' ? 'selected' : '' }}>Anak-anak</option>
                        <option value="Remaja" {{ request('kelompok_usia_monthly') == 'Remaja' ? 'selected' : '' }}>Remaja</option>
                        <option value="Dewasa" {{ request('kelompok_usia_monthly') == 'Dewasa' ? 'selected' : '' }}>Dewasa</option>
                        <option value="Lansia" {{ request('kelompok_usia_monthly') == 'Lansia' ? 'selected' : '' }}>Lansia</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Metric Cards: Ringkasan Bulan Terpilih -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Total Pengunjung</span>
                    <div class="h-8 w-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-800 mt-2">{{ $monthlyStats['total'] }}</div>
                <span class="text-[11px] text-teal-600 font-medium">Bulan {{ $bulanNames[$selectedMonth] }} {{ $selectedYear }}</span>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Rata-Rata Kepuasan</span>
                    <div class="h-8 w-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-amber-500 mt-2">{{ $monthlyStats['avg_satisfaction'] }} <span class="text-sm text-slate-400 font-normal">/ 5.0</span></div>
                <span class="text-[11px] text-slate-500 font-medium">Index Kepuasan Masyarakat</span>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Hari Layanan Aktif</span>
                    <div class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                </div>
                <div class="text-3xl font-black text-blue-600 mt-2">{{ $monthlyStats['hari_aktif'] }}</div>
                <span class="text-[11px] text-slate-400 font-medium">Hari tercatat kunjungan</span>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Keperluan Terbanyak</span>
                    <div class="h-8 w-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                </div>
                <div class="text-xl font-black text-slate-800 mt-2 truncate capitalize">{{ $monthlyStats['top_keperluan'] }}</div>
                <span class="text-[11px] text-slate-400 font-medium">Usia dominan: {{ $monthlyStats['top_usia'] }}</span>
            </div>
        </div>

        <!-- Sebaran Distribusi Keperluan & Kelompok Usia -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Breakdown Keperluan -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                <h4 class="text-xs font-bold text-slate-800 mb-3 flex items-center space-x-2">
                    <i class="fa-solid fa-chart-pie text-teal-500"></i>
                    <span>Sebaran Berdasarkan Keperluan Layanan</span>
                </h4>
                <div class="space-y-2.5">
                    @forelse($monthlyStats['keperluan'] as $keperluanKey => $count)
                        @php
                            $pct = $monthlyStats['total'] > 0 ? round(($count / $monthlyStats['total']) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-semibold text-slate-700 capitalize">{{ $keperluanKey }}</span>
                                <span class="font-bold text-slate-900">{{ $count }} orang ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-teal-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-3 text-center">Belum ada data keperluan tercatat.</p>
                    @endforelse
                </div>
            </div>

            <!-- Breakdown Kelompok Usia -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                <h4 class="text-xs font-bold text-slate-800 mb-3 flex items-center space-x-2">
                    <i class="fa-solid fa-chart-bar text-blue-500"></i>
                    <span>Sebaran Berdasarkan Kelompok Usia</span>
                </h4>
                <div class="space-y-2.5">
                    @forelse($monthlyStats['usia'] as $usiaKey => $count)
                        @php
                            $pctUsia = $monthlyStats['total'] > 0 ? round(($count / $monthlyStats['total']) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-semibold text-slate-700">{{ $usiaKey }}</span>
                                <span class="font-bold text-slate-900">{{ $count }} orang ({{ $pctUsia }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pctUsia }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-3 text-center">Belum ada data usia tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Google Drive Integration Info Banner -->
        <div class="bg-gradient-to-r from-blue-50 via-teal-50 to-emerald-50 rounded-2xl p-4 border border-blue-100/80 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-blue-600 shrink-0">
                    <i class="fa-brands fa-google-drive text-xl"></i>
                </div>
                <div>
                    <h5 class="text-xs font-bold text-slate-800">Tujuan Arsip Google Drive: <span class="text-blue-600 font-mono">seksiphupbg@gmail.com</span></h5>
                    <p class="text-[11px] text-slate-500">
                        Klik tombol <strong>"Kirim ke Google Drive"</strong> untuk mengirim file spreadsheet rekap bulanan ke inbox Google Drive resmi Seksi PHU Purbalingga.
                    </p>
                </div>
            </div>

            <a href="mailto:seksiphupbg@gmail.com" class="text-xs font-semibold text-blue-700 bg-white px-3 py-1.5 rounded-xl border border-blue-200 shadow-sm hover:bg-blue-50 transition">
                <i class="fa-solid fa-envelope mr-1"></i> Buka Gmail
            </a>
        </div>

        <!-- Tabel Rekap Pengunjung Bulanan -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm shadow-slate-100/50 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h4 class="text-sm font-bold text-slate-800">
                        Daftar Pengunjung Bulan {{ $bulanNames[$selectedMonth] }} {{ $selectedYear }}
                    </h4>
                    <p class="text-[11px] text-slate-400">Total data tercatat: {{ $monthlyVisitors->total() }} pengunjung</p>
                </div>

                <!-- Form Cari Cepat dalam Bulan Ini -->
                <form action="{{ route('visitors.index') }}" method="GET" class="flex items-center space-x-2">
                    <input type="hidden" name="tab" value="monthly">
                    <input type="hidden" name="bulan" value="{{ $selectedMonth }}">
                    <input type="hidden" name="tahun" value="{{ $selectedYear }}">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search_monthly" value="{{ request('search_monthly') }}"
                               class="rounded-xl border border-slate-200 py-1.5 pl-8 pr-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 bg-slate-50 text-slate-700 placeholder-slate-400"
                               placeholder="Cari nama/HP...">
                    </div>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold px-3 py-1.5 rounded-xl transition">
                        Cari
                    </button>
                    @if(request('search_monthly'))
                        <a href="{{ route('visitors.index', ['tab' => 'monthly', 'bulan' => $selectedMonth, 'tahun' => $selectedYear]) }}"
                           class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1.5 rounded-xl hover:bg-slate-200 transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-500 text-[10px] font-semibold uppercase tracking-wider">
                            <th class="py-4 px-6" style="width: 50px;">No</th>
                            <th class="py-4 px-6" style="width: 60px;">Foto</th>
                            <th class="py-4 px-6">Tanggal & Waktu</th>
                            <th class="py-4 px-6">Nama Pengunjung</th>
                            <th class="py-4 px-6">Alamat Lengkap</th>
                            <th class="py-4 px-6">Keperluan</th>
                            <th class="py-4 px-6">Kepuasan</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($monthlyVisitors as $index => $v)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 text-slate-400 font-semibold">
                                {{ $monthlyVisitors->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                @if($v->foto_pelayanan)
                                    <button type="button" @click="activePhotoM = '{{ asset('storage/' . $v->foto_pelayanan) }}'; modalOpenM = true" 
                                            class="h-10 w-10 rounded-xl overflow-hidden border border-slate-200 block hover:opacity-80 transition group relative">
                                        <img src="{{ asset('storage/' . $v->foto_pelayanan) }}" alt="Foto {{ $v->nama }}" class="h-full w-full object-cover">
                                    </button>
                                @else
                                    <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                        <i class="fa-regular fa-image text-sm"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">{{ $v->created_at->format('d/m/Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $v->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">{{ $v->nama }}</div>
                                <div class="text-[11px] text-slate-400">{{ $v->no_hp }} • {{ $v->kelompok_usia }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-slate-600 line-clamp-2 max-w-xs">{{ $v->alamat }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold tracking-wide uppercase
                                    {{ $v->keperluan == 'pendaftaran' ? 'bg-teal-50 text-teal-700 border border-teal-200' : '' }}
                                    {{ $v->keperluan == 'konsultasi' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                    {{ $v->keperluan == 'pelimpahan' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : '' }}
                                    {{ $v->keperluan == 'pembatalan' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                                    {{ $v->keperluan == 'lainnya' ? 'bg-slate-50 text-slate-700 border border-slate-200' : '' }}">
                                    {{ $v->keperluan }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-1">
                                    <span class="font-bold text-slate-700 mr-1">{{ $v->tingkat_kepuasan }}</span>
                                    <template x-for="i in 5">
                                        <i class="fa-solid fa-star text-[10px]" :class="i <= {{ $v->tingkat_kepuasan }} ? 'text-amber-400' : 'text-slate-200'"></i>
                                    </template>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('visitors.destroy', $v) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengunjung ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-500 transition p-2 rounded-lg hover:bg-rose-50">
                                        <i class="fa-regular fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                <i class="fa-solid fa-inbox text-3xl text-slate-300 mb-2"></i>
                                <p>Tidak ada data pengunjung pada bulan {{ $bulanNames[$selectedMonth] }} {{ $selectedYear }}.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($monthlyVisitors->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $monthlyVisitors->links() }}
            </div>
            @endif
        </div>

        <!-- Modal Preview Foto Bulanan -->
        <div x-show="modalOpenM" x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             @keydown.escape.window="modalOpenM = false">
            <div class="relative bg-white rounded-3xl max-w-lg w-full p-4 shadow-2xl border border-slate-100" @click.away="modalOpenM = false">
                <button type="button" @click="modalOpenM = false" 
                        class="absolute top-4 right-4 h-8 w-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img :src="activePhotoM" class="w-full h-auto rounded-2xl max-h-[70vh] object-contain">
                <div class="p-4 text-center">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Foto Bukti Pelayanan Pengunjung</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function visitorForm() {
        return {
            rating: 5,
            photoMode: 'upload',
            webcamStream: null,
            capturedPhoto: '',
            cameraError: '',
            
            getRatingLabel(r) {
                const labels = {
                    1: 'Sangat Buruk',
                    2: 'Buruk',
                    3: 'Cukup',
                    4: 'Puas',
                    5: 'Sangat Puas'
                };
                return labels[r] || 'Pilih Rating';
            },

            async startWebcam() {
                this.capturedPhoto = '';
                this.cameraError = '';
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'user', width: 640, height: 480 },
                        audio: false
                    });
                    this.webcamStream = stream;
                    const video = document.getElementById('webcam');
                    if (video) {
                        video.srcObject = stream;
                    }
                } catch (err) {
                    console.error("Camera access failed: ", err);
                    this.cameraError = 'Akses kamera gagal. Pastikan browser diizinkan menggunakan kamera.';
                }
            },

            stopWebcam() {
                if (this.webcamStream) {
                    this.webcamStream.getTracks().forEach(track => track.stop());
                    this.webcamStream = null;
                }
                this.capturedPhoto = '';
            },

            capturePhoto() {
                const video = document.getElementById('webcam');
                if (!video || !this.webcamStream) return;
                
                // Create canvas in memory
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                
                const ctx = canvas.getContext('2d');
                // Mirror image context for webcam capture
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Set data-uri base64 output
                this.capturedPhoto = canvas.toDataURL('image/jpeg', 0.95);
                
                // Stop webcam stream after capture to release resource
                this.stopWebcam();
            },

            resetCapture() {
                this.capturedPhoto = '';
                this.startWebcam();
            }
        };
    }
</script>
@endsection
