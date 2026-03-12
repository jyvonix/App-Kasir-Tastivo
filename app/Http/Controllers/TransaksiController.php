<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Kategori;
use App\Models\PengaturanToko;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

use App\Models\Promo; // Tambahkan Model Promo

class TransaksiController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        Config::$curlOptions = config('midtrans.curl_options');
    }

    // =========================================================================
    // 1. HALAMAN KASIR (POS)
    // =========================================================================
    public function index(Request $request)
    {
        // 1. Ambil Kategori untuk Filter
        $kategoris = Kategori::all();

        // 2. Query Produk (Aktif & Stok > 0)
        $query = Produk::where('status', true)->where('stok', '>', 0);

        // Filter by Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter by Search (Nama / Kode)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%");
            });
        }

        $produks = $query->paginate(12); // Pagination agar tidak berat

        // 3. Ambil Keranjang dari Session
        $cart = session()->get('cart', []);
        
        // 4. Hitung Total Sementara
        $summary = $this->calculateTotals($cart);

        // 5. Pengaturan Toko (Pajak, Diskon Member, dll jika ada)
        $setting = PengaturanToko::first();

        return view('transaksi.index', compact('produks', 'kategoris', 'cart', 'summary', 'setting'));
    }

    // =========================================================================
    // 2. LOGIKA KERANJANG (AJAX)
    // =========================================================================
    public function tambahItem(Request $request)
    {
        $id = $request->id;
        $produk = Produk::find($id);

        if(!$produk) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
        }

        if($produk->stok < 1) {
            return response()->json(['status' => 'error', 'message' => 'Stok habis!']);
        }

        $cart = session()->get('cart', []);

        // Cek jika sudah ada di keranjang
        if(isset($cart[$id])) {
            // Cek stok lagi
            if($produk->stok < ($cart[$id]['qty'] + 1)) {
                return response()->json(['status' => 'error', 'message' => 'Stok tidak cukup!']);
            }
            $cart[$id]['qty']++;
        } else {
            $cart[$id] = [
                "nama" => $produk->nama_produk,
                "qty" => 1,
                "price" => $produk->harga_jual,
                "image" => $produk->gambar_url ?? $produk->gambar_file, 
                "stok_max" => $produk->stok 
            ];
        }

        session()->put('cart', $cart);
        
        // Recalculate totals
        $summary = $this->calculateTotals($cart);
        
        // Render View HTML
        $html = view('transaksi.components.cart_item', compact('cart'))->render();

        return response()->json([
            'status' => 'success', 
            'message' => 'Produk ditambahkan!', 
            'cart' => $cart,
            'html' => $html,
            'totals' => $summary
        ]);
    }
    
    public function updateQty(Request $request)
    {
        if($request->id && ($request->qty || $request->action)){
            $cart = session()->get('cart');
            
            // Validasi Stok Real-time
            $produk = Produk::find($request->id);
            if (!$produk) {
                return response()->json(['status' => 'error', 'message' => 'Produk tidak valid']);
            }
            
            // Handle Action (Plus/Minus) or Direct Qty
            $currentQty = isset($cart[$request->id]) ? $cart[$request->id]['qty'] : 0;
            $newQty = $request->qty; // Default if direct qty provided

            if ($request->action == 'plus') {
                $newQty = $currentQty + 1;
            } elseif ($request->action == 'minus') {
                $newQty = $currentQty - 1;
            }
            
            // Validate New Qty
            if ($newQty > $produk->stok) {
                 return response()->json(['status' => 'error', 'message' => 'Stok maksimal hanya ' . $produk->stok]);
            }
            
            if ($newQty < 1) {
                unset($cart[$request->id]);
            } else {
                $cart[$request->id]['qty'] = $newQty;
            }
            
            session()->put('cart', $cart);
            
            $summary = $this->calculateTotals($cart);
            $html = view('transaksi.components.cart_item', compact('cart'))->render();
            
            return response()->json([
                'status' => 'success', 
                'cart' => $cart,
                'html' => $html,
                'totals' => $summary
            ]);
        }
    }

    public function hapusItem($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        $summary = $this->calculateTotals($cart);
        $html = view('transaksi.components.cart_item', compact('cart'))->render();
        
        return response()->json([
            'status' => 'success',
            'cart' => $cart,
            'html' => $html,
            'totals' => $summary
        ]);
    }
    
    public function resetKeranjang()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang dikosongkan.');
    }

    /**
     * Helper: Hitung Subtotal, Pajak, Total
     */
    private function calculateTotals($cart)
    {
        $subTotal = 0;
        foreach($cart as $item) {
            $subTotal += $item['price'] * $item['qty'];
        }

        // Ambil Pengaturan Pajak
        $setting = PengaturanToko::first();
        $pajakPersen = $setting ? $setting->pajak_persen : 0;
        
        // Cek Diskon Toko Global (Misal: Event Tertentu)
        // Diskon ini berbeda dengan Voucher. Ini otomatis.
        $diskonToko = 0; 
        if ($setting && $setting->diskon_global_persen > 0) {
             $diskonToko = ($subTotal * $setting->diskon_global_persen) / 100;
        }

        // Dasar Pengenaan Pajak (DPP)
        // Opsional: Apakah pajak dikenakan SEBELUM atau SESUDAH diskon toko?
        // Umumnya Sesudah.
        $dpp = $subTotal - $diskonToko;
        if($dpp < 0) $dpp = 0;

        $pajak = ($dpp * $pajakPersen) / 100;
        $total = $dpp + $pajak;

        return [
            'subTotal' => $subTotal,
            'diskon' => $diskonToko, // Diskon Toko (Belum termasuk Voucher)
            'pajak' => $pajak,
            'total' => $total,
            'pajak_persen' => $pajakPersen
        ];
    }

    // =========================================================================
    // 3. PROSES BAYAR / CHECKOUT
    // =========================================================================
    public function store(Request $request)
    {
        // Hilangkan format rupiah
        $rawBayar = preg_replace('/[^0-9]/', '', $request->uang_dibayar);
        $bayar = $rawBayar ? (int)$rawBayar : 0;
        
        $request->merge(['bayar' => $bayar]);

        $request->validate([
            'bayar' => 'numeric',
            'nama_pelanggan' => 'required|string',
            'kode_promo' => 'nullable|string'
        ]);

        $cart = session()->get('cart');
        if(!$cart) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            $totals = $this->calculateTotals($cart);
            
            // --- LOGIKA DISKON VOUCHER SERVER-SIDE (KEAMANAN) ---
            $potonganVoucher = 0;
            $kodePromo = null;

            if ($request->filled('kode_promo')) {
                $promo = Promo::where('kode', $request->kode_promo)->first();
                if ($promo && $promo->isValid() && $totals['subTotal'] >= $promo->minimum_belanja) {
                    
                    if ($promo->tipe == 'nominal') {
                        $potonganVoucher = $promo->nilai;
                    } else {
                        $potonganVoucher = ($totals['subTotal'] * $promo->nilai) / 100;
                        if ($promo->maksimum_potongan && $potonganVoucher > $promo->maksimum_potongan) {
                            $potonganVoucher = $promo->maksimum_potongan;
                        }
                    }
                    
                    if ($potonganVoucher > $totals['subTotal']) $potonganVoucher = $totals['subTotal'];
                    
                    $kodePromo = $promo->kode;
                }
            }

            // Hitung Ulang Total Bayar dengan Voucher
            // Total = Subtotal + Pajak - DiskonToko - DiskonVoucher
            // *Catatan: Pajak dihitung dari Subtotal bersih atau kotor tergantung kebijakan. 
            // Di sini kita pakai logic: Pajak dihitung dari (Subtotal - DiskonToko - Voucher) agar adil.
            
            $subTotalBersih = $totals['subTotal'] - $totals['diskon'] - $potonganVoucher;
            if ($subTotalBersih < 0) $subTotalBersih = 0;

            // Recalculate Pajak based on final taxable amount
            $setting = PengaturanToko::first();
            $pajakPersen = $setting ? $setting->pajak_persen : 0;
            $pajakBaru = ($subTotalBersih * $pajakPersen) / 100;
            
            $totalHarga = $subTotalBersih + $pajakBaru;
            
            // Gabungkan diskon toko + voucher untuk disimpan di kolom 'diskon'
            $totalDiskonGabungan = $totals['diskon'] + $potonganVoucher;
            
            // --- SELESAI HITUNG ULANG ---

            $isCash = $bayar > 0;
            $statusPembayaran = 'Pending';
            $metodePembayaran = 'Qris / Cashless';
            $snapToken = null;

            if ($isCash) {
                if($bayar < $totalHarga) {
                    return redirect()->back()->with('error', 'Uang pembayaran kurang!');
                }
                $statusPembayaran = 'Lunas';
                $metodePembayaran = 'Cash';
            }

            // Generate Kode TRX
            $kodeTrx = 'TRX-' . strtoupper(Str::random(10));

            // Buat Transaksi
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'kode_transaksi' => $kodeTrx,
                'nama_pelanggan' => $request->nama_pelanggan,
                'total_harga' => $totalHarga,
                'diskon' => $totalDiskonGabungan, // Diskon Toko + Voucher
                'kode_promo' => $kodePromo,      // Simpan Kode Promo
                'pajak' => $pajakBaru,
                'bayar' => $bayar, 
                'kembalian' => $isCash ? ($bayar - $totalHarga) : 0,
                'metode_pembayaran' => $metodePembayaran, 
                'status_pembayaran' => $statusPembayaran,
                'tanggal' => now(),
            ]);
            
            // UPDATE PENGGUNAAN VOUCHER
            if ($kodePromo) {
                Promo::where('kode', $kodePromo)->increment('jumlah_klaim');
            }

            // Simpan Detail & Kurangi Stok
            foreach($cart as $id => $item) {
                $produk = Produk::lockForUpdate()->find($id);
                if($produk->stok < $item['qty']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi saat proses akhir.");
                }

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $id,
                    'nama_produk' => $item['nama'],
                    'jumlah' => $item['qty'],
                    'harga_satuan' => $item['price'],
                    'diskon_item' => 0,
                    'sub_total_produk' => $item['price'] * $item['qty'],
                ]);

                $produk->decrement('stok', $item['qty']);
            }

            // JIKA CASHLESS: Minta Snap Token ke Midtrans
            if (!$isCash) {
                $midtransItems = [[
                    'id' => 'TRX-TOTAL',
                    'price' => (int) $totalHarga,
                    'quantity' => 1,
                    'name' => 'Total Belanja ' . $kodeTrx
                ]];

                $params = [
                    'transaction_details' => [
                        'order_id' => $kodeTrx,
                        'gross_amount' => (int) $totalHarga,
                    ],
                    'customer_details' => [
                        'first_name' => $request->nama_pelanggan,
                        'email' => 'guest@tastivo.local',
                    ],
                    'item_details' => $midtransItems,
                ];

                // TRY CATCH untuk Token: Jika gagal, transaksi TETAP TERCIPTA tapi tanpa token
                try {
                    $snapToken = Snap::getSnapToken($params);
                    $transaksi->snap_token = $snapToken;
                    $transaksi->save();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Midtrans Store Error: " . $e->getMessage());
                    // Tidak throw error agar transaksi tetap tersimpan
                }
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('transaksi.show', $transaksi->id)->with('success', 'Transaksi Berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 4. RIWAYAT & DETAIL
    // =========================================================================
    public function riwayat()
    {
        $transaksis = Transaksi::where('user_id', Auth::id())->latest()->paginate(10);
        return view('transaksi.riwayat', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);
        $midtransError = null;

        // Self-Healing: Jika belum lunas dan tidak ada token, coba generate ulang
        if ($transaksi->status_pembayaran != 'Lunas' && empty($transaksi->snap_token) && $transaksi->bayar == 0) {
            
            $midtransItems = [[
                'id' => 'TRX-TOTAL',
                'price' => (int) $transaksi->total_harga,
                'quantity' => 1,
                'name' => 'Total Belanja ' . $transaksi->kode_transaksi
            ]];

            $orderId = $transaksi->kode_transaksi . '-' . Str::random(3);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $transaksi->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $transaksi->nama_pelanggan,
                    'email' => 'guest@tastivo.local',
                ],
                'item_details' => $midtransItems,
            ];

            try {
                if(empty(Config::$serverKey)) {
                    throw new \Exception("Server Key Midtrans belum disetting.");
                }

                $snapToken = Snap::getSnapToken($params);
                $transaksi->snap_token = $snapToken;
                
                // [FIX] Update kode_transaksi di database agar sesuai dengan Order ID baru di Midtrans
                $transaksi->kode_transaksi = $orderId;
                
                $transaksi->save();
                $transaksi->refresh(); 
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Midtrans Show Error: " . $e->getMessage());
                $midtransError = $e->getMessage();
            }
        }

        return view('transaksi.detail', compact('transaksi', 'midtransError'));
    }
    
    public function cekStatus($id) {
        $transaksi = Transaksi::findOrFail($id);
        
        // [FIX] Jika status lokal sudah Lunas, tidak perlu cek ke Midtrans lagi (hindari error 404 jika ID mismatch)
        if ($transaksi->status_pembayaran == 'Lunas') {
            return redirect()->route('transaksi.show', $id)->with('success', 'Status: Pembayaran Berhasil (Lunas)');
        }

        try {
            // Cek status ke Midtrans menggunakan Kode Transaksi sebagai Order ID
            $status = Transaction::status($transaksi->kode_transaksi);
            
            // Logika Mapping Status Midtrans ke Status Aplikasi
            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status;

            $newStatus = null;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $newStatus = 'Challenge';
                } else {
                    $newStatus = 'Lunas';
                }
            } else if ($transactionStatus == 'settlement') {
                $newStatus = 'Lunas';
            } else if ($transactionStatus == 'pending') {
                $newStatus = 'Pending';
            } else if ($transactionStatus == 'deny') {
                $newStatus = 'Gagal';
            } else if ($transactionStatus == 'expire') {
                $newStatus = 'Kadaluarsa';
            } else if ($transactionStatus == 'cancel') {
                $newStatus = 'Dibatalkan';
            }

            if ($newStatus) {
                $transaksi->status_pembayaran = $newStatus;
                $transaksi->save();
            }

            if ($newStatus == 'Lunas') {
                 return redirect()->route('transaksi.show', $id)->with('success', 'Status: Pembayaran Berhasil (Lunas)');
            } else {
                 return redirect()->route('transaksi.show', $id)->with('info', 'Status saat ini: ' . ucfirst($transactionStatus));
            }

        } catch (\Exception $e) {
            // Jika error 404 (transaksi tidak ditemukan di Midtrans), mungkin karena belum dibayar sama sekali
            if (strpos($e->getMessage(), '404') !== false) {
                return redirect()->route('transaksi.show', $id)->with('info', 'Belum ada pembayaran yang terdeteksi di Midtrans.');
            }
            return redirect()->route('transaksi.show', $id)->with('error', 'Gagal cek status: ' . $e->getMessage());
        }
    }

    public function pembayaranSukses($id) {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status_pembayaran = 'Lunas';
        $transaksi->save();
        return view('transaksi.sukses', compact('id'));
    }
}