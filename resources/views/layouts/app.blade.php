<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Pelayanan') - Kemenhaj</title>
    
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- FontAwesome for Premium Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js (CDN) for UI reactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js (CDN) for premium dashboard analytics charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="h-full text-slate-800" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Sidebar Navigation -->
        <aside class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-900 text-white transition-transform duration-300 md:static md:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <!-- Brand Logo -->
            <div class="flex h-20 items-center justify-between px-6 border-b border-slate-800">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/95 p-1 shadow-lg shadow-teal-500/10">
                        <img src="{{ asset('images/logo-kemenhaj.svg') }}" alt="Logo Kemenhaj" class="h-8 w-8 object-contain">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight text-white leading-none">E-Pelayanan</h1>
                        <span class="text-xs font-semibold text-teal-400">Kementerian Haji dan Umrah Purbalingga</span>
                    </div>
                </a>
                <!-- Close sidebar button (mobile) -->
                <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white md:hidden">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Nav Items -->
            <nav class="flex-1 space-y-1.5 px-4 py-6 overflow-y-auto">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ Route::is('dashboard') ? 'bg-teal-500/10 text-teal-400 border border-teal-500/20 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}">
                    <i class="fa-solid fa-chart-line text-lg {{ Route::is('dashboard') ? 'text-teal-400' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span>Dashboard Awal</span>
                </a>
                
                <a href="{{ route('visitors.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ Route::is('visitors.*') ? 'bg-teal-500/10 text-teal-400 border border-teal-500/20 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}">
                    <i class="fa-solid fa-user-pen text-lg {{ Route::is('visitors.*') ? 'text-teal-400' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span>Buku Tamu / Pengunjung</span>
                </a>

                <a href="{{ route('news.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ Route::is('news.*') ? 'bg-teal-500/10 text-teal-400 border border-teal-500/20 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}">
                    <i class="fa-solid fa-newspaper text-lg {{ Route::is('news.*') ? 'text-teal-400' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span>Berita Tayang</span>
                </a>

                <a href="{{ route('daily-reports.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ Route::is('daily-reports.*') ? 'bg-teal-500/10 text-teal-400 border border-teal-500/20 font-medium' : 'text-slate-400 hover:bg-slate-800 hover:text-white border border-transparent' }}">
                    <i class="fa-solid fa-clipboard-check text-lg {{ Route::is('daily-reports.*') ? 'text-teal-400' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span>Laporan Kinerja Harian</span>
                </a>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="mt-4 border-t border-slate-800/80 pt-4">
                    @csrf
                    <button type="submit" 
                            class="flex w-full items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-rose-500/10 hover:text-rose-400 border border-transparent hover:border-rose-500/20 transition-all duration-200 group font-medium">
                        <i class="fa-solid fa-right-from-bracket text-lg text-slate-400 group-hover:text-rose-400 transition-colors"></i>
                        <span>Keluar Sesi</span>
                    </button>
                </form>
            </nav>

            <!-- Footer Sidebar -->
            <div class="p-4 border-t border-slate-800 text-xs text-slate-500 flex flex-col space-y-1">
                <span class="font-medium text-slate-400">&copy; {{ date('Y') }} Kemenhaj Purbalingga</span>
                <span>Sistem Pelayanan Terpadu</span>
            </div>
        </aside>

        <!-- Overlay for mobile when sidebar is open -->
        <div class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm md:hidden"
             x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-x-hidden">
            
            <!-- Topbar / Header -->
            <header class="flex h-20 items-center justify-between bg-white border-b border-slate-200/80 px-6 md:px-10">
                <div class="flex items-center space-x-4">
                    <!-- Toggle sidebar button (mobile) -->
                    <button @click="sidebarOpen = true" class="text-slate-600 hover:text-slate-900 md:hidden p-2 rounded-lg hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">@yield('page_title', 'Beranda')</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">Aplikasi E-Pelayanan & Pengarsipan Digital</p>
                    </div>
                </div>

                <!-- User profile / Info -->
                <div class="flex items-center space-x-4">
                    <div class="flex flex-col items-end text-right hidden sm:flex">
                        <span class="text-sm font-semibold text-slate-900">{{ Auth::user() ? Auth::user()->name : 'Admin Pelayanan' }}</span>
                        <span class="text-xs text-teal-600 font-medium">{{ Auth::user() ? Auth::user()->email : 'Petugas Front Office' }}</span>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-teal-50 border border-slate-200 flex items-center justify-center text-teal-600 font-bold">
                        {{ Auth::user() ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'AP' }}
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-6 md:p-10 space-y-8">
                
                <!-- Flash Session Message (Alpine.js auto-fade) -->
                @if(session('success'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)"
                     class="flex items-center justify-between p-4 mb-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm shadow-emerald-100/50"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="transform -translate-y-2 opacity-0"
                     x-transition:enter-end="transform translate-y-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @endif

                @if($errors->any())
                <div x-data="{ show: true }" 
                     x-show="show"
                     class="p-4 mb-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-2xl shadow-sm shadow-rose-100/50">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
                            <span class="text-sm font-bold">Terjadi Kesalahan Input:</span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-800">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <ul class="list-disc pl-8 text-xs space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Content Area Yield -->
                @yield('content')
                
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
