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

            /* --- 3. CUSTOM SCROLLBAR --- */
            .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255, 107, 53, 0.2); border-radius: 10px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255, 107, 53, 0.4); }

            /* --- 4. ANIMATIONS --- */
            .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>

    <div class="mesh-background"></div>

    <div class="relative w-full px-4 py-8 max-w-[1600px] mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 fade-in-up">
            <div class="space-y-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-8 h-[2px] bg-[#FF6B35] rounded-full"></span>
                    <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.3em]">Advanced Monitoring & History</span>
                </div>
                <h2 class="text-4xl lg:text-5xl text-gray-900 tracking-tight leading-tight">
                    <span class="font-medium">Dashboard</span>
                    <span class="font-light text-gray-400">Absensi</span>
                </h2>
                <p class="text-gray-400 font-medium flex items-center gap-2 text-sm tracking-wide pt-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                    @if($startDate == $endDate)
                        Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
                    @else
                        Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                    @endif
                </p>
            </div>

            @if(Auth::user()->role !== 'owner')
            <div class="flex gap-4">
                 <a href="{{ route('attendance.scan') }}" target="_blank" 
                    class="group flex items-center gap-2 px-6 py-4 bg-gray-900 text-white rounded-[1.5rem] font-black shadow-xl hover:bg-[#FF6B35] transition-all transform hover:-translate-y-1 text-sm tracking-widest uppercase">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Buka Scanner
                </a>
            </div>
            @endif
        </div>

        <!-- STATISTICS CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-10 fade-in-up" style="animation-delay: 0.1s">
            <!-- Total Log -->
            <div class="glass-card p-6 rounded-[2rem] border-l-4 border-l-gray-900 hover:scale-[1.02] transition-transform cursor-pointer group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Absensi</p>
                        <h3 class="text-3xl font-black text-gray-900">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="p-3 bg-gray-900 rounded-2xl text-white shadow-lg shadow-gray-200 group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400">AKTIVITAS TERCATAT</span>
                </div>
            </div>

            <!-- Hadir Tepat Waktu -->
            <div class="glass-card p-6 rounded-[2rem] border-l-4 border-l-green-500 hover:scale-[1.02] transition-transform cursor-pointer group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tepat Waktu</p>
                        <h3 class="text-3xl font-black text-gray-900">{{ $stats['hadir'] }}</h3>
                    </div>
                    <div class="p-3 bg-green-500 rounded-2xl text-white shadow-lg shadow-green-100 group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    @php $percentHadir = $stats['total'] > 0 ? round(($stats['hadir'] / $stats['total']) * 100) : 0; @endphp
                    <div class="flex-1 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-green-500 h-full rounded-full" style="width: {{ $percentHadir }}%"></div>
                    </div>
                    <span class="text-[10px] font-bold text-green-600">{{ $percentHadir }}%</span>
                </div>
            </div>

            <!-- Terlambat -->
            <div class="glass-card p-6 rounded-[2rem] border-l-4 border-l-red-500 hover:scale-[1.02] transition-transform cursor-pointer group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Terlambat</p>
                        <h3 class="text-3xl font-black text-gray-900">{{ $stats['telat'] }}</h3>
                    </div>
                    <div class="p-3 bg-red-500 rounded-2xl text-white shadow-lg shadow-red-100 group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    @php $percentTelat = $stats['total'] > 0 ? round(($stats['telat'] / $stats['total']) * 100) : 0; @endphp
                    <div class="flex-1 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-red-500 h-full rounded-full" style="width: {{ $percentTelat }}%"></div>
                    </div>
                    <span class="text-[10px] font-bold text-red-600">{{ $percentTelat }}%</span>
                </div>
            </div>

            <!-- Izin / Sakit -->
            <div class="glass-card p-6 rounded-[2rem] border-l-4 border-l-orange-500 hover:scale-[1.02] transition-transform cursor-pointer group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Izin / Sakit</p>
                        <h3 class="text-3xl font-black text-gray-900">{{ $stats['izin'] }}</h3>
                    </div>
                    <div class="p-3 bg-orange-500 rounded-2xl text-white shadow-lg shadow-orange-100 group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400">TOTAL PENGAJUAN</span>
                </div>
            </div>
        </div>

        <!-- FILTER BAR -->
        <div class="glass-card p-6 rounded-[2.5rem] mb-8 fade-in-up" style="animation-delay: 0.2s">
            <form action="{{ route('attendance.monitoring') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                        class="w-full bg-white/50 border-white/50 rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full bg-white/50 border-white/50 rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Pegawai</label>
                    <select name="user_id" class="w-full bg-white/50 border-white/50 rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                        <option value="">Semua Pegawai</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('user_id') == $emp->id ? 'selected' : '' }}>{{ $emp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Status</label>
                    <select name="status" class="w-full bg-white/50 border-white/50 rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="telat" {{ request('status') == 'telat' ? 'selected' : '' }}>Telat</option>
                        <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-gray-900 hover:bg-black text-white font-black py-3 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-widest">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Filter
                    </button>
                    
                    <button type="button" onclick="exportToPdf()" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-black py-3 rounded-2xl shadow-lg shadow-orange-100 transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-widest">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        PDF
                    </button>

                    <a href="{{ route('attendance.monitoring') }}" class="p-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-2xl transition-all" title="Reset Filter">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- MAIN TABLE -->
        <div class="glass-card rounded-[2.5rem] border border-white/50 shadow-xl overflow-hidden fade-in-up" style="animation-delay: 0.3s">
            <div class="overflow-x-auto custom-scroll">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gradient-to-r from-orange-50/50 to-red-50/50">
                            <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">TANGGAL & PEGAWAI</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">SHIFT</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">JAM MASUK</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">STATUS</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">JAM PULANG</th>
                            <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">BUKTI / KET</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white/60 backdrop-blur-md">
                        @forelse($logs as $log)
                        <tr class="hover:bg-orange-50/40 transition-colors group">
                            <!-- TANGGAL & PEGAWAI -->
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="text-center bg-gray-50 p-2 rounded-xl border border-gray-100 group-hover:bg-white transition-colors">
                                        <div class="text-[8px] font-black text-gray-400 uppercase">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('M') }}</div>
                                        <div class="text-sm font-black text-gray-900">{{ \Carbon\Carbon::parse($log->tanggal)->format('d') }}</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $log->user->foto_url }}" alt="" class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-sm group-hover:scale-110 transition-transform">
                                        <div>
                                            <div class="text-sm font-black text-gray-900 group-hover:text-orange-500 transition-colors">{{ $log->user->nama }}</div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">{{ $log->user->jabatan ?? ucfirst($log->user->role) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- SHIFT -->
                            <td class="px-8 py-5 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-gray-100/80 text-gray-600 text-[10px] font-black uppercase tracking-wider border border-gray-200">
                                    {{ $log->shift ? $log->shift->nama_shift : '-' }}
                                </span>
                            </td>

                            <!-- JAM MASUK -->
                            <td class="px-8 py-5 whitespace-nowrap">
                                @if($log->waktu_masuk)
                                    <div class="text-sm font-bold text-gray-900 tabular-nums font-mono">
                                        {{ \Carbon\Carbon::parse($log->waktu_masuk)->format('H:i') }}
                                        <span class="text-gray-400 text-[10px] font-medium ml-1">WIB</span>
                                    </div>
                                @else
                                    <span class="text-gray-300 text-xs italic font-bold">--:--</span>
                                @endif
                            </td>

                            <!-- STATUS -->
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

                            <!-- JAM PULANG -->
                            <td class="px-8 py-5 whitespace-nowrap text-center">
                                @if($log->waktu_pulang)
                                    <div class="text-sm font-bold text-gray-900 tabular-nums font-mono">
                                        {{ \Carbon\Carbon::parse($log->waktu_pulang)->format('H:i') }}
                                        <span class="text-gray-400 text-[10px] font-medium ml-1">WIB</span>
                                    </div>
                                @else
                                    <span class="text-[9px] font-black text-gray-400 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 uppercase tracking-tighter">BELUM PULANG</span>
                                @endif
                            </td>

                            <!-- BUKTI / KET -->
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    @if($log->bukti_foto)
                                        <button onclick="showProofModal('{{ asset('storage/' . $log->bukti_foto) }}', '{{ $log->user->nama }}')" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm group/btn">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </button>
                                    @endif
                                    <p class="text-[10px] text-gray-500 font-bold max-w-[150px] line-clamp-2" title="{{ $log->keterangan }}">{{ $log->keterangan }}</p>
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
                                    <p class="text-sm font-medium text-gray-400 mt-1 max-w-xs mx-auto">Data kehadiran pegawai untuk periode ini akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PROOF MODAL -->
    <div id="proofModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeProofModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
            <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm">Bukti Absensi: <span id="modalEmployeeName" class="text-orange-500"></span></h3>
                    <button onclick="closeProofModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-4">
                    <img id="proofImage" src="" alt="Bukti Absensi" class="w-full rounded-[1.5rem] shadow-inner object-cover max-h-[70vh]">
                </div>
                <div class="p-6 bg-gray-50/50">
                    <a id="downloadBtn" href="" download class="w-full bg-gray-900 text-white font-black py-3 rounded-2xl flex items-center justify-center gap-2 text-xs uppercase tracking-[0.2em] hover:bg-orange-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Simpan Gambar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportToPdf() {
            const form = document.querySelector('form');
            const urlParams = new URLSearchParams(new FormData(form)).toString();
            window.location.href = "{{ route('attendance.export_pdf') }}?" + urlParams;
        }

        function showProofModal(src, name) {
            document.getElementById('proofImage').src = src;
            document.getElementById('modalEmployeeName').innerText = name;
            document.getElementById('downloadBtn').href = src;
            document.getElementById('proofModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeProofModal() {
            document.getElementById('proofModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on escape key
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeProofModal();
        });
    </script>
</x-app-layout>