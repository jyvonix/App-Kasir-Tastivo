<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Kehadiran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
                <div>
                    <h3 class="text-3xl font-black text-gray-800 tracking-tight">Rekap <span class="text-[#FF6B35]">Harian</span></h3>
                    <p class="text-gray-500 font-medium mt-1">Pantau kehadiran pegawai secara real-time.</p>
                </div>
                <div class="bg-white px-5 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
                    <div class="bg-orange-100 p-2 rounded-lg text-orange-600">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Tanggal Hari Ini</p>
                        <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs uppercase font-bold tracking-wider border-b border-gray-100">
                                <th class="p-6">Pegawai</th>
                                <th class="p-6">Role</th>
                                <th class="p-6">Jadwal Shift</th>
                                <th class="p-6">Jam Masuk</th>
                                <th class="p-6">Jam Pulang</th>
                                <th class="p-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @foreach($pegawais as $pegawai)
                                @php
                                    $absen = $pegawai->attendance_today;
                                @endphp
                                <tr class="hover:bg-orange-50/30 transition border-b border-gray-50 last:border-none group">
                                    
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold shadow-sm border border-white">
                                                {{ substr($pegawai->nama, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 group-hover:text-orange-600 transition">{{ $pegawai->nama }}</p>
                                                <p class="text-xs text-gray-400">{{ $pegawai->email }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="p-6">
                                        <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $pegawai->role == 'admin' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                                            {{ $pegawai->role }}
                                        </span>
                                    </td>

                                    <td class="p-6">
                                        @if($absen && $absen->shift)
                                            <span class="font-bold text-gray-700">{{ $absen->shift->nama_shift }}</span>
                                            <span class="text-xs text-gray-400 block">
                                                {{ \Carbon\Carbon::parse($absen->shift->jam_masuk)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($absen->shift->jam_pulang)->format('H:i') }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 italic">-</span>
                                        @endif
                                    </td>

                                    <td class="p-6">
                                        @if($absen)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-sign-in-alt text-green-500"></i>
                                                <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i') }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>

                                    <td class="p-6">
                                        @if($absen && $absen->waktu_pulang)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-sign-out-alt text-red-500"></i>
                                                <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($absen->waktu_pulang)->format('H:i') }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>

                                    <td class="p-6 text-center">
                                        @if($absen)
                                            @if($absen->waktu_pulang)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200 animate-pulse">
                                                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span> Bekerja
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-500 border border-red-100">
                                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span> Belum Hadir
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

