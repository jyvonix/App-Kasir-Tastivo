<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TASTIVO') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Smooth Scroll & Custom Scrollbar */
        html { scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Utilitas tambahan untuk hide scrollbar tapi tetap bisa scroll */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#F2F4F7] text-gray-800 antialiased h-screen overflow-hidden selection:bg-orange-100 selection:text-orange-600">
    
    <div class="flex h-full w-full p-3 lg:p-4 gap-4" x-data="{ sidebarOpen: false }">
        
        <!-- SIDEBAR (Floating Style) -->
        <aside class="fixed inset-y-4 left-4 z-50 w-[280px] bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] transform transition-transform duration-300 ease-[cubic-bezier(0.2,0.8,0.2,1)] lg:translate-x-0 lg:static lg:h-full flex flex-col border border-white/50"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-[120%] lg:translate-x-0'">
            @include('layouts.navigation')
        </aside>

        <!-- OVERLAY (Mobile) -->
        <div class="fixed inset-0 bg-gray-900/20 backdrop-blur-sm z-40 lg:hidden" 
             x-show="sidebarOpen" 
             x-transition.opacity 
             @click="sidebarOpen = false"></div>

        <!-- MAIN CONTENT (Rounded Container) -->
        <main class="flex-1 flex flex-col h-full overflow-hidden relative rounded-[2rem] bg-white shadow-[0_2px_20px_rgb(0,0,0,0.02)] border border-white/60">
            
            <!-- Mobile Toggle Header (Hanya muncul di mobile) -->
            <div class="lg:hidden flex items-center justify-between p-5 pb-0">
                <button @click="sidebarOpen = true" class="p-2 -ml-2 rounded-xl text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <span class="font-bold text-lg text-gray-800">Tastivo</span>
                <div class="w-8"></div> <!-- Spacer -->
            </div>

            <!-- Content Scroll Area -->
            <div class="flex-1 overflow-y-auto overflow-x-hidden p-6 lg:p-8 scroll-smooth">
                {{ $slot }}
            </div>
            
        </main>
    </div>

    <!-- GLOBAL NOTIFICATION SCRIPT (SweetAlert2) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Notifikasi Sukses
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    iconColor: '#f97316', // Orange-500
                    background: '#ffffff',
                    color: '#374151',
                    confirmButtonColor: '#f97316',
                    confirmButtonText: 'Oke, Mengerti',
                    customClass: {
                        popup: 'rounded-[2rem] shadow-2xl border-2 border-orange-100',
                        confirmButton: 'rounded-xl px-6 py-2 font-bold shadow-lg shadow-orange-200'
                    }
                });
            @endif

            // Notifikasi Error
            @if(session('error'))
                Swal.fire({
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    iconColor: '#ef4444', // Red-500
                    background: '#ffffff',
                    color: '#374151',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        popup: 'rounded-[2rem] shadow-2xl border-2 border-red-100',
                        confirmButton: 'rounded-xl px-6 py-2 font-bold shadow-lg shadow-red-200'
                    }
                });
            @endif
        });
    </script>
</body>
</html>