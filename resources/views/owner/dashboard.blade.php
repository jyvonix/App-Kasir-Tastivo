<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Dashboard Owner') }}
            </h2>
            <div class="mt-2 md:mt-0 text-sm text-gray-500">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- SECTION 1: STATS CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Card 1: Income Today (Primary Highlight) -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 p-6 text-white shadow-xl transform hover:scale-105 transition duration-300">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white opacity-20 blur-xl"></div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium opacity-90">Omset Hari Ini</p>
                        <h3 class="mt-2 text-3xl font-bold">Rp {{ number_format($incomeToday, 0, ',', '.') }}</h3>
                        <p class="mt-1 text-xs opacity-75">Update Real-time</p>
                    </div>
                    <div class="absolute bottom-4 right-4 text-white opacity-30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Card 2: Income Month -->
                <div class="rounded-2xl bg-white p-6 shadow-lg border border-orange-100 hover:shadow-orange-100/50 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Omset Bulan Ini</p>
                            <h3 class="mt-2 text-2xl font-bold text-gray-800">Rp {{ number_format($incomeMonth, 0, ',', '.') }}</h3>
                        </div>
                        <div class="rounded-full bg-orange-50 p-3 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Income Year -->
                <div class="rounded-2xl bg-white p-6 shadow-lg border border-orange-100 hover:shadow-orange-100/50 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Omset Tahun Ini</p>
                            <h3 class="mt-2 text-2xl font-bold text-gray-800">Rp {{ number_format($incomeYear, 0, ',', '.') }}</h3>
                        </div>
                        <div class="rounded-full bg-red-50 p-3 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Attendance Summary -->
                <div class="rounded-2xl bg-white p-6 shadow-lg border border-orange-100 hover:shadow-orange-100/50 transition duration-300">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-gray-500">Kehadiran Hari Ini</p>
                        <a href="{{ route('attendance.monitoring') }}" class="text-xs text-orange-600 hover:underline">Lihat Detail</a>
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                             <span class="text-2xl font-bold text-green-600">{{ $hadirCount }}</span>
                             <span class="text-xs text-gray-400 ml-1">Hadir</span>
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                         <div>
                             <span class="text-2xl font-bold text-yellow-500">{{ $telatCount }}</span>
                             <span class="text-xs text-gray-400 ml-1">Telat</span>
                        </div>
                        <div class="h-8 w-px bg-gray-200"></div>
                         <div>
                             <span class="text-2xl font-bold text-red-500">{{ $alphaCount }}</span>
                             <span class="text-xs text-gray-400 ml-1">Absen</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: CHART & TOP PRODUCTS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Sales Chart -->
                <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-lg border border-orange-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Statistik Penjualan</h3>
                        <span class="px-3 py-1 text-xs font-semibold text-orange-600 bg-orange-100 rounded-full">7 Hari Terakhir</span>
                    </div>
                    <div class="relative h-72">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="rounded-2xl bg-white p-6 shadow-lg border border-orange-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Menu Terlaris</h3>
                    <div class="space-y-4">
                        @foreach($topProducts as $index => $item)
                        <div class="flex items-center p-3 rounded-xl {{ $index == 0 ? 'bg-orange-50 border border-orange-100' : 'hover:bg-gray-50' }} transition">
                            <div class="flex-shrink-0 relative">
                                @if($item->produk && $item->produk->gambar_file)
                                    <img class="h-12 w-12 rounded-lg object-cover shadow-sm" src="{{ asset('storage/produk/' . $item->produk->gambar_file) }}" alt="{{ $item->produk->nama_produk }}">
                                @elseif($item->produk && $item->produk->gambar_url)
                                    <img class="h-12 w-12 rounded-lg object-cover shadow-sm" src="{{ $item->produk->gambar_url }}" alt="{{ $item->produk->nama_produk }}">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute -top-2 -left-2 bg-gray-800 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-md">
                                    #{{ $index + 1 }}
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-semibold text-gray-800">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</h4>
                                <p class="text-xs text-gray-500">Terjual: <span class="font-bold text-orange-600">{{ $item->total_sold }}</span> item</p>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($topProducts->isEmpty())
                        <div class="text-center py-8 text-gray-400">
                            <p class="text-sm">Belum ada data penjualan.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SECTION 3: ATTENDANCE LIST -->
            <div class="rounded-2xl bg-white p-6 shadow-lg border border-orange-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Log Absensi Hari Ini</h3>
                    <a href="{{ route('attendance.monitoring') }}" class="text-sm font-medium text-orange-600 hover:text-orange-700">Lihat Semua &rarr;</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pegawai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Masuk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendanceToday as $absen)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            @if($absen->user->foto)
                                                <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . $absen->user->foto) }}" alt="">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs">
                                                    {{ substr($absen->user->name, 0, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $absen->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $absen->user->role }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($absen->waktu_masuk)->format('H:i') }} WIB
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(strtolower($absen->status) == 'hadir')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Hadir
                                        </span>
                                    @elseif(strtolower($absen->status) == 'telat')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($absen->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($absen->bukti_foto)
                                        <a href="{{ asset('storage/' . $absen->bukti_foto) }}" target="_blank" class="text-orange-600 hover:text-orange-900 text-xs">Lihat Bukti</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-400 text-sm">Belum ada yang absen hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            // Gradient Fill
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(234, 88, 12, 0.5)'); // Orange-600
            gradient.addColorStop(1, 'rgba(234, 88, 12, 0.0)');

            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Omset Harian (Rp)',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: gradient,
                        borderColor: 'rgba(234, 88, 12, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: 'rgba(234, 88, 12, 1)',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4 // Curves the line
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1f2937',
                            bodyColor: '#ea580c',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + (value/1000) + 'k';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>
</x-app-layout>