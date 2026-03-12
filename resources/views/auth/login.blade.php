<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - TASTIVO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        tastivo: {
                            500: '#f97316',
                            600: '#ea580c',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .bg-pattern {
            background-color: #f97316;
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                              radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                              radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            background-size: 200% 200%;
            animation: gradient-animation 15s ease infinite;
        }
        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="font-sans antialiased h-screen w-full overflow-hidden flex items-center justify-center relative bg-gray-900">

    <!-- Premium Animated Background -->
    <div class="absolute inset-0 w-full h-full">
        <!-- Base Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-orange-600 via-red-600 to-amber-600 animate-gradient-xy"></div>
        
        <!-- Orbs -->
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-yellow-400 rounded-full mix-blend-multiply filter blur-[100px] opacity-50 animate-float"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-red-600 rounded-full mix-blend-multiply filter blur-[120px] opacity-60 animate-float" style="animation-delay: 2s"></div>
        <div class="absolute top-[40%] left-[40%] w-[400px] h-[400px] bg-orange-300 rounded-full mix-blend-overlay filter blur-[80px] opacity-40 animate-float" style="animation-delay: 4s"></div>
        
        <!-- Texture Overlay -->
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-md p-6">
        
        <!-- Glass Card -->
        <div class="glass-effect rounded-[2rem] shadow-2xl shadow-orange-900/50 p-8 md:p-10 border border-white/40 relative overflow-hidden transform transition-all hover:scale-[1.01] duration-500">
            
            <!-- Decor Top -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-yellow-400 via-orange-500 to-red-500"></div>

            <!-- Header -->
            <div class="text-center mb-10 relative">
                <!-- Floating Burger Icon -->
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-orange-100 to-orange-50 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/20 mb-6 group border-4 border-white/50">
                    <svg class="w-14 h-14 text-orange-600 group-hover:scale-110 transition-transform duration-300" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 8C19 8 19 4 12 4C5 4 5 8 5 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 15C21 13.8954 20.1046 13 19 13H5C3.89543 13 3 13.8954 3 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 18C21 19.1046 20.1046 20 19 20H5C3.89543 20 3 19.1046 3 18V16H21V18Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 4V2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 11H12.01" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <line x1="4" y1="10" x2="20" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>

                <h2 class="text-3xl font-black text-gray-800 tracking-tight">Selamat Datang</h2>
                <p class="text-gray-500 text-sm mt-2 font-medium">Masuk untuk mengelola restoran Anda</p>
            </div>

            <!-- Error Notification -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3 animate-pulse">
                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <h4 class="text-sm font-bold text-red-800">Login Gagal</h4>
                        <p class="text-xs text-red-600 mt-1">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPass: false, loading: false }">
                @csrf

                <!-- Username Input -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-600 uppercase tracking-wider ml-1">Username / Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" name="username" value="{{ old('username') }}" required autofocus
                               class="w-full pl-12 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none font-semibold text-gray-700 placeholder-gray-400"
                               placeholder="Masukkan ID Pengguna">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-600 uppercase tracking-wider ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input :type="showPass ? 'text' : 'password'" name="password" required
                               class="w-full pl-12 pr-12 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none font-semibold text-gray-700 placeholder-gray-400"
                               placeholder="Masukkan Password">
                        
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-orange-600 transition-colors cursor-pointer">
                            <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPass" style="display:none" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.57-3.19m1.77-2.23c.498-.556 1.063-1.053 1.685-1.478A10.032 10.032 0 0112 5c4.478 0 8.268 2.943 9.542 7-.433 1.377-1.136 2.622-2.03 3.67"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" @click="loading = true" class="w-full relative py-4 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold rounded-xl shadow-lg shadow-orange-500/30 transform transition-all hover:-translate-y-1 active:scale-95 flex justify-center items-center overflow-hidden group">
                    <div class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 skew-x-12 -ml-4"></div>
                    
                    <span x-show="!loading" class="relative flex items-center gap-2">
                        MASUK
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                    
                    <span x-show="loading" style="display:none" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4zm2 5.291A7.96 7.96 0 014 12H0c0 3.04 1.13 5.82 3 7.94l3-2.65z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400 font-medium">
                    &copy; 2025 Tastivo POS System. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>