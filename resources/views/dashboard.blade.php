<x-app-layout>
    <!-- Background Decor (Agar tidak sepi) -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden opacity-[0.03]">
        <div class="absolute top-[10%] left-[5%] w-[40rem] h-[40rem] bg-orange-500 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[10%] right-[5%] w-[30rem] h-[30rem] bg-blue-500 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 space-y-8 animate-fade-in">
        
        <!-- TOP SECTION: Personal Greeting -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div class="flex items-center gap-5">
                <div class="relative">
                    <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&background=f97316&color=fff' }}" 
                         class="w-16 h-16 md:w-20 md:h-20 rounded-[1.5rem] object-cover shadow-xl border-4 border-white">
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full"></div>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight leading-none">
                        Halo, <span class="text-orange-500">{{ Auth::user()->nama }}</span>! 👋
                    </h1>
                    <p class="text-gray-500 font-medium mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 0L10 18.95 5.05 4.05zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                        Dashboard {{ ucfirst(Auth::user()->role) }} <span class="text-gray-300">|</span> 
                        <span id="current-date-text" class="text-gray-400">--</span>
                    </p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <div class="text-right">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Waktu Sekarang</p>
                    <p class="text-2xl font-black text-gray-900 tabular-nums" id="digital-clock-big">--:--</p>
                </div>
                <div class="w-12 h-12 bg-gray-900 rounded-2xl flex items-center justify-center text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- QUICK ACTIONS (Agar ramai & fungsional) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('transaksi.index') }}" class="p-4 bg-orange-500 hover:bg-orange-600 rounded-[1.5rem] text-white transition-all hover:-translate-y-1 shadow-lg shadow-orange-500/20 group">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <p class="text-sm font-black uppercase tracking-wider">Kasir POS</p>
                <p class="text-[10px] opacity-70 font-bold">Mulai Penjualan</p>
            </a>

            <a href="{{ route('produk.index') }}" class="p-4 bg-white border border-gray-100 hover:border-orange-200 rounded-[1.5rem] text-gray-800 transition-all hover:-translate-y-1 shadow-sm group">
                <div class="w-10 h-10 bg-gray-50 text-gray-400 group-hover:bg-orange-100 group-hover:text-orange-600 rounded-xl flex items-center justify-center mb-3 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <p class="text-sm font-black uppercase tracking-wider">Produk</p>
                <p class="text-[10px] text-gray-400 font-bold">Kelola Stok</p>
            </a>

            <a href="{{ route('transaksi.riwayat') }}" class="p-4 bg-white border border-gray-100 hover:border-blue-200 rounded-[1.5rem] text-gray-800 transition-all hover:-translate-y-1 shadow-sm group">
                <div class="w-10 h-10 bg-gray-50 text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-600 rounded-xl flex items-center justify-center mb-3 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <p class="text-sm font-black uppercase tracking-wider">Laporan</p>
                <p class="text-[10px] text-gray-400 font-bold">Riwayat Harian</p>
            </a>

            <div class="p-4 bg-gray-900 rounded-[1.5rem] text-white relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-400 mb-1">Status Sistem</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <p class="text-lg font-black tracking-tight">Terhubung</p>
                </div>
                <p class="text-[10px] opacity-50 mt-2 font-bold uppercase tracking-wider">Sync: v2.4.0</p>
            </div>
        </div>

        <!-- STATS & ANALYTICS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Bento Box: Revenue/Summary (Span 2) -->
            <div class="md:col-span-2 bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm flex flex-col justify-between min-h-[300px] relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8">
                    <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>

                <div>
                    <h3 class="text-gray-400 font-black text-xs uppercase tracking-widest mb-2">Penjualan Terakhir</h3>
                    @php
                        $latestTrx = $transaksiTerbaru->first();
                    @endphp
                    @if($latestTrx)
                        <p class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter">
                            Rp {{ number_format($latestTrx->total_harga, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-green-600 font-bold mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                            Baru saja masuk (#{{ $latestTrx->kode_transaksi }})
                        </p>
                    @else
                        <p class="text-4xl font-black text-gray-300">Belum ada data</p>
                    @endif
                </div>

                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Informasi Toko</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-2xl">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Menu Aktif</p>
                            <p class="text-xl font-black text-gray-800">{{ $totalProduk ?? 0 }} <span class="text-xs text-gray-400 font-bold">Item</span></p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Total Nota</p>
                            <p class="text-xl font-black text-gray-800">{{ $totalTransaksi ?? 0 }} <span class="text-xs text-gray-400 font-bold">Trx</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento Box: Stock Warning (Side) -->
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm flex flex-col group">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-gray-900">Stok Menipis</h3>
                    <span class="px-2 py-1 bg-red-100 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-wider">Hampir Habis</span>
                </div>

                <div class="flex-1 space-y-4 overflow-y-auto no-scrollbar max-h-[200px]">
                    @forelse($stokMenipis ?? [] as $item)
                        <div class="flex items-center gap-3 group/item">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-xs font-black text-gray-400 group-hover/item:bg-red-50 group-hover/item:text-red-500 transition-colors">
                                {{ substr($item->nama_produk, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate">{{ $item->nama_produk }}</p>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-red-500 rounded-full" style="width: {{ ($item->stok / 10) * 100 }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-red-500">{{ $item->stok }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-center opacity-40 py-8">
                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <p class="text-xs font-bold">Stok Aman Terkendali</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('produk.index') }}" class="mt-6 w-full py-3 bg-gray-50 text-gray-600 rounded-xl text-center text-xs font-bold hover:bg-gray-100 transition-colors">
                    Lengkapi Stok
                </a>
            </div>
        </div>

        <!-- BOTTOM SECTION: Recent Activity Table -->
        <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Aktivitas Terkini</h3>
                    <p class="text-sm text-gray-400 font-medium">Data transaksi masuk secara real-time.</p>
                </div>
                <div class="flex gap-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-ping"></div>
                    <span class="text-xs font-black text-green-600 uppercase tracking-widest">Live Updates</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-300 text-[10px] uppercase tracking-widest border-b border-gray-50">
                            <th class="pb-4 font-black pl-4">Transaksi</th>
                            <th class="pb-4 font-black">Waktu</th>
                            <th class="pb-4 font-black text-right">Total Tagihan</th>
                            <th class="pb-4 font-black text-right pr-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($transaksiTerbaru as $trx)
                            <tr class="group hover:bg-gray-50 transition-colors">
                                <td class="py-5 pl-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-50 text-gray-400 group-hover:bg-white group-hover:text-orange-500 rounded-xl flex items-center justify-center font-black transition-all">
                                            #
                                        </div>
                                        <span class="font-black text-gray-800">{{ $trx->kode_transaksi }}</span>
                                    </div>
                                </td>
                                <td class="py-5 font-bold text-gray-500">
                                    {{ $trx->created_at->format('H:i') }}
                                    <span class="block text-[10px] text-gray-300 uppercase tracking-tighter">{{ $trx->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="py-5 text-right font-black text-gray-900">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="py-5 text-right pr-4">
                                    @php
                                        $isPaid = in_array($trx->status_pembayaran, ['Lunas', 'success', 'paid', 'settlement']) || ($trx->bayar >= $trx->total_harga);
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $isPaid ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $isPaid ? 'Lunas' : 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-10 text-center text-gray-400 font-bold">Belum ada aktivitas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        function updateLiveTime() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
            const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            const clockEl = document.getElementById('digital-clock-big');
            const dateEl = document.getElementById('current-date-text');
            const headerDateEl = document.getElementById('current-date-text'); // Same element used in desktop/mobile parts

            if(clockEl) clockEl.innerText = timeStr;
            if(dateEl) dateEl.innerText = dateStr;
        }
        setInterval(updateLiveTime, 1000);
        updateLiveTime();
    </script>
</x-app-layout>