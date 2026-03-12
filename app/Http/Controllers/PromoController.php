<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Produk;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Tampilkan daftar promo
     */
    public function index()
    {
        $promos = Promo::with('produk')->latest()->get();
        return view('admin.promo.index', compact('promos'));
    }

    /**
     * Form tambah promo
     */
    public function create()
    {
        $produks = Produk::all(); // Untuk pilihan promo khusus produk
        return view('admin.promo.create', compact('produks'));
    }

    /**
     * Simpan promo baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:promos,kode|uppercase|alpha_num',
            'tipe' => 'required|in:persen,nominal',
            'nilai' => 'required|numeric|min:0',
            'minimum_belanja' => 'nullable|numeric|min:0',
            'limit_type' => 'required|in:unlimited,limited', // Form Helper
            'batas_pemakaian' => 'nullable|numeric|min:1',
        ]);

        $data = $request->all();

        // Handle Unlimited Logic
        if ($request->limit_type === 'unlimited') {
            $data['batas_pemakaian'] = null;
        }

        Promo::create($data);

        return redirect()->route('promo.index')->with('success', 'Kode Promo berhasil dibuat!');
    }

    /**
     * Update promo
     */
    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'kode' => 'required|uppercase|alpha_num|unique:promos,kode,' . $promo->id,
            'tipe' => 'required|in:persen,nominal',
            'nilai' => 'required|numeric|min:0',
            'limit_type' => 'required|in:unlimited,limited',
        ]);

        $data = $request->all();
        
        // Handle Unlimited Logic
        if ($request->limit_type === 'unlimited') {
            $data['batas_pemakaian'] = null;
        }

        $promo->update($data);

        return redirect()->route('promo.index')->with('success', 'Promo diperbarui.');
    }

    /**
     * Hapus promo
     */
    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('promo.index')->with('success', 'Promo dihapus.');
    }
    
    /**
     * API untuk Cek Promo di Kasir (PENTING)
     */
    public function checkPromo(Request $request)
    {
        $kode = $request->kode;
        $totalBelanja = $request->total_belanja; // Subtotal belanjaan saat ini
        
        $promo = Promo::where('kode', $kode)->first();

        // 1. Cek Ketersediaan
        if (!$promo) {
            return response()->json(['status' => 'error', 'message' => 'Kode promo tidak ditemukan!']);
        }

        // 2. Cek Validitas Tanggal & Aktif
        if (!$promo->is_active) {
            return response()->json(['status' => 'error', 'message' => 'Kode promo sedang dinonaktifkan.']);
        }
        
        $today = \Carbon\Carbon::today();
        if ($promo->mulai_berlaku && $today->lt($promo->mulai_berlaku)) {
             return response()->json(['status' => 'error', 'message' => 'Promo belum dimulai.']);
        }
        if ($promo->berakhir_pada && $today->gt($promo->berakhir_pada)) {
             return response()->json(['status' => 'error', 'message' => 'Promo sudah kadaluarsa.']);
        }

        // 3. Cek Kuota / Batas Pemakaian
        if ($promo->batas_pemakaian !== null && $promo->jumlah_klaim >= $promo->batas_pemakaian) {
            return response()->json(['status' => 'error', 'message' => 'Yah, Kuota Voucher ini sudah habis!']);
        }

        // 4. Cek Minimum Belanja
        if ($totalBelanja < $promo->minimum_belanja) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Belum memenuhi syarat. Min. belanja Rp ' . number_format($promo->minimum_belanja, 0, ',', '.')
            ]);
        }

        // 5. Hitung Diskon
        $potongan = 0;
        if ($promo->tipe == 'nominal') {
            $potongan = $promo->nilai;
        } else {
            // Persen
            $potongan = ($totalBelanja * $promo->nilai) / 100;
            // Cek Max Potongan
            if ($promo->maksimum_potongan && $potongan > $promo->maksimum_potongan) {
                $potongan = $promo->maksimum_potongan;
            }
        }

        // Pastikan potongan tidak lebih besar dari total belanja (biar gak minus)
        if ($potongan > $totalBelanja) {
            $potongan = $totalBelanja;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Kode Voucher Dipakai!',
            'kode' => $promo->kode,
            'tipe' => $promo->tipe,
            'nilai_asli' => $promo->nilai,
            'potongan' => $potongan, // Nominal Rupiah yang dipotong
            'promo_id' => $promo->id
        ]);
    }
}