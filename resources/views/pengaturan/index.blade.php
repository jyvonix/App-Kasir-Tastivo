<x-app-layout>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
        <style>
            body { font-family: 'Outfit', sans-serif; }
            
            /* --- 1. BACKGROUND SYSTEM --- */
            .mesh-background {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
                background-color: #F1F5F9;
                background-image: 
                    radial-gradient(at 0% 0%, hsla(25, 100%, 88%, 1) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(210, 100%, 96%, 1) 0, transparent 50%);
            }

            /* --- 2. GLASS CARD --- */
            .glass-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
            }

            /* --- 3. CUSTOM SCROLLBAR --- */
            .custom-scroll::-webkit-scrollbar { width: 6px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255, 107, 53, 0.2); border-radius: 10px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255, 107, 53, 0.4); }

            /* --- 4. FLOATING ANIMATION --- */
            @keyframes float {
                0% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
                100% { transform: translateY(0px) rotate(0deg); }
            }
            .floating-icon { animation: float 6s ease-in-out infinite; }
            .floating-icon-delayed { animation: float 8s ease-in-out infinite; animation-delay: 2s; }
        </style>
    </head>

    <div class="mesh-background"></div>

    {{-- Decorative Elements --}}
    <div class="fixed top-20 right-[5%] opacity-10 floating-icon pointer-events-none hidden lg:block">
        <svg class="w-32 h-32 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11 9H9V2H7V9H5V2H3V9c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>
    </div>
    <div class="fixed bottom-20 left-[5%] opacity-10 floating-icon-delayed pointer-events-none hidden lg:block">
        <svg class="w-24 h-24 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
    </div>

    <div class="relative w-full py-4 px-2">
        <div class="max-w-7xl mx-auto">
            
            <!-- HEADER (Modern Minimalist Display) -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-10 relative z-10">
                <div class="space-y-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-[2px] bg-[#FF6B35] rounded-full"></span>
                        <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.3em]">System Configuration</span>
                    </div>
                    <h1 class="text-4xl lg:text-5xl text-gray-900 tracking-tight leading-tight">
                        <span class="font-medium">Pengaturan</span>
                        <span class="font-light text-gray-400">Toko</span>
                    </h1>
                    <p class="text-gray-400 font-medium tracking-wide pt-2">Kelola identitas, pajak, dan operasional TASTIVO.</p>
                </div>
                
                <div class="flex gap-4">
                    <div class="px-5 py-2 bg-white/60 backdrop-blur-md rounded-2xl border border-white shadow-sm flex items-center gap-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">System Active</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengaturan.update') }}" method="POST" class="mb-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- LEFT COLUMN: SHOP IDENTITY -->
                    <div class="lg:col-span-7 space-y-8">
                        <div class="glass-card rounded-[2.5rem] p-8 lg:p-10 relative overflow-hidden group">
                            {{-- Card Decor --}}
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-100 to-transparent rounded-bl-[2.5rem] -mr-8 -mt-8 pointer-events-none"></div>

                            <div class="flex items-center gap-6 mb-8 relative z-10">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-800 to-black flex items-center justify-center text-white shadow-xl shadow-gray-200">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <h3 class="font-black text-gray-900 text-2xl tracking-tight">Identitas Toko</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Informasi Dasar & Kontak</p>
                                </div>
                            </div>

                            <div class="space-y-6 relative z-10">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Toko / Outlet</label>
                                        <input type="text" name="nama_toko" value="{{ $setting->nama_toko }}" required
                                               class="w-full px-5 py-4 bg-white/50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:bg-white transition-all font-bold text-gray-800 placeholder-gray-300">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nomor Telepon</label>
                                        <input type="text" name="no_telepon" value="{{ $setting->no_telepon }}" 
                                               class="w-full px-5 py-4 bg-white/50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:bg-white transition-all font-bold text-gray-800 placeholder-gray-300">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Alamat Lengkap</label>
                                    <textarea name="alamat_toko" rows="3"
                                              class="w-full px-5 py-4 bg-white/50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:bg-white transition-all font-bold text-gray-800 placeholder-gray-300 resize-none">{{ $setting->alamat_toko }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- PAJAK SETTINGS -->
                        <div class="glass-card rounded-[2.5rem] p-8 lg:p-10 relative overflow-hidden group">
                            <div class="flex items-center gap-6 mb-8 relative z-10">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-xl shadow-blue-200">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="font-black text-gray-900 text-2xl tracking-tight">Konfigurasi Pajak</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">PPN & Service Charge</p>
                                </div>
                            </div>

                            <div class="relative z-10">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Tarif Pajak (PPN)</label>
                                <div class="relative group/input">
                                    <input type="number" step="0.1" name="pajak_persen" value="{{ $setting->pajak_persen }}" 
                                           class="w-full pl-8 pr-20 py-5 bg-white/80 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 transition-all font-black text-gray-900 text-3xl placeholder-gray-300 shadow-sm">
                                    <div class="absolute inset-y-0 right-0 pr-8 flex items-center pointer-events-none">
                                        <span class="text-blue-500 font-black text-2xl">%</span>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-start gap-3 p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-xs text-blue-800 font-medium leading-relaxed">
                                        Isi dengan <strong class="text-blue-600">0</strong> jika tidak ingin menerapkan pajak pada transaksi. Pajak akan dihitung otomatis saat checkout.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- SAVE BUTTON MAIN -->
                        <button type="submit" class="w-full py-5 bg-gray-900 text-white font-black rounded-[2rem] hover:bg-[#FF6B35] shadow-2xl hover:shadow-orange-200 transition-all transform hover:-translate-y-1 uppercase tracking-widest text-sm flex items-center justify-center gap-3 group">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Konfigurasi Toko
                        </button>
                    </div>

                    <!-- RIGHT COLUMN: SHIFT SETTINGS -->
                    <div class="lg:col-span-5 relative z-20">
                        <form action="{{ route('pengaturan.update_shifts') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="glass-card rounded-[2.5rem] p-8 lg:p-10 relative overflow-hidden h-full">
                                {{-- Card Decor --}}
                                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-[#FF6B35]/10 to-transparent rounded-bl-[4rem] -mr-10 -mt-10 pointer-events-none"></div>

                                <div class="flex items-center gap-6 mb-10 border-b border-gray-100 pb-8">
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#FF6B35] to-red-600 flex items-center justify-center text-white shadow-xl shadow-orange-200">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-gray-900 text-2xl tracking-tight">Jadwal Operasional</h3>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Shift Karyawan</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    @foreach($shifts as $shift)
                                        <div class="bg-white/50 rounded-3xl p-6 border border-gray-100 hover:bg-white hover:shadow-lg hover:shadow-orange-500/5 transition-all duration-300 group/card">
                                            
                                            <div class="flex items-center justify-between mb-4">
                                                <h4 class="font-black text-gray-800 text-lg capitalize flex items-center gap-3">
                                                    <span class="w-1.5 h-6 rounded-full bg-[#FF6B35]"></span>
                                                    {{ $shift->nama_shift }}
                                                </h4>
                                                <div class="p-2 bg-orange-50 rounded-xl text-orange-400 group-hover/card:text-[#FF6B35] transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Jam Masuk</label>
                                                    <input type="time" name="shifts[{{ $shift->id }}][jam_masuk]" value="{{ $shift->jam_masuk }}" 
                                                           class="w-full px-4 py-2.5 bg-white border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-200 font-bold text-gray-800 text-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Jam Pulang</label>
                                                    <input type="time" name="shifts[{{ $shift->id }}][jam_pulang]" value="{{ $shift->jam_pulang }}" 
                                                           class="w-full px-4 py-2.5 bg-white border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-200 font-bold text-gray-800 text-sm">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-8 pt-6 border-t border-gray-100">
                                    <button type="submit" class="w-full py-4 bg-gray-900 hover:bg-black text-white font-bold rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all text-sm uppercase tracking-wider flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        Update Jadwal
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil Disimpan!',
                text: '{{ session('success') }}',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#ffffff',
                iconColor: '#FF6B35',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-gray-100',
                    title: 'font-black text-gray-900 text-sm',
                    htmlContainer: 'font-medium text-gray-500 text-xs'
                }
            });
        @endif
    </script>
</x-app-layout>