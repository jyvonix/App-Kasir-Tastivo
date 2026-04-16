<x-app-layout>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
            .mesh-background {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
                background-color: #F1F5F9;
                background-image: radial-gradient(at 0% 0%, hsla(25, 100%, 88%, 1) 0, transparent 50%), radial-gradient(at 100% 0%, hsla(210, 100%, 96%, 1) 0, transparent 50%);
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.75);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.6);
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            }
            .lava-btn {
                background: linear-gradient(135deg, #FF6B35 0%, #D84315 100%);
                box-shadow: 0 4px 15px rgba(216, 67, 21, 0.3);
            }
            .lava-btn:hover {
                background: linear-gradient(135deg, #D84315 0%, #BF360C 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(216, 67, 21, 0.4);
            }
            .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.05); border-radius: 10px; }
            .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
            @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>

    <div class="mesh-background"></div>

    <div class="relative w-full px-4 py-8 max-w-[1600px] mx-auto">
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8 fade-in-up">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.3em]">Operational Intelligence</span>
                </div>
                <h2 class="text-4xl font-black text-gray-900 tracking-tight">History <span class="font-light text-gray-400">Absensi</span></h2>
                <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">
                    {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                </p>
            </div>

            @if(auth()->user()->role !== 'owner')
            <a href="{{ route('attendance.scan') }}" target="_blank" class="px-6 py-3 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-orange-600 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H6v-4h6v4m0-6v-4m0 0h6m-6 0H6"></path></svg>
                Scanner Mode
            </a>
            @endif
        </div>

        <!-- STATISTICS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8 fade-in-up" style="animation-delay: 0.1s">
            @foreach([
                ['Total', $stats['total'], 'gray-900', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['Hadir', $stats['hadir'], 'emerald-500', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Telat', $stats['telat'], 'rose-500', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Izin', $stats['izin'], 'orange-500', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z']
            ] as [$label, $val, $color, $icon])
            <div class="glass-card p-6 rounded-[2rem] border-b-4 border-{{ $color }} transition-all hover:scale-[1.02]">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">{{ $label }}</p>
                        <h3 class="text-3xl font-black text-gray-900">{{ $val }}</h3>
                    </div>
                    <div class="p-2.5 bg-{{ $color }} rounded-xl text-white shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path></svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- FILTER BAR -->
        <div class="glass-card p-5 rounded-[2.5rem] mb-8 fade-in-up" style="animation-delay: 0.2s">
            <form id="filterForm" action="{{ route('attendance.monitoring') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-3 space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-3">Rentang Waktu</label>
                    <div class="flex items-center gap-2 bg-white/50 border border-white/60 rounded-2xl p-1.5">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full bg-transparent border-none text-xs font-bold text-gray-900 focus:ring-0">
                        <span class="text-gray-300">—</span>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full bg-transparent border-none text-xs font-bold text-gray-900 focus:ring-0">
                    </div>
                </div>
                <div class="md:col-span-3 space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-3">Pegawai</label>
                    <select name="user_id" class="w-full bg-white/50 border-white/60 rounded-2xl px-4 py-3 text-xs font-bold text-gray-900 focus:ring-orange-500 transition-all">
                        <option value="">Semua Pegawai</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('user_id') == $emp->id ? 'selected' : '' }}>{{ $emp->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-3">Status</label>
                    <select name="status" class="w-full bg-white/50 border-white/60 rounded-2xl px-4 py-3 text-xs font-bold text-gray-900 focus:ring-orange-500 transition-all">
                        <option value="">Semua Status</option>
                        @foreach(['hadir', 'telat', 'izin', 'sakit'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="flex-1 bg-gray-900 text-white font-black py-3 rounded-2xl text-[10px] uppercase tracking-[0.2em] shadow-lg hover:bg-black transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Filter
                    </button>
                    <button type="button" onclick="exportToPdf()" class="flex-1 lava-btn text-white font-black py-3 rounded-2xl text-[10px] uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Cetak PDF
                    </button>
                    <a href="{{ route('attendance.monitoring') }}" class="p-3 bg-gray-100 text-gray-400 rounded-2xl hover:bg-gray-200 transition-all flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="glass-card rounded-[2.5rem] overflow-hidden fade-in-up" style="animation-delay: 0.3s">
            <div class="overflow-x-auto custom-scroll">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Waktu & Pegawai</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Shift</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Check In</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Check Out</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white/40">
                        @forelse($logs as $log)
                        <tr class="hover:bg-white/80 transition-all group">
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="bg-gray-100 px-3 py-2 rounded-xl text-center group-hover:bg-orange-50 transition-all">
                                        <p class="text-[8px] font-black text-gray-400 uppercase leading-none">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('M') }}</p>
                                        <p class="text-sm font-black text-gray-900 leading-none mt-1">{{ \Carbon\Carbon::parse($log->tanggal)->format('d') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-gray-900">{{ $log->user->nama }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $log->user->jabatan ?? $log->user->role }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <span class="text-[10px] font-black text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">{{ $log->shift->nama_shift ?? '-' }}</span>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <p class="text-xs font-bold text-gray-900">{{ $log->waktu_masuk ? \Carbon\Carbon::parse($log->waktu_masuk)->format('H:i') : '--:--' }}</p>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                @php
                                    $s_color = match($log->status) { 'hadir' => 'emerald', 'telat' => 'rose', 'izin' => 'blue', 'sakit' => 'purple', default => 'gray' };
                                @endphp
                                <span class="px-3 py-1.5 rounded-xl bg-{{ $s_color }}-100 text-{{ $s_color }}-700 text-[9px] font-black uppercase tracking-wider">
                                    {{ $log->status == 'hadir' ? 'TEPAT WAKTU' : $log->status }}
                                </span>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <p class="text-xs font-bold text-gray-900">{{ $log->waktu_pulang ? \Carbon\Carbon::parse($log->waktu_pulang)->format('H:i') : '--:--' }}</p>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($log->bukti_foto)
                                        <button onclick="showProofModal('{{ asset('storage/'.$log->bukti_foto) }}', '{{ $log->user->nama }}')" class="p-2 bg-zinc-100 text-zinc-500 rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </button>
                                    @endif
                                    <p class="text-[9px] font-bold text-gray-400 italic max-w-[100px] truncate" title="{{ $log->keterangan }}">{{ $log->keterangan }}</p>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">Belum ada data absensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div id="proofModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" onclick="closeProofModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
            <div class="glass-card rounded-[2.5rem] overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase tracking-widest text-gray-900">Bukti: <span id="modalEmployeeName" class="text-orange-500"></span></h3>
                    <button onclick="closeProofModal()" class="text-gray-400 hover:text-gray-900 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="p-4"><img id="proofImage" src="" class="w-full rounded-[1.5rem] object-cover shadow-inner"></div>
            </div>
        </div>
    </div>

    <script>
        function exportToPdf() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams();
            
            // Loop through form data to build query params
            for (const [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            
            window.location.href = "{{ route('attendance.export_pdf') }}?" + params.toString();
        }

        function showProofModal(src, name) {
            document.getElementById('proofImage').src = src;
            document.getElementById('modalEmployeeName').innerText = name;
            document.getElementById('proofModal').classList.remove('hidden');
        }

        function closeProofModal() {
            document.getElementById('proofModal').classList.add('hidden');
        }
    </script>
</x-app-layout>