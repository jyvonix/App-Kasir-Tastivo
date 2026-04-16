<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\User;
use App\Models\Attendance;
use App\Models\TransaksiDetail;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan data sesuai role.
     */
    public function index()
    {
        $user = Auth::user();

        // LOGIC KHUSUS OWNER
        if ($user->hasRole('owner')) {
            $validStatus = ['Lunas', 'success', 'paid', 'settlement'];

            // 1. Keuangan (Omset)
            $incomeToday = Transaksi::whereDate('tanggal', Carbon::today())
                ->whereIn('status_pembayaran', $validStatus)
                ->sum('total_harga');

            $incomeMonth = Transaksi::whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->whereIn('status_pembayaran', $validStatus)
                ->sum('total_harga');
                
            $incomeYear = Transaksi::whereYear('tanggal', Carbon::now()->year)
                ->whereIn('status_pembayaran', $validStatus)
                ->sum('total_harga');

            // 2. Top Produk (Berdasarkan jumlah terjual)
            $topProducts = TransaksiDetail::select('produk_id', DB::raw('SUM(jumlah) as total_sold'))
                ->whereHas('transaksi', function($q) use ($validStatus) {
                    $q->whereIn('status_pembayaran', $validStatus);
                })
                ->with('produk')
                ->groupBy('produk_id')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();

            // 3. Absensi Hari Ini
            $attendanceToday = Attendance::whereDate('tanggal', Carbon::today())
                ->with('user')
                ->latest()
                ->get();
            
            // Jika hari ini kosong, kita tetap ingin tahu total pegawai untuk alphaCount
            $totalPegawai = User::whereIn('role', ['admin', 'kasir'])->count();
            $hadirCount = $attendanceToday->where('status', 'hadir')->count();
            $telatCount = $attendanceToday->where('status', 'telat')->count();
            $izinCount = $attendanceToday->whereIn('status', ['izin', 'sakit'])->count();
            $alphaCount = max(0, $totalPegawai - ($hadirCount + $telatCount + $izinCount));

            // 4. Grafik Penjualan (7 Hari Terakhir) - OPTIMIZED: 1 Query vs 7 Queries
            $chartLabels = [];
            $chartData = [];
            
            $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
            $dailySales = Transaksi::where('tanggal', '>=', $sevenDaysAgo)
                ->whereIn('status_pembayaran', $validStatus)
                ->select(DB::raw('DATE(tanggal) as date'), DB::raw('SUM(total_harga) as total'))
                ->groupBy('date')
                ->get()
                ->pluck('total', 'date');

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $label = Carbon::now()->subDays($i)->format('d M');
                $chartLabels[] = $label;
                $chartData[] = (float) ($dailySales[$date] ?? 0);
            }

            return view('owner.dashboard', compact(
                'incomeToday', 'incomeMonth', 'incomeYear',
                'topProducts',
                'attendanceToday', 'hadirCount', 'telatCount', 'izinCount', 'alphaCount',
                'chartLabels', 'chartData'
            ));
        }

        // LOGIC UMUM (ADMIN / KASIR)
        $totalTransaksi = Transaksi::count(); 
        $totalProduk = Produk::count();
        $transaksiTerbaru = Transaksi::with('user')->latest()->take(5)->get();
        
        // Cek Stok Menipis
        $stokMenipis = Produk::where('stok', '<', 10)->get();

        return view('dashboard', compact('totalTransaksi', 'totalProduk', 'transaksiTerbaru', 'stokMenipis'));
    }
}