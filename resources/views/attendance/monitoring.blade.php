<x-app-layout>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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

            /* --- 3. FLOATING ANIMATION --- */
            @keyframes float {
                0% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
                100% { transform: translateY(0px) rotate(0deg); }
            }
            .floating-icon { animation: float 6s ease-in-out infinite; }
            .floating-icon-delayed { animation: float 8s ease-in-out infinite; animation-delay: 2s; }

            /* --- 4. CUSTOM SCROLLBAR --- */
            .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255, 107, 53, 0.2); border-radius: 10px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255, 107, 53, 0.4); }
        </style>
    </head>

    <div class="mesh-background"></div>

    {{-- Decorative Elements --}}
    <div class="fixed top-20 right-[5%] opacity-10 floating-icon pointer-events-none hidden lg:block">
        <svg class="w-32 h-32 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11 9H9V2H7V9H5V2H3V9c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>
    </div>
    
    <div class="relative w-full">
        <!-- Refresh Otomatis -->
        <meta http-equiv="refresh" content="30"> 

        {{-- Background Decoration --}}
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-orange-50 via-white to-orange-50/50 rounded-3xl"></div>

        <div class="w-full px-2 py-4">
            
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-10 relative z-10">
                <div class="space-y-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-[2px] bg-[#FF6B35] rounded-full"></span>
                        <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.3em]">Live Monitoring System</span>
                    </div>
                    <h2 class="text-4xl lg:text-5xl text-gray-900 tracking-tight leading-tight">
                        <span class="font-medium">Monitoring</span>
                        <span class="font-light text-gray-400">Absensi</span>
                    </h2>
                    <p class="text-gray-400 font-medium flex items-center gap-2 text-sm tracking-wide pt-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse shadow-[0_0_10px_#22c55e]"></span>
                        LIVE LOG: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} 
                        <span class="mx-2 text-gray-300">|</span>
                        <span id="server-clock" class="text-gray-900 tabular-nums font-mono font-black">--:--:--</span>
                    </p>
                </div>

                <div class="flex gap-4">
                     <a href="{{ route('attendance.scan') }}" target="_blank" 
                        class="group flex items-center gap-2 px-6 py-4 bg-gray-900 text-white rounded-[1.5rem] font-black shadow-xl hover:bg-[#FF6B35] hover:shadow-orange-200 transition-all transform hover:-translate-y-1 text-sm tracking-widest uppercase">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Buka Scanner
                    </a>
                </div>
            </div>

            <!-- TABEL MODERN -->
            <div class="glass-card rounded-[2.5rem] border border-white/50 shadow-xl overflow-hidden relative z-10">
                <div class="overflow-x-auto custom-scroll">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gradient-to-r from-orange-50/50 to-red-50/50">
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">PEGAWAI</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">SHIFT</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">JAM MASUK</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">STATUS</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">JAM PULANG</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">KETERANGAN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white/60 backdrop-blur-md">
                            @forelse($logs as $log)
                            <tr class="hover:bg-orange-50/40 transition-colors group">
                                <!-- KOLOM NAMA -->
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <img src="{{ $log->user->foto_url }}" alt="" class="w-12 h-12 rounded-[1rem] object-cover border-2 border-white shadow-md group-hover:scale-110 transition-transform duration-300">
                                            @if($log->status == 'hadir')
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-gray-900 group-hover:text-[#FF6B35] transition-colors">{{ $log->user->nama }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">{{ $log->user->jabatan ?? ucfirst($log->user->role) }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- KOLOM SHIFT -->
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-gray-100/80 text-gray-600 text-[10px] font-black uppercase tracking-wider border border-gray-200 group-hover:bg-white group-hover:border-orange-200 transition-all">
                                        {{ $log->shift ? $log->shift->nama_shift : '-' }}
                                    </span>
                                </td>

                                <!-- KOLOM JAM MASUK -->
                                <td class="px-8 py-5 whitespace-nowrap">
                                    @if($log->waktu_masuk)
                                        <div class="text-sm font-bold text-gray-900 tabular-nums font-mono">
                                            {{ \Carbon\Carbon::parse($log->waktu_masuk)->format('H:i') }}
                                            <span class="text-gray-400 text-xs font-medium ml-1">WIB</span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-xs italic font-bold">--:--</span>
                                    @endif
                                </td>

                                <!-- KOLOM STATUS -->
                                <td class="px-8 py-5 whitespace-nowrap">
                                    @php
                                        $statusStyles = [
                                            'hadir' => 'bg-green-100 text-green-700 border border-green-200',
                                            'telat' => 'bg-red-100 text-red-700 border border-red-200',
                                            'sakit' => 'bg-purple-100 text-purple-700 border border-purple-200',
                                            'izin'  => 'bg-blue-100 text-blue-700 border border-blue-200',
                                        ];
                                        $style = $statusStyles[$log->status] ?? 'bg-gray-100 text-gray-700';
                                        $icon = match($log->status) {
                                            'hadir' => '✅', 'telat' => '⏰', 'sakit' => '🤒', 'izin' => '📩', default => '❓'
                                        };
                                        $label = $log->status == 'hadir' ? 'TEPAT WAKTU' : strtoupper($log->status);
                                    @endphp
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl {{ $style }} text-[10px] font-black tracking-widest uppercase shadow-sm">
                                        <span>{{ $icon }}</span> {{ $label }}
                                    </div>
                                </td>

                                <!-- KOLOM JAM PULANG -->
                                <td class="px-8 py-5 whitespace-nowrap">
                                    @if($log->waktu_pulang)
                                        <div class="text-sm font-bold text-gray-900 tabular-nums font-mono">
                                            {{ \Carbon\Carbon::parse($log->waktu_pulang)->format('H:i') }}
                                            <span class="text-gray-400 text-xs font-medium ml-1">WIB</span>
                                        </div>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400 italic bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">BELUM PULANG</span>
                                    @endif
                                </td>

                                <!-- KOLOM KETERANGAN -->
                                <td class="px-8 py-5">
                                    <div class="max-w-[200px]">
                                        <p class="text-xs text-gray-600 font-bold truncate" title="{{ $log->keterangan }}">{{ $log->keterangan }}</p>
                                        @if($log->bukti_foto)
                                            <a href="{{ asset('storage/' . $log->bukti_foto) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-600 hover:text-blue-800 hover:underline mt-1 bg-blue-50 px-2 py-0.5 rounded-md w-fit">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Lihat Bukti
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-dashed border-gray-200">
                                            <span class="text-4xl grayscale opacity-50">📭</span>
                                        </div>
                                        <h3 class="text-lg font-black text-gray-900">Belum ada data absensi</h3>
                                        <p class="text-sm font-medium text-gray-400 mt-1 max-w-xs mx-auto">Data kehadiran pegawai hari ini akan muncul secara otomatis di sini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        function updateServerClock() {
            const now = new Date();
            document.getElementById('server-clock').innerText = now.toLocaleTimeString('id-ID', { hour12: false });
        }
        setInterval(updateServerClock, 1000);
        updateServerClock();
    </script>
</x-app-layout>