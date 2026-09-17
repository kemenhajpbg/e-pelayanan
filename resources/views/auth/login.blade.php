<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - E-Pelayanan Kemenhaj</title>
    
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center relative overflow-hidden p-4">

    <!-- Glowing Background Aurora Shapes -->
    <div class="absolute top-[-20%] left-[-20%] w-[60%] h-[60%] rounded-full bg-teal-500/10 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-20%] w-[60%] h-[60%] rounded-full bg-teal-600/10 blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md z-10">
        <!-- Logo & Branding -->
        <div class="text-center mb-8 space-y-2.5">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-white/95 p-2 shadow-xl shadow-teal-500/10 border border-white/20">
                <img src="{{ asset('images/logo-kemenhaj.svg') }}" alt="Logo Kemenhaj" class="h-12 w-12 object-contain">
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-white tracking-tight">E-Pelayanan</h2>
                <p class="text-xs text-slate-400 font-medium">Kementerian Haji dan Umrah Purbalingga</p>
            </div>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/60 border border-white/10 backdrop-blur-2xl rounded-3xl p-8 shadow-2xl shadow-slate-950/80">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-white">Selamat Datang</h3>
                <p class="text-xs text-slate-400">Silakan masuk menggunakan akun admin Anda</p>
            </div>

            <!-- Error Alerts -->
            @if($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-300 rounded-2xl p-4 mb-5 text-xs space-y-1">
                <div class="flex items-center space-x-2 font-bold mb-1">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Masalah Autentikasi</span>
                </div>
                <ul class="list-disc list-inside text-rose-200/90 leading-relaxed">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ url('/login') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Email / Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}" autofocus
                               class="w-full rounded-2xl bg-slate-950/50 border border-white/10 py-3.5 pl-11 pr-4 text-sm text-white focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-600 transition duration-200"
                               placeholder="admin@kemenhaj.pelayanan">
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Kata Sandi</label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password" id="password" required
                               class="w-full rounded-2xl bg-slate-950/50 border border-white/10 py-3.5 pl-11 pr-12 text-sm text-white focus:border-teal-500 focus:outline-none focus:ring-4 focus:ring-teal-500/10 placeholder-slate-600 transition duration-200"
                               placeholder="Masukkan sandi Anda">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 hover:text-white transition">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <label class="relative flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="sr-only peer">
                        <div class="h-5 w-5 rounded-md border border-white/10 bg-slate-950/50 flex items-center justify-center transition peer-checked:bg-teal-500 peer-checked:border-teal-500">
                            <i class="fa-solid fa-check text-[10px] text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                        </div>
                        <span class="text-xs text-slate-400 font-medium ml-2.5">Ingat perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-4 rounded-2xl bg-teal-500 text-slate-950 font-bold text-sm hover:bg-teal-400 hover:scale-[1.01] active:scale-[0.99] transition duration-200 shadow-xl shadow-teal-500/15 flex items-center justify-center space-x-2">
                    <span>Masuk ke Dashboard</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-[10px] text-slate-600 mt-8 leading-relaxed">
            &copy; {{ date('Y') }} Kementerian Haji dan Umrah Purbalingga.<br>
            Sistem Informasi Pelayanan Terpadu Front Office.
        </p>
    </div>

</body>
</html>
