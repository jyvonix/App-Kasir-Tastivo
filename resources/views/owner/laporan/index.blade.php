<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tastivo - Elite Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }
        .glass-header { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.8); }
        .premium-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .premium-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>
<body class="text-slate-800">

    <div class="flex">
        <aside class="w-64 bg-white min-h-screen shadow-sm flex flex-col p-6 sticky top-0">
            <div class="flex items-center gap-3 mb-10">
                <div class="bg-[#ff4d4d] p-2 rounded-xl shadow-md">
                    <i class="fas fa-shopping-basket text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-none">TASTIVO</h1>
                    <p class="text-[10px] text-gray-400 font-bold tracking-widest mt-1 uppercase">POINT OF SALE</p>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-3 mb-8 border border-gray-100">
                <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xs">PT</div>
                <div>
                    <h4 class="text-sm font-bold">Pemilik Toko</h4>
                    <span class="text-[9px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-md font-bold uppercase">OWNER</span>
                </div>
            </div>

            <nav class="space-y-1.5 flex-1">
                <p class="text-[10px] font-bold text-gray-400 uppercase mb-4 ml-2 tracking-widest">MAIN MENU</p>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl text-gray-500 hover:bg-orange-50 hover:text-orange-500 transition">
                    <i class="fas fa-th-large text-sm"></i>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>

                <p class="text-[10px] font-bold text-gray-400 uppercase mt-8 mb-4 ml-2 tracking-widest">OWNER</p>
                <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-orange-500 text-white shadow-lg shadow-orange-100 font-medium text-sm">
                    <i class="fas fa-file-invoice-dollar text-sm"></i>
                    <span>Laporan Keuangan</span>
                </a>
            </nav>

            <button class="bg-[#1a1c2e] text-white p-3.5 rounded-xl flex items-center justify-center gap-3 font-bold text-xs hover:bg-black transition-all mt-10 uppercase tracking-widest">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </aside>

        <main class="flex-1 p-10">
            <div class="max-w-6xl mx-auto">
                <div class="glass-header p-10 rounded-[32px] shadow-sm mb-10 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex gap-2 mb-4">
                            <span class="bg-purple-100 text-purple-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">Owner Area</span>
                            <span class="flex items-center gap-2 text-[10px] font-bold text-emerald-500 uppercase tracking-widest">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> System Online
                            </span>
                        </div>
                        <h2 class="text-5xl font-extrabold mb-3 tracking-tighter">Menu <span class="text-orange-500">Laporan.</span></h2>
                        <p class="text-slate-400 text-sm max-w-lg leading-relaxed">Kelola dan pantau seluruh aktivitas keuangan serta ketersediaan stok barang secara akurat.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <div class="bg-white p-6 rounded-[28px] premium-card border border-slate-50 group">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 mb-6 group-hover:bg-blue-500 group-hover:text-white transition-all">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-1 text-slate-800">Penjualan</h4>
                        <p class="text-xs text-slate-400 mb-6 font-medium">Rekapitulasi transaksi harian & mingguan.</p>
                        <a href="{{ route('laporan.penjualan') }}" class="text-blue-500 font-bold text-xs flex items-center gap-2 group-hover:gap-4 transition-all uppercase tracking-widest">
                            Detail <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="bg-white p-6 rounded-[28px] premium-card border border-slate-50 group">
                        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-1 text-slate-800">Laba & Rugi</h4>
                        <p class="text-xs text-slate-400 mb-6 font-medium">Analisis keuntungan bersih vs modal.</p>
                        <a href="{{ route('laporan.keuntungan') }}" class="text-emerald-500 font-bold text-xs flex items-center gap-2 group-hover:gap-4 transition-all uppercase tracking-widest">
                            Profit <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="bg-white p-6 rounded-[28px] premium-card border border-slate-50 group">
                        <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 mb-6 group-hover:bg-orange-500 group-hover:text-white transition-all">
                            <i class="fas fa-box"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-1 text-slate-800">Stok Produk</h4>
                        <p class="text-xs text-slate-400 mb-6 font-medium">Pantau inventaris & stok menipis.</p>
                        <a href="{{ route('laporan.stok') }}" class="text-orange-500 font-bold text-xs flex items-center gap-2 group-hover:gap-4 transition-all uppercase tracking-widest">
                            Cek Stok <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>~~

                    <div class="bg-[#1a1c2e] p-6 rounded-[28px] shadow-lg group">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-orange-400 mb-6">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-1 text-white">Arus Kas</h4>
                        <p class="text-xs text-slate-400 mb-6 font-medium">Ringkasan total uang masuk & keluar.</p>
                        <a href="{{ route('laporan.keuangan') }}" class="text-orange-400 font-bold text-xs flex items-center gap-2 group-hover:gap-4 transition-all uppercase tracking-widest">
                            Buka <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                </div>

                <div class="mt-8 bg-white/50 border border-slate-100 p-6 rounded-[24px]">
                    <p class="text-center text-slate-400 text-[11px] font-bold uppercase tracking-[0.2em]">Tastivo Point of Sale © 2025</p>
                </div>
            </div>
        </main>
    </div>

</body>
</html>