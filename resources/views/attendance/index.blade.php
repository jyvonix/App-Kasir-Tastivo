<x-app-layout>
    <div class="max-w-6xl mx-auto space-y-6">
        
        <!-- HEADER SECTION -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Presensi Kehadiran</h1>
                <p class="text-sm text-gray-500 font-medium">Kelola aktivitas jam kerja Anda hari ini.</p>
            </div>
            <!-- Tanggal Simple -->
            <div class="inline-flex items-center px-4 py-2 bg-white rounded-xl border border-gray-200 shadow-sm text-sm font-bold text-gray-700">
                <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span id="current-date-display">--</span>
            </div>
        </div>

        <!-- MAIN GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- 1. PROFILE & CLOCK CARD (Gradient Background Solid - No Blur Effect) -->
            <div class="lg:col-span-2 bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden">
                <!-- Aksen Garis Halus -->
                <div class="absolute top-0 right-0 w-full h-full opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, #ffffff 10px, #ffffff 11px);"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start md:items-center h-full">
                    <!-- Foto Profil -->
                    <div class="relative shrink-0">
                        <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&background=ea580c&color=fff' }}" 
                             class="w-24 h-24 rounded-2xl object-cover border-4 border-gray-700 shadow-xl bg-gray-700">
                        <div class="absolute -bottom-2 -right-2 bg-orange-500 text-white p-1.5 rounded-lg border-4 border-gray-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>

                    <!-- Info Pegawai -->
                    <div class="flex-1 min-w-0 space-y-4">
                        <div>
                            <span class="inline-block px-2 py-1 bg-orange-500/20 text-orange-400 text-[10px] font-black uppercase tracking-wider rounded mb-2">
                                {{ Auth::user()->role }}
                            </span>
                            <h2 class="text-3xl font-black leading-none truncate">{{ Auth::user()->nama }}</h2>
                            <p class="text-gray-400 text-sm font-medium mt-1">NIP: {{ str_pad(Auth::user()->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        
                        <!-- Jam Digital Stabil -->
                        <div class="pt-4 border-t border-gray-700/50 flex items-end gap-2">
                            <div class="text-5xl font-black tracking-tighter tabular-nums leading-none" id="attendance-clock">--:--</div>
                            <div class="text-xl font-bold text-orange-500 tabular-nums mb-1" id="attendance-seconds">00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. STATUS CARD (Simple White) -->
            <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm flex flex-col justify-center items-center text-center">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Status Hari Ini</p>

                @if($attendance)
                    @php
                        $statusColor = match($attendance->status) {
                            'hadir' => 'text-green-600 bg-green-50',
                            'telat' => 'text-red-600 bg-red-50',
                            'izin', 'sakit' => 'text-blue-600 bg-blue-50',
                            default => 'text-gray-600 bg-gray-50'
                        };
                        $statusLabel = strtoupper($attendance->status);
                    @endphp
                    
                    <div class="w-20 h-20 rounded-2xl {{ $statusColor }} flex items-center justify-center text-4xl mb-3 shadow-inner">
                        @if($attendance->status == 'hadir') 👏 @elseif($attendance->status == 'telat') ⚠️ @else 📝 @endif
                    </div>
                    
                    <h3 class="text-xl font-black text-gray-900">{{ $statusLabel }}</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">
                        Pukul {{ \Carbon\Carbon::parse($attendance->waktu_masuk)->format('H:i') }}
                    </p>
                @else
                    <div class="w-20 h-20 rounded-2xl bg-gray-100 flex items-center justify-center text-4xl mb-3 text-gray-400">
                        ⏳
                    </div>
                    <h3 class="text-xl font-black text-gray-900">BELUM ABSEN</h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Silakan check-in dahulu.</p>
                @endif
            </div>
        </div>

        <!-- ACTION BUTTONS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- SCAN QR CARD -->
            <a href="{{ route('attendance.scan') }}" class="group relative overflow-hidden rounded-3xl bg-orange-600 p-8 shadow-md transition-all hover:bg-orange-700 hover:shadow-lg hover:-translate-y-1">
                <!-- Pattern Background -->
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10 transition-transform group-hover:scale-150"></div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 text-2xl text-white shadow-inner">
                            📷
                        </div>
                        <h3 class="text-2xl font-black text-white">SCAN QR</h3>
                        <p class="mt-1 text-sm font-bold text-orange-100">Masuk & Pulang</p>
                    </div>
                    <div class="rounded-full bg-white/20 p-3 text-white transition-transform group-hover:rotate-45">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>
            </a>

            <!-- IZIN/SAKIT CARD -->
            <a href="{{ route('attendance.izin') }}" class="group relative overflow-hidden rounded-3xl bg-white border border-gray-200 p-8 shadow-sm transition-all hover:border-blue-300 hover:shadow-md hover:-translate-y-1">
                <div class="absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-blue-50 transition-transform group-hover:scale-150"></div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-2xl text-blue-600 shadow-sm">
                            🤒
                        </div>
                        <h3 class="text-2xl font-black text-gray-900">IZIN / SAKIT</h3>
                        <p class="mt-1 text-sm font-bold text-gray-400 group-hover:text-blue-600">Formulir Digital</p>
                    </div>
                    <div class="rounded-full bg-gray-50 p-3 text-gray-400 transition-transform group-hover:text-blue-600 group-hover:rotate-45">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                </div>
            </a>

        </div>

        <!-- SHIFT INFO (Clean Timeline) -->
        <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-8 w-1.5 rounded-full bg-orange-500"></div>
                <h3 class="text-lg font-black text-gray-900">Jadwal Shift</h3>
            </div>

            @if($currentShift)
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Kotak Nama Shift -->
                    <div class="bg-gray-50 rounded-2xl p-4 md:w-1/4 border border-gray-100">
                        <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Shift Aktif</span>
                        <p class="text-xl font-black text-gray-800 mt-1">{{ $currentShift->nama_shift }}</p>
                    </div>

                    <!-- Timeline -->
                    <div class="flex-1 bg-white border border-dashed border-gray-300 rounded-2xl p-4 flex items-center justify-between gap-4">
                        <div class="text-center">
                            <span class="block text-[10px] font-black uppercase text-gray-400">Masuk</span>
                            <span class="block text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($currentShift->jam_masuk)->format('H:i') }}</span>
                        </div>

                        <!-- Garis Tengah -->
                        <div class="flex-1 h-1 bg-gray-100 rounded-full relative">
                            <div class="absolute left-0 top-0 h-full w-1/2 bg-orange-200 rounded-full"></div> <!-- Simulasi progress -->
                            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-6 h-6 bg-orange-500 rounded-full border-4 border-white shadow-sm"></div>
                        </div>

                        <div class="text-center">
                            <span class="block text-[10px] font-black uppercase text-gray-400">Pulang</span>
                            <span class="block text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($currentShift->jam_pulang)->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            @else
                 <div class="py-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-sm font-bold text-gray-400">Tidak ada jadwal shift hari ini.</p>
                </div>
            @endif
        </div>

        <!-- RIWAYAT PENGAJUAN IZIN/SAKIT PEGAWAI (NEW) -->
        <div class="rounded-3xl bg-white border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-1.5 rounded-full bg-blue-500"></div>
                    <h3 class="text-lg font-black text-gray-900">Riwayat Pengajuan Izin</h3>
                </div>
                <span class="px-3 py-1 bg-gray-50 text-[10px] font-bold text-gray-400 rounded-full border border-gray-100 uppercase tracking-widest">
                    Update Terakhir
                </span>
            </div>

            <div class="space-y-4">
                @forelse($riwayatIzin as $izin)
                    <div class="group flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-100 hover:border-orange-200 hover:bg-white transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <!-- Icon Type -->
                            <div class="h-12 w-12 rounded-xl flex items-center justify-center text-xl shadow-sm
                                {{ $izin->jenis == 'sakit' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                @if($izin->jenis == 'sakit') 🤒 @else 📝 @endif
                            </div>
                            
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-gray-800">{{ ucfirst($izin->jenis) }}</h4>
                                    <span class="text-[10px] text-gray-400 font-medium">• {{ \Carbon\Carbon::parse($izin->tanggal)->translatedFormat('d F Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-500 truncate max-w-[200px] md:max-w-md italic">"{{ $izin->keterangan }}"</p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div>
                            @if($izin->status_approval == 'pending')
                                <span class="px-4 py-1.5 rounded-full bg-yellow-100 text-yellow-700 text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                    Pending
                                </span>
                            @elseif($izin->status_approval == 'disetujui')
                                <span class="px-4 py-1.5 rounded-full bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    Disetujui
                                </span>
                            @else
                                <span class="px-4 py-1.5 rounded-full bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest italic">Belum ada riwayat pengajuan.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <script>
        function updateClockSimple() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

            const clockEl = document.getElementById('attendance-clock');
            const secEl = document.getElementById('attendance-seconds');
            const dateEl = document.getElementById('current-date-display');

            if(clockEl) clockEl.innerText = h + ':' + m;
            if(secEl) secEl.innerText = s;
            if(dateEl) dateEl.innerText = dateStr;
        }
        setInterval(updateClockSimple, 1000);
        updateClockSimple();
    </script>
</x-app-layout>