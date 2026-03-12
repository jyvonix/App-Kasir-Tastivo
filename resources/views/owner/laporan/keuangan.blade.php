<x-app-layout>
    <div class="space-y-8">
        
        <!-- HEADER & FILTER -->
        <div class="flex flex-col lg:flex-row justify-between items-end gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Laporan Keuangan</h2>
                <p class="text-gray-500 font-medium">Rekapitulasi pendapatan dan performa penjualan.</p>
            </div>
            
            <form action="{{ route('laporan.keuangan') }}" method="GET" class="flex flex-col sm:flex-row gap-3 bg-white p-2 rounded-2xl shadow-sm border border-gray-100 w-full lg:w-auto">
                <div class="relative">
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                           class="pl-4 pr-2 py-2 bg-gray-50 border-none rounded-xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 cursor-pointer">
                </div>
                <span class="hidden sm:flex items-center text-gray-400 font-bold">-</span>
                <div class="relative">
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                           class="pl-4 pr-2 py-2 bg-gray-50 border-none rounded-xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-orange-500 cursor-pointer">
                </div>
                <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-orange-200 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
            </form>
        </div>

        <!-- SECTION 1: BREAKDOWN PENDAPATAN (H/M/B/T) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Harian -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-orange-200 transition-all">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Hari Ini</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                <p class="text-[10px] font-bold text-green-600 mt-2 bg-green-50 w-fit px-2 py-1 rounded-lg">Realtime</p>
            </div>

            <!-- Mingguan -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-blue-200 transition-all">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Minggu Ini</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">Rp {{ number_format($pendapatanMingguIni, 0, ',', '.') }}</h3>
                <p class="text-[10px] font-bold text-blue-600 mt-2 bg-blue-50 w-fit px-2 py-1 rounded-lg">Senin - Minggu</p>
            </div>

            <!-- Bulanan -->
            <div class="bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-3xl p-5 shadow-lg shadow-orange-200 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white opacity-10 blur-2xl group-hover:scale-110 transition-transform"></div>
                <p class="text-xs font-black text-orange-100 uppercase tracking-widest relative z-10">Bulan Ini</p>
                <h3 class="text-2xl font-black mt-1 relative z-10">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                <p class="text-[10px] font-bold text-white/80 mt-2 bg-white/20 w-fit px-2 py-1 rounded-lg relative z-10">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
            </div>

            <!-- Tahunan -->
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 relative overflow-hidden group hover:border-purple-200 transition-all">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Tahun Ini</p>
                <h3 class="text-2xl font-black text-gray-900 mt-1">Rp {{ number_format($pendapatanTahunIni, 0, ',', '.') }}</h3>
                <p class="text-[10px] font-bold text-purple-600 mt-2 bg-purple-50 w-fit px-2 py-1 rounded-lg">Tahun {{ \Carbon\Carbon::now()->year }}</p>
            </div>
        </div>

        <!-- SECTION 2: GRAFIK & PRODUK TOP -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Grafik Penjualan (Harian) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Grafik Omset</h3>
                        <p class="text-xs text-gray-400 font-bold">Tren harian berdasarkan filter tanggal</p>
                    </div>
                    <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-lg text-xs font-bold uppercase">Chart</span>
                </div>
                <div class="h-72 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Top Produk (Laporan Produk Mini) -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col">
                <h3 class="text-lg font-black text-gray-900 mb-4">Produk Terlaris</h3>
                <div class="flex-1 overflow-y-auto pr-2 space-y-4 max-h-[300px] scrollbar-thin">
                    @forelse($produkTerlaris as $idx => $produk)
                        <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center text-orange-700 font-black text-xs">
                                    {{ $idx + 1 }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800 line-clamp-1">{{ $produk->nama_produk }}</h4>
                                    <p class="text-[10px] font-bold text-gray-400">{{ $produk->total_qty }} Terjual</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-black text-gray-900">Rp {{ number_format($produk->total_revenue/1000, 0) }}k</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-xs font-bold">Belum ada data penjualan.</div>
                    @endforelse
                </div>
                <a href="{{ route('laporan.stok') }}" class="mt-4 block w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-center rounded-xl text-xs transition-colors uppercase tracking-wider">
                    Lihat Stok Lengkap
                </a>
            </div>
        </div>

        <!-- SECTION 3: TABEL TRANSAKSI -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Rincian Transaksi</h3>
                    <p class="text-xs text-gray-400 font-bold mt-1">
                        Total Periode Ini: <span class="text-orange-600">Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}</span> 
                        ({{ $totalTransaksi }} Struk)
                    </p>
                </div>
                <!-- Indikator Rata-rata -->
                <div class="px-4 py-2 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Rata-rata / Struk</p>
                    <p class="text-lg font-black text-blue-700">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4">ID Transaksi</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Kasir</th>
                            <th class="px-6 py-4">Metode</th>
                            <th class="px-6 py-4 text-right">Total</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($riwayatTransaksi as $trx)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 font-mono text-sm font-bold text-gray-600 group-hover:text-orange-600">#{{ $trx->kode_transaksi }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-800">
                                {{ $trx->tanggal->format('d/m/Y') }} <span class="text-gray-400 font-medium text-xs">{{ $trx->tanggal->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                        {{ substr($trx->user->nama ?? '?', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-600">{{ $trx->user->nama ?? 'Sistem' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-500">
                                    {{ $trx->metode_pembayaran ?? 'Tunai' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-black text-gray-900">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                    LUNAS
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('transaksi.show', $trx->id) }}" class="text-orange-600 hover:text-orange-800 font-bold text-sm underline decoration-orange-300 decoration-2 underline-offset-2">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-medium">Belum ada data transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $riwayatTransaksi->links() }}
            </div>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(249, 115, 22, 0.6)'); // Orange-500
        gradient.addColorStop(1, 'rgba(249, 115, 22, 0.0)');

        const labels = {!! json_encode($grafikData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))) !!};
        const data = {!! json_encode($grafikData->pluck('total')) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#ea580c',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ea580c',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#374151',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f3f4f6' },
                        ticks: {
                            font: { family: "'Outfit', sans-serif", size: 10, weight: 'bold' },
                            color: '#9ca3af',
                            callback: function(value) { return (value/1000) + 'k'; }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: "'Outfit', sans-serif", size: 10, weight: 'bold' },
                            color: '#9ca3af'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>