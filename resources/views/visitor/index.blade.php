@extends('layouts.app')

@section('title', 'Buku Tamu Pengunjung')
@section('page_title', 'Buku Tamu / Pengunjung')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="visitorForm()">
    
    <!-- Left Column: Form Input (4 cols on lg) -->
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
                    <label for="alamat" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="2" required
                              class="w-full rounded-2xl border border-slate-200 py-3 px-4 text-sm focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-400 transition"
                              placeholder="Alamat lengkap...">{{ old('alamat') }}</textarea>
                </div>

                <!-- Tingkat Kepuasan (Rating Bintang) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tingkat Kepuasan Pelayanan</label>
                    <input type="hidden" name="tingkat_kepuasan" x-model="rating">
                    <div class="flex items-center space-x-2 bg-slate-50 border border-slate-100 p-3 rounded-2xl justify-center">
                        <template x-for="i in 5">
                            <button type="button" @click="rating = i" class="text-2xl transition duration-150 transform hover:scale-125 focus:outline-none"
                                    :class="i <= rating ? 'text-amber-400' : 'text-slate-300'">
                                <i class="fa-solid fa-star"></i>
                            </button>
                        </template>
                        <span class="text-xs font-bold ml-3 text-slate-500" x-text="getRatingLabel(rating)"></span>
                    </div>
                </div>

                <!-- Foto Pelayanan (Upload / Webcam) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Foto Pelayanan / Bukti</label>
                    
                    <!-- Toggle Type Upload -->
                    <div class="flex space-x-2 mb-3">
                        <button type="button" @click="photoMode = 'upload'; stopWebcam()"
                                class="flex-1 py-2 px-3 rounded-xl border text-xs font-bold transition duration-200"
                                :class="photoMode === 'upload' ? 'bg-teal-500 text-white border-teal-500 shadow-sm shadow-teal-500/20' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                            <i class="fa-solid fa-file-arrow-up mr-1.5"></i> Unggah File
                        </button>
                        <button type="button" @click="photoMode = 'camera'; startWebcam()"
                                class="flex-1 py-2 px-3 rounded-xl border text-xs font-bold transition duration-200"
                                :class="photoMode === 'camera' ? 'bg-teal-500 text-white border-teal-500 shadow-sm shadow-teal-500/20' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'">
                            <i class="fa-solid fa-camera mr-1.5"></i> Tangkap Kamera
                        </button>
                    </div>

                    <!-- UPLOAD FILE FIELD -->
                    <div x-show="photoMode === 'upload'" class="transition duration-150">
                        <input type="file" name="foto_file" id="foto_file" accept="image/*"
                               class="block w-full text-sm text-slate-500 border border-slate-200 rounded-2xl file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer" />
                    </div>

                    <!-- WEBCAM CAMERA CAPTURE -->
                    <div x-show="photoMode === 'camera'" class="space-y-3 transition duration-150" x-cloak>
                        
                        <!-- Webcam Video Stream / Captured Preview -->
                        <div class="relative rounded-2xl overflow-hidden bg-slate-900 aspect-video border border-slate-800 flex items-center justify-center">
                            
                            <!-- Video element for live feed -->
                            <video id="webcam" x-show="!capturedPhoto" class="w-full h-full object-cover transform scale-x-[-1]" autoplay playsinline></video>
                            
                            <!-- Img element for preview of captured base64 -->
                            <img x-show="capturedPhoto" :src="capturedPhoto" class="w-full h-full object-cover transform scale-x-[-1]" />
                            
                            <!-- Error state -->
                            <div x-show="cameraError" class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center text-slate-400">
                                <i class="fa-solid fa-triangle-exclamation text-3xl text-rose-500 mb-2"></i>
                                <span class="text-xs font-medium" x-text="cameraError"></span>
                            </div>
                        </div>

                        <!-- Webcam action buttons -->
                        <div class="flex space-x-2">
                            <button type="button" x-show="!capturedPhoto && webcamStream" @click="capturePhoto()"
                                    class="w-full py-2.5 px-4 rounded-xl bg-teal-500 text-white font-bold text-xs hover:bg-teal-600 transition shadow-sm">
                                <i class="fa-solid fa-circle-dot mr-1.5"></i> Ambil Foto
                            </button>
                            <button type="button" x-show="capturedPhoto" @click="resetCapture()"
                                    class="w-full py-2.5 px-4 rounded-xl bg-rose-500 text-white font-bold text-xs hover:bg-rose-600 transition shadow-sm">
                                <i class="fa-solid fa-rotate-left mr-1.5"></i> Ambil Ulang
                            </button>
                        </div>

                        <!-- Hidden input to store base64 string -->
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
                <span>Filter & Pencarian</span>
            </h3>
            
            <form action="{{ route('visitors.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <select name="kelompok_usia" onchange="this.form.submit()"
                                class="w-full rounded-xl border border-slate-200 py-2.5 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
                            <option value="">Semua Usia</option>
                            <option value="Anak-anak" {{ request('kelompok_usia') == 'Anak-anak' ? 'selected' : '' }}>Anak-anak</option>
                            <option value="Remaja" {{ request('kelompok_usia') == 'Remaja' ? 'selected' : '' }}>Remaja</option>
                            <option value="Dewasa" {{ request('kelompok_usia') == 'Dewasa' ? 'selected' : '' }}>Dewasa</option>
                            <option value="Lansia" {{ request('kelompok_usia') == 'Lansia' ? 'selected' : '' }}>Lansia</option>
                        </select>
                    </div>

                    <div>
                        <select name="keperluan" onchange="this.form.submit()"
                                class="w-full rounded-xl border border-slate-200 py-2.5 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
                            <option value="">Semua Keperluan</option>
                            <option value="pendaftaran" {{ request('keperluan') == 'pendaftaran' ? 'selected' : '' }}>Pendaftaran</option>
                            <option value="konsultasi" {{ request('keperluan') == 'konsultasi' ? 'selected' : '' }}>Konsultasi</option>
                            <option value="pelimpahan" {{ request('keperluan') == 'pelimpahan' ? 'selected' : '' }}>Pelimpahan</option>
                            <option value="pembatalan" {{ request('keperluan') == 'pembatalan' ? 'selected' : '' }}>Pembatalan</option>
                            <option value="lainnya" {{ request('keperluan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <select name="tingkat_kepuasan" onchange="this.form.submit()"
                                class="w-full rounded-xl border border-slate-200 py-2.5 px-3 text-xs focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition bg-white text-slate-600">
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
                    @if(request()->anyFilled(['search', 'kelompok_usia', 'keperluan', 'tingkat_kepuasan']))
                        <a href="{{ route('visitors.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm shadow-slate-100/50 overflow-hidden">
            
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
                <h3 class="text-sm font-bold text-slate-800">Daftar Buku Tamu hari ini</h3>
                <div class="flex items-center space-x-2">
                    @if(env('GOOGLE_SHEET_WEBHOOK_URL'))
                    <a href="https://docs.google.com/spreadsheets/d/1Mv_-ofOAwLDU_xD8Tvv_ySSoY_k-V1O5MEZu-gG6O-c/edit?usp=sharing" target="_blank"
                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition">
                        <i class="fa-solid fa-file-excel text-emerald-600"></i>
                        <span>Buka Spreadsheet</span>
                    </a>
                    @endif
                    <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-full font-bold">
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
                                    <button @click="activePhoto = '{{ asset('storage/' . $visitor->foto_pelayanan) }}'; modalOpen = true" 
                                            class="h-12 w-12 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 hover:opacity-85 transition transform hover:scale-105 focus:outline-none">
                                        <img src="{{ asset('storage/' . $visitor->foto_pelayanan) }}" class="h-full w-full object-cover">
                                    </button>
                                @else
                                    <div class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-image text-lg"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 space-y-1">
                                <div class="font-bold text-slate-900">{{ $visitor->nama }}</div>
                                <div class="text-[10px] text-slate-400 flex items-center space-x-1.5">
                                    <span class="bg-teal-50 text-teal-600 font-bold px-1.5 py-0.5 rounded">{{ $visitor->kelompok_usia }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $visitor->no_hp }}</span>
                                </div>
                                <div class="text-[10px] text-slate-500 max-w-[200px] truncate" title="{{ $visitor->alamat }}">
                                    <i class="fa-solid fa-location-dot text-slate-400 mr-0.5"></i> {{ $visitor->alamat }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="capitalize font-medium text-slate-700">{{ $visitor->keperluan }}</span>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $visitor->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex text-[10px] text-amber-400">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="{{ $i <= $visitor->tingkat_kepuasan ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                    @endfor
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 mt-0.5 block">{{ $visitor->tingkat_kepuasan }}/5</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('visitors.destroy', $visitor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                                Belum ada data pengunjung hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile Card List (Daftar Pengunjung khusus HP/Tablet) -->
                <div class="grid grid-cols-1 gap-4 p-4 md:hidden">
                    @forelse($visitors as $visitor)
                    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 space-y-3 relative">
                        <div class="flex items-center space-x-3">
                            @if($visitor->foto_pelayanan)
                                <button @click="activePhoto = '{{ asset('storage/' . $visitor->foto_pelayanan) }}'; modalOpen = true" 
                                        class="h-12 w-12 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $visitor->foto_pelayanan) }}" class="h-full w-full object-cover">
                                </button>
                            @else
                                <div class="h-12 w-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 flex-shrink-0">
                                    <i class="fa-solid fa-user text-lg"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 truncate">{{ $visitor->nama }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ $visitor->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="bg-teal-50 text-teal-600 font-bold px-2 py-0.5 rounded text-[10px] uppercase">
                                {{ $visitor->keperluan }}
                            </span>
                        </div>
                        
                        <div class="text-xs space-y-1.5 border-t border-slate-200/60 pt-3 text-slate-600">
                            <div class="flex items-start space-x-1.5">
                                <i class="fa-solid fa-location-dot text-slate-400 mt-0.5 flex-shrink-0 w-3"></i>
                                <span class="break-words">{{ $visitor->alamat }}</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-phone text-slate-400 flex-shrink-0 w-3"></i>
                                <span>{{ $visitor->no_hp }}</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-cake-candles text-slate-400 flex-shrink-0 w-3"></i>
                                <span>Kelompok Usia: <strong class="text-teal-600 font-bold">{{ $visitor->kelompok_usia }}</strong></span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-face-smile text-slate-400 flex-shrink-0 w-3"></i>
                                <span class="flex items-center">
                                    Rating: 
                                    <span class="flex text-[10px] text-amber-400 ml-1.5">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="{{ $i <= $visitor->tingkat_kepuasan ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                        @endfor
                                    </span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex justify-end border-t border-slate-200/60 pt-3">
                            <form action="{{ route('visitors.destroy', $visitor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-semibold flex items-center space-x-1 py-1.5 px-2 rounded-lg hover:bg-rose-50 text-[11px]">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                    <span>Hapus Data</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-slate-400 font-medium py-6">
                        <i class="fa-regular fa-folder-open text-2xl mb-1 block text-slate-300"></i>
                        Belum ada data pengunjung hari ini.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination wrapper -->
            @if($visitors->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $visitors->links() }}
            </div>
            @endif
        </div>

        <!-- Lightbox Image Modal (Alpine.js) -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md"
             x-show="modalOpen" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click.away="modalOpen = false">
            <div class="relative bg-white rounded-3xl overflow-hidden max-w-lg w-full shadow-2xl p-2 border border-slate-100">
                <button @click="modalOpen = false" class="absolute top-4 right-4 h-8 w-8 bg-slate-900/30 text-white rounded-full hover:bg-slate-900/60 transition flex items-center justify-center focus:outline-none">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img :src="activePhoto" class="w-full h-auto rounded-2xl max-h-[70vh] object-contain">
                <div class="p-4 text-center">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Foto Bukti Pelayanan</span>
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
