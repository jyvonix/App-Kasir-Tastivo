<h1>INI FILE SIDEBAR</h1>

<ul class="nav-list">
    
    
    <li class="nav-item">
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>

    @if(in_array(Auth::user()->role, ['admin', 'kasir']))
        <li class="nav-item">
            <a href="{{ route('attendance.index') }}">Absensi</a>
        </li>
    @endif

    @if(Auth::user()->role == 'admin')
        <li class="nav-item">
            <a href="{{ route('pegawai.index') }}">Pegawai</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pengaturan.index') }}">Pengaturan</a>
        </li>
    @endif

    @if(Auth::user()->role == 'kasir')
        <li class="nav-item">
            <a href="{{ route('transaksi.index') }}">Transaksi</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('transaksi.riwayat') }}">Detail Transaksi</a> 
            </li>
        <li class="nav-item">
            <a href="{{ route('menu.index') }}">Menu</a>
        </li>
    @endif

    @if(Auth::user()->role == 'owner')
        <li class="nav-item">
            <a href="{{ route('laporan.index') }}">Laporan</a>
        </li>
    @endif

    @if(in_array(Auth::user()->role, ['admin', 'kasir']))
        <li class="nav-item">
            <a href="{{ route('produk.index') }}">Daftar Produk</a>
        </li>
    @endif

</ul>