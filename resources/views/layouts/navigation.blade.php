<div class="flex flex-col h-full py-6 px-4">
    
    <!-- BRAND HEADER -->
    <div class="flex items-center gap-3 px-3 mb-10">
        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-500/20 transform rotate-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight leading-none">TASTIVO</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">Manager</p>
        </div>
    </div>

    <!-- NAVIGATION LINKS -->
    <div class="flex-1 overflow-y-auto space-y-1 custom-scrollbar -mr-2 pr-2">

        <div class="px-3 mb-2 mt-2">
            <span class="text-[10px] font-extrabold text-gray-300 uppercase tracking-widest">Utama</span>
        </div>
        
        <a href="{{ route('dashboard') }}" 
           class="nav-item group {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="icon-box">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
            <span class="font-bold">Dashboard</span>
            @if(request()->routeIs('dashboard'))
                <span class="active-dot"></span>
            @endif
        </a>

        <!-- ADMIN MENU -->
        @if (auth()->user()->role === 'admin')
            <div class="px-3 mb-2 mt-6">
                <span class="text-[10px] font-extrabold text-gray-300 uppercase tracking-widest">Administrasi</span>
            </div>
            
            <a href="{{ route('attendance.index') }}" class="nav-item group {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span>Absensi</span>
            </a>
            
            <a href="{{ route('produk.index') }}" class="nav-item group {{ request()->routeIs('produk*') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span>Produk</span>
            </a>

            <a href="{{ route('kategori.index') }}" class="nav-item group {{ request()->routeIs('kategori*') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                </div>
                <span>Kategori</span>
            </a>

            <a href="{{ route('pegawai.index') }}" class="nav-item group {{ request()->routeIs('pegawai*') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span>Pegawai</span>
            </a>

            <a href="{{ route('promo.index') }}" class="nav-item group {{ request()->routeIs('promo*') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <span>Promo</span>
            </a>

            <a href="{{ route('attendance.monitoring') }}" class="nav-item group {{ request()->routeIs('attendance.monitoring') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 01-2 2z"></path></svg>
                </div>
                <span>Rekap</span>
            </a>

            <a href="{{ route('pengaturan.index') }}" class="nav-item group {{ request()->routeIs('pengaturan*') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <span>Setting</span>
            </a>
        @endif

        <!-- KASIR MENU -->
        @if (auth()->user()->role === 'kasir')
            <div class="px-3 mb-2 mt-6">
                <span class="text-[10px] font-extrabold text-gray-300 uppercase tracking-widest">Kasir Area</span>
            </div>

            <a href="{{ route('attendance.index') }}" class="nav-item group {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span>Absensi Staf</span>
            </a>
            
            <a href="{{ route('transaksi.index') }}" class="nav-item group {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <span>POS Kasir</span>
            </a>

            <a href="{{ route('transaksi.riwayat') }}" class="nav-item group {{ request()->routeIs('transaksi.riwayat') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span>Riwayat</span>
            </a>

            <a href="{{ route('attendance.scan') }}" target="_blank" class="nav-item group">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H6v-4h6v4m0-6v-4m0 0h6m-6 0H6"></path></svg>
                </div>
                <span>Scanner</span>
            </a>

            <a href="{{ route('produk.index') }}" class="nav-item group {{ request()->routeIs('produk*') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                </div>
                <span>Cek Stok</span>
            </a>

            <a href="{{ route('promo.index') }}" class="nav-item group {{ request()->routeIs('promo*') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <span>Promo & Voucher</span>
            </a>
        @endif

        <!-- OWNER MENU -->
        @if (auth()->user()->role === 'owner')
            <div class="px-3 mb-2 mt-6">
                <span class="text-[10px] font-extrabold text-gray-300 uppercase tracking-widest">Pemilik</span>
            </div>
            
            <a href="{{ route('owner.approval') }}" class="nav-item group {{ request()->routeIs('owner.approval') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span>Approval</span>
            </a>

            <a href="{{ route('laporan.index') }}" class="nav-item group {{ request()->routeIs('laporan*') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                </div>
                <span>Laporan</span>
            </a>

            <a href="{{ route('attendance.monitoring') }}" class="nav-item group {{ request()->routeIs('attendance.monitoring') ? 'active' : '' }}">
                <div class="icon-box">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <span>Rekap Absen</span>
            </a>
        @endif

    </div>

    <!-- USER PROFILE (Mini) -->
    <div class="mt-6 pt-6 border-t border-gray-100">
        <div class="flex items-center gap-3 px-2">
            <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=f97316&color=fff' }}" 
                 class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase truncate">{{ Auth::user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <style>
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            margin-bottom: 4px;
            border-radius: 14px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b; /* slate-500 */
            transition: all 0.2s ease-out;
            position: relative;
        }
        .nav-item:hover {
            background-color: #fff7ed; /* orange-50 */
            color: #ea580c; /* orange-600 */
        }
        .nav-item.active {
            background-color: #fff7ed; /* orange-50 */
            color: #ea580c; /* orange-600 */
        }
        .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background-color: #f8fafc; /* slate-50 */
            color: #94a3b8; /* slate-400 */
            transition: all 0.2s;
        }
        .nav-item:hover .icon-box {
            background-color: #fed7aa; /* orange-200 (sedikit lebih gelap dr bg) */
            color: #c2410c; /* orange-700 */
        }
        .nav-item.active .icon-box {
            background-color: #ea580c; /* orange-600 */
            color: white;
            box-shadow: 0 4px 6px -1px rgba(234, 88, 12, 0.2);
        }
        .active-dot {
            position: absolute;
            right: 12px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #ea580c;
        }
    </style>
</div>