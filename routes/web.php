<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PegawaiController; 
use App\Http\Controllers\LaporanController; 
use App\Http\Controllers\TransaksiController; 
use App\Http\Controllers\SettingController; // Import Controller Setting
use App\Http\Controllers\DashboardController; // Import Controller Dashboard

use App\Models\Transaksi;
use App\Models\User;
use App\Models\Produk;
use App\Http\Controllers\PromoController; // Import Controller Promo
use Carbon\Carbon;

Route::get('/', function () {
    return redirect()->route('login'); 
});

// ====================================================
// GROUP 1: AUTH UMUM
// ====================================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('produk', ProdukController::class);
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class);

    // [TEMP] Route untuk isi kategori otomatis jika kosong (Akses: /seed-kategori)
    Route::get('/seed-kategori', function() {
        if (\App\Models\Kategori::count() === 0) {
            Artisan::call('db:seed', ['--class' => 'KategoriSeeder']);
            return redirect()->route('kategori.index')->with('success', '10 Kategori awal berhasil ditambahkan!');
        }
        return redirect()->route('kategori.index')->with('error', 'Tabel kategori sudah ada isinya.');
    });

    // 7. Absensi (Dipindahkan ke Auth Umum agar Admin & Kasir bisa akses)
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/scan', [App\Http\Controllers\AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('/attendance/scan', [App\Http\Controllers\AttendanceController::class, 'processScan'])->name('attendance.process_scan'); // Unified Scan Process
    
    // Fitur Izin/Sakit (Sekarang Mengarah ke ApprovalController)
    Route::get('/attendance/izin', [App\Http\Controllers\AttendanceController::class, 'createIzin'])->name('attendance.izin');
    Route::post('/attendance/izin', [App\Http\Controllers\ApprovalController::class, 'store'])->name('attendance.store_izin');
});

// ====================================================
// GROUP 2: KHUSUS ADMIN
// ====================================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class);
    
    // [TEMP] Route untuk isi kategori otomatis jika kosong (Akses: /seed-kategori)
    Route::get('/seed-kategori', function() {
        if (\App\Models\Kategori::count() === 0) {
            Artisan::call('db:seed', ['--class' => 'KategoriSeeder']);
            return redirect()->route('kategori.index')->with('success', '10 Kategori awal berhasil ditambahkan!');
        }
        return redirect()->route('kategori.index')->with('error', 'Tabel kategori sudah ada isinya.');
    });

    // [TEMP] Route untuk isi user otomatis (Akses: /seed-users)
    Route::get('/seed-users', function() {
        if (\App\Models\User::count() <= 1) { // Jika hanya ada 1 user (admin yg sedang login) atau 0
            Artisan::call('db:seed', ['--class' => 'UserSeeder']);
            return "Akun Default (Admin, Kasir, Owner) berhasil ditambahkan!";
        }
        return "Gagal: Tabel User sudah ada isinya.";
    });

    // [TEMP] Route untuk update database (Akses: /run-migrate)
    Route::get('/run-migrate', function() {
        Artisan::call('migrate', ['--force' => true]);
        return redirect()->route('kategori.index')->with('success', 'Struktur database berhasil diperbarui!');
    });

    Route::get('/pegawai/card/{id}', [App\Http\Controllers\AttendanceController::class, 'card'])->name('pegawai.card'); // New Card Printing Route
    
    // Routes Pengaturan Toko (PENTING)
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');
    Route::put('/pengaturan/shifts', [SettingController::class, 'updateShifts'])->name('pengaturan.update_shifts');
});

// ====================================================
// GROUP 3: KHUSUS KASIR
// ====================================================
Route::middleware(['auth', 'role:kasir'])->group(function () {
    
    // 1. Kasir (POS)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    
    // 2. Logika Keranjang (AJAX)
    Route::post('/transaksi/tambah', [TransaksiController::class, 'tambahItem'])->name('transaksi.tambah');
    Route::get('/transaksi/hapus/{id}', [TransaksiController::class, 'hapusItem'])->name('transaksi.hapus');
    
    // [FIX ERROR DI SINI] Tambahkan Route Update Qty
    Route::post('/transaksi/update-qty', [TransaksiController::class, 'updateQty'])->name('transaksi.update_qty');
    
    Route::get('/transaksi/reset', [TransaksiController::class, 'resetKeranjang'])->name('transaksi.reset');
    
    // 3. Proses Bayar & Midtrans
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    
    // 4. Riwayat & Struk
    
    // 5. Status & Callback
    Route::get('/transaksi/cek-status/{id}', [TransaksiController::class, 'cekStatus'])->name('transaksi.cek_status');
    Route::get('/transaksi/sukses/{id}', [TransaksiController::class, 'pembayaranSukses'])->name('transaksi.pembayaran_sukses');
    
    // 6. Menu Display
    Route::get('/menu', [ProdukController::class, 'menuDisplay'])->name('menu.index');
    
    // 7. Cek Promo (AJAX)
    Route::post('/promo/check', [PromoController::class, 'checkPromo'])->name('promo.check');
});

// ====================================================
// GROUP 4: KHUSUS OWNER
// ====================================================
Route::middleware(['auth', 'role:owner'])->group(function () {
    // Approval Izin/Sakit
    Route::get('/approval', [App\Http\Controllers\ApprovalController::class, 'index'])->name('owner.approval');
    Route::post('/approval/{id}', [App\Http\Controllers\ApprovalController::class, 'updateStatus'])->name('owner.approval.update');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
    });
});


// ====================================================
// GROUP 6: PROMO (Kombinasi Admin & Kasir)
// ====================================================
Route::middleware(['auth'])->group(function () {
    // KASIR & ADMIN: Hanya bisa Lihat Daftar & Detail
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
        Route::get('/promo/{promo}', [PromoController::class, 'show'])->name('promo.show');
    });

    // KHUSUS ADMIN: Bisa Create, Store, Edit, Update, Delete
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/promo/create', [PromoController::class, 'create'])->name('promo.create');
        Route::post('/promo', [PromoController::class, 'store'])->name('promo.store');
        Route::get('/promo/{promo}/edit', [PromoController::class, 'edit'])->name('promo.edit');
        Route::put('/promo/{promo}', [PromoController::class, 'update'])->name('promo.update');
        Route::delete('/promo/{promo}', [PromoController::class, 'destroy'])->name('promo.destroy');
    });
});

// ====================================================
// GROUP 5: KHUSUS ADMIN, OWNER & KASIR (Monitoring & QR)
// ====================================================
Route::middleware(['auth', 'role:admin,owner,kasir'])->group(function () {
    Route::get('/attendance/monitoring', [App\Http\Controllers\AttendanceController::class, 'monitoring'])->name('attendance.monitoring');
    
    // Detail Transaksi (Bisa diakses Owner dari Laporan, Kasir dari Riwayat, Admin jika perlu)
    Route::get('/transaksi/riwayat', [TransaksiController::class, 'riwayat'])->name('transaksi.riwayat');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
});

// ====================================================
// DEBUG ROUTE (HAPUS NANTI)
// ====================================================
Route::get('/midtrans-check', function () {
    $serverKey = config('midtrans.server_key');
    $isProduction = config('midtrans.is_production');
    
    // Setup Midtrans
    \Midtrans\Config::$serverKey = $serverKey;
    \Midtrans\Config::$isProduction = $isProduction;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;
    \Midtrans\Config::$curlOptions = [
        CURLOPT_SSL_VERIFYPEER => false, 
        CURLOPT_SSL_VERIFYHOST => 0,
        10023 => [] // Fix for Undefined array key 10023
    ];

    echo "<h1>Midtrans Connection Check</h1>";
    echo "<p><strong>Server Key:</strong> " . $serverKey . "</p>";
    echo "<p><strong>Environment:</strong> " . ($isProduction ? 'Production' : 'Sandbox') . "</p>";

    try {
        $params = [
            'transaction_details' => [
                'order_id' => 'CHECK-' . time(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'Debug',
                'email' => 'debug@example.com',
            ],
        ];
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        echo "<h2 style='color:green'>SUCCESS!</h2>";
        echo "<p>Snap Token: $snapToken</p>";
    } catch (\Exception $e) {
        echo "<h2 style='color:red'>FAILED!</h2>";
        echo "<p>Error Message: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
});

// TEMP FIX ROUTE
Route::get('/fix-qr', function() {
    $users = \App\Models\User::whereNull('kode_pegawai')->orWhere('kode_pegawai', '')->get();
    $count = 0;
    foreach($users as $user) {
        do {
            $code = 'PEG-' . rand(1000, 9999);
        } while (\App\Models\User::where('kode_pegawai', $code)->exists());
        
        $user->kode_pegawai = $code;
        $user->save();
        $count++;
    }
    return "Berhasil memperbaiki $count pegawai yang tidak punya QR Code. Silakan cek kembali.";
});

require __DIR__.'/auth.php';