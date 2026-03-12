<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-100 mb-8 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex gap-2 mb-4">
                    <span class="bg-purple-100 text-purple-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Owner Area</span>
                    <span class="flex items-center gap-2 text-[10px] font-bold text-emerald-500 uppercase tracking-wider">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> System Online
                    </span>
                </div>
                <h2 class="text-6xl font-black mb-4 tracking-tighter text-slate-900 leading-tight">
                    Laporan <span class="text-orange-500 italic">Penjualan.</span>
                </h2>
                <p class="text-gray-500 max-w-xl">Pantau performa bisnis dan rincian transaksi harian secara real-time.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-50">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Omzet Hari Ini</p>
                <h3 class="text-3xl font-black text-gray-800">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-50">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Transaksi Hari Ini</p>
                <h3 class="text-3xl font-black text-gray-800">{{ $jumlahTransaksiHariIni }} <span class="text-sm font-bold text-gray-400">Nota</span></h3>
            </div>
            <div class="bg-slate-900 p-8 rounded-[32px] text-white shadow-xl">
                <p class="text-xs text-white/40 font-bold uppercase tracking-widest mb-1 text-white">Item Terjual</p>
                <h3 class="text-3xl font-black text-white">{{ $itemTerjualHariIni }} <span class="text-sm font-bold text-white/30">Produk</span></h3>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100">
            <h4 class="text-xl font-bold text-slate-800 mb-8 italic">Recent <span class="text-orange-500">Transactions.</span></h4>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase font-bold tracking-[0.2em] border-b border-gray-50">
                        <th class="pb-6 px-4">Waktu</th>
                        <th class="pb-6 px-4">ID Transaksi</th>
                        <th class="pb-6 px-4">Kasir</th>
                        <th class="pb-6 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-semibold">
                    @foreach($semuaTransaksi as $t)
                    <tr class="border-b border-gray-50 last:border-none group hover:bg-gray-50/50 transition">
                        <td class="py-6 px-4">{{ $t->created_at->format('H:i') }} WIB</td>
                        <td class="py-6 px-4">
                            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                #INV-{{ $t->id }}
                            </span>
                        </td>
                        <td class="py-6 px-4 font-bold text-slate-700">{{ $t->user->name ?? 'System' }}</td>
                        <td class="py-6 px-4 text-right">
                            <a href="{{ route('laporan.penjualan.show', $t->id) }}" class="text-orange-500 font-bold text-xs flex items-center justify-end gap-1 hover:gap-2 transition-all">
                                DETAIL <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>