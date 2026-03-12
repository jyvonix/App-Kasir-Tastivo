<x-app-layout>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
            
            /* --- 1. BACKGROUND SYSTEM --- */
            .mesh-background {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
                background-color: #F8FAFC;
                background-image: 
                    radial-gradient(at 0% 0%, hsla(25, 100%, 94%, 1) 0, transparent 50%), 
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
    <div class="fixed bottom-20 left-[5%] opacity-10 floating-icon-delayed pointer-events-none hidden lg:block">
        <svg class="w-24 h-24 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V1h-1.97v4.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v8.05zM1 22.99V9c0-2.21 1.79-4 4-4h10c2.21 0 4 1.79 4 4v13.99H1zM3.4 15h12.2l-6.1-9-6.1 9z"/></svg>
    </div>

    <div class="relative w-full py-6">
        <div class="max-w-[1600px] mx-auto px-4">
            
            <!-- HEADER (Modern Minimalist Display) -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-10 relative z-10">
                <div class="space-y-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-[2px] bg-[#FF6B35] rounded-full"></span>
                        <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.3em]">Transaction History</span>
                    </div>
                    <h1 class="text-4xl lg:text-5xl text-gray-900 tracking-tight leading-tight">
                        <span class="font-medium">Riwayat</span>
                        <span class="font-light text-gray-400">Transaksi</span>
                    </h1>
                    <p class="text-gray-400 font-medium tracking-wide pt-2">Pantau semua aktivitas penjualan dan status pembayaran.</p>
                </div>
                
                <div class="flex gap-4">
                    <a href="{{ route('transaksi.index') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-sm font-black text-white transition-all duration-300 bg-gray-900 rounded-[1.5rem] hover:bg-[#FF6B35] hover:shadow-xl hover:shadow-orange-200 hover:-translate-y-1 uppercase tracking-widest">
                        <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Transaksi Baru
                    </a>
                </div>
            </div>

            <!-- TABEL MODERN -->
            <div class="glass-card rounded-[2.5rem] border border-white/50 shadow-xl overflow-hidden relative z-10">
                <div class="overflow-x-auto custom-scroll">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gradient-to-r from-orange-50/50 to-red-50/50">
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] w-16 text-center">NO</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ID TRANSAKSI</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">PELANGGAN</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">WAKTU</th>
                                <th class="px-8 py-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">METODE</th>
                                <th class="px-8 py-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">TOTAL</th>
                                <th class="px-8 py-6 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">STATUS</th>
                                <th class="px-8 py-6 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">OPSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white/60 backdrop-blur-md">
                            @forelse($transaksis as $trx)
                                <tr class="hover:bg-orange-50/40 transition-colors group">
                                    
                                    <td class="px-8 py-5 text-center font-bold text-gray-300 text-xs">
                                        {{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}
                                    </td>

                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 shadow-sm group-hover:text-[#FF6B35] group-hover:border-orange-100 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <span class="font-black text-gray-700 text-sm font-mono tracking-wide group-hover:text-[#FF6B35] transition-colors">
                                                {{ $trx->kode_transaksi }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <span class="font-bold text-gray-900 text-sm">
                                            {{ $trx->nama_pelanggan ?? 'Umum' }}
                                        </span>
                                    </td>

                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 mt-0.5 uppercase tracking-wide">{{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }} WIB</div>
                                    </td>

                                    <td class="px-8 py-5 whitespace-nowrap text-center">
                                        @if($trx->metode_pembayaran == 'Cash')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-black bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-wider">
                                                💵 Tunai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-black bg-purple-50 text-purple-600 border border-purple-100 uppercase tracking-wider">
                                                📱 Digital
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-8 py-5 whitespace-nowrap text-right">
                                        <span class="font-black text-gray-900 text-sm">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</span>
                                    </td>

                                    <td class="px-8 py-5 whitespace-nowrap text-center">
                                        @if($trx->status_pembayaran == 'Lunas' || $trx->status_pembayaran == 'success' || $trx->status_pembayaran == 'paid' || $trx->status_pembayaran == 'settlement' || $trx->bayar >= $trx->total_harga)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black bg-green-100 text-green-700 border border-green-200 uppercase tracking-wider shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black bg-yellow-100 text-yellow-700 border border-yellow-200 uppercase tracking-wider shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Pending
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-8 py-5 whitespace-nowrap text-center">
                                        <a href="{{ route('transaksi.show', $trx->id) }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-600 text-[10px] font-black uppercase tracking-widest hover:bg-[#FF6B35] hover:text-white hover:border-orange-200 transition-all shadow-sm hover:shadow-lg group/btn">
                                            Detail
                                            <svg class="w-3 h-3 ml-1 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-50">
                                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-dashed border-gray-200">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            </div>
                                            <h3 class="text-lg font-black text-gray-900">Belum ada riwayat</h3>
                                            <p class="text-sm font-medium text-gray-400 mt-1">Transaksi penjualan akan muncul di sini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-8 py-6 border-t border-gray-100 bg-white/50 backdrop-blur-sm">
                    {{ $transaksis->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>