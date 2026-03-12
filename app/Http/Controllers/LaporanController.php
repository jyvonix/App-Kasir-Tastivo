<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index() {
        return redirect()->route('laporan.keuangan');
    }

    public function keuangan(Request $request) {
        // Filter Tanggal
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();
        
        $validStatus = ['Lunas', 'success', 'paid', 'settlement'];

        // Query Dasar
        $query = Transaksi::whereBetween('tanggal', [$startDate, $endDate])
            ->whereIn('status_pembayaran', $validStatus);

        // 1. METRIK KARTU UTAMA (BREAKDOWN WAKTU)
        $pendapatanTotal = $query->sum('total_harga'); // Sesuai Filter
        
        $pendapatanHariIni = Transaksi::whereDate('tanggal', Carbon::today())
            ->whereIn('status_pembayaran', $validStatus)->sum('total_harga');
            
        $pendapatanMingguIni = Transaksi::whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->whereIn('status_pembayaran', $validStatus)->sum('total_harga');
            
        $pendapatanBulanIni = Transaksi::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereIn('status_pembayaran', $validStatus)->sum('total_harga');
            
        $pendapatanTahunIni = Transaksi::whereYear('tanggal', Carbon::now()->year)
            ->whereIn('status_pembayaran', $validStatus)->sum('total_harga');

        // Statistik Tambahan
        $totalTransaksi = $query->count();
        $rataRataTransaksi = $totalTransaksi > 0 ? $pendapatanTotal / $totalTransaksi : 0;
        
        // Item Terjual (Join dengan detail)
        $totalItemTerjual = DB::table('transaksi_details')
            ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->whereIn('transaksis.status_pembayaran', $validStatus)
            ->sum('transaksi_details.jumlah');
            
        // Produk Terlaris (Top 5)
        $produkTerlaris = DB::table('transaksi_details')
            ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
            ->select('transaksi_details.nama_produk', DB::raw('SUM(transaksi_details.jumlah) as total_qty'), DB::raw('SUM(transaksi_details.sub_total_produk) as total_revenue'))
            ->whereBetween('transaksis.tanggal', [$startDate, $endDate])
            ->whereIn('transaksis.status_pembayaran', $validStatus)
            ->groupBy('transaksi_details.nama_produk')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 2. DATA GRAFIK (Harian)
        $grafikData = Transaksi::selectRaw('DATE(tanggal) as date, SUM(total_harga) as total')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereIn('status_pembayaran', $validStatus)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // 3. RIWAYAT TRANSAKSI (Tabel)
        $riwayatTransaksi = Transaksi::with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereIn('status_pembayaran', $validStatus)
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('owner.laporan.keuangan', compact(
            'pendapatanTotal', 
            'pendapatanHariIni',
            'pendapatanMingguIni',
            'pendapatanBulanIni',
            'pendapatanTahunIni',
            'totalTransaksi',
            'rataRataTransaksi',
            'totalItemTerjual',
            'produkTerlaris', // Tambahan Laporan Produk
            'grafikData', 
            'riwayatTransaksi',
            'startDate',
            'endDate'
        ));
    }

    public function stok(Request $request) {
        // Query Dasar
        $query = Produk::query();

        // Filter Pencarian
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%')
                  ->orWhereHas('kategori', function($q) use ($request) {
                      $q->where('nama_kategori', 'like', '%' . $request->search . '%');
                  });
        }

        // 1. METRIK KARTU UTAMA
        $totalProduk = $query->count();
        $totalStokFisik = $query->sum('stok');
        $itemKritisCount = (clone $query)->where('stok', '<', 10)->count();
        $potensiOmset = $query->get()->sum(function($produk) {
            return $produk->harga_jual * $produk->stok;
        });

        // 2. DATA TABEL
        $stokMenipis = (clone $query)->where('stok', '<', 10)->orderBy('stok', 'asc')->get();
        $semuaStok = $query->with('kategori')->orderBy('stok', 'asc')->paginate(10)->withQueryString();

        // JIKA AJAX (Real-time Search)
        if ($request->ajax()) {
            return view('owner.laporan.stok', compact(
                'totalProduk', 'totalStokFisik', 'itemKritisCount', 'potensiOmset', 'stokMenipis', 'semuaStok'
            ))->renderSections()['content'];
        }

        return view('owner.laporan.stok', compact(
            'totalProduk', 'totalStokFisik', 'itemKritisCount', 'potensiOmset', 'stokMenipis', 'semuaStok'
        ));
    }
}