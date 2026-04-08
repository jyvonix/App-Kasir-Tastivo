<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PegawaiController; 
use App\Http\Controllers\LaporanController; 
use App\Http\Controllers\TransaksiController; 
use App\Http\Controllers\SettingController; 
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\PromoController; 
use Illuminate\Support\Facades\Artisan;

// ====================================================
// RUTE DARURAT (AKSES TANPA LOGIN UNTUK PERBAIKAN)
// ====================================================
Route::get('/run-migrate', function() {
    Artisan::call('migrate', ['--force' => true]);
    return "Struktur database berhasil diperbarui!";
});

Route::get('/seed-kategori', function() {
    Artisan::call('db:seed', ['--class' => 'KategoriSeeder']);
    return "Kategori berhasil ditambahkan!";
});

Route::get('/seed-users', function() {
    Artisan::call('db:seed', ['--class' => 'UserSeeder']);
    return "Akun Admin, Kasir, dan Owner berhasil disinkronkan!";
});

Route::get('/seed-shift', function() {
    Artisan::call('db:seed', ['--class' => 'ShiftSeeder']);
    return "Jadwal Shift Pagi, Siang, Sore, dan Malam berhasil ditambahkan!";
});

Route::get('/clear-all', function() {
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "Semua cache berhasil dihapus!";
});

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

    // Absensi
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/scan', [App\Http\Controllers\AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('/attendance/scan', [App\Http\Controllers\AttendanceController::class, 'processScan'])->name('attendance.process_scan');
    Route::get('/attendance/izin', [App\Http\Controllers\AttendanceController::class, 'createIzin'])->name('attendance.izin');
    Route::post('/attendance/izin', [App\Http\Controllers\ApprovalController::class, 'store'])->name('attendance.store_izin');
});

// ====================================================
// GROUP 2: KHUSUS ADMIN
// ====================================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class);
    Route::resource('shifts', \App\Http\Controllers\ShiftController::class);
    Route::get('/pegawai/card/{id}', [App\Http\Controllers\AttendanceController::class, 'card'])->name('pegawai.card');
    
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [SettingController::class, 'update'])->name('pengaturan.update');
    Route::put('/pengaturan/shifts', [SettingController::class, 'updateShifts'])->name('pengaturan.update_shifts');
});

// ====================================================
// GROUP 3: KHUSUS KASIR
// ====================================================
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/tambah', [TransaksiController::class, 'tambahItem'])->name('transaksi.tambah');
    Route::get('/transaksi/hapus/{id}', [TransaksiController::class, 'hapusItem'])->name('transaksi.hapus');
    Route::post('/transaksi/update-qty', [TransaksiController::class, 'updateQty'])->name('transaksi.update_qty');
    Route::get('/transaksi/reset', [TransaksiController::class, 'resetKeranjang'])->name('transaksi.reset');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/cek-status/{id}', [TransaksiController::class, 'cekStatus'])->name('transaksi.cek_status');
    Route::get('/transaksi/sukses/{id}', [TransaksiController::class, 'pembayaranSukses'])->name('transaksi.pembayaran_sukses');
    Route::get('/menu', [ProdukController::class, 'menuDisplay'])->name('menu.index');
    Route::post('/promo/check', [PromoController::class, 'checkPromo'])->name('promo.check');
});

// ====================================================
// GROUP 4: KHUSUS OWNER
// ====================================================
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/approval', [App\Http\Controllers\ApprovalController::class, 'index'])->name('owner.approval');
    Route::post('/approval/{id}', [App\Http\Controllers\ApprovalController::class, 'updateStatus'])->name('owner.approval.update');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export_excel');
        Route::get('/export/pdf', [LaporanController::class, 'exportPdf'])->name('export_pdf');
    });
});

// ====================================================
// GROUP 6: PROMO
// ====================================================
Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
        Route::get('/promo/{promo}', [PromoController::class, 'show'])->name('promo.show');
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/promo/create', [PromoController::class, 'create'])->name('promo.create');
        Route::post('/promo', [PromoController::class, 'store'])->name('promo.store');
        Route::get('/promo/{promo}/edit', [PromoController::class, 'edit'])->name('promo.edit');
        Route::put('/promo/{promo}', [PromoController::class, 'update'])->name('promo.update');
        Route::delete('/promo/{promo}', [PromoController::class, 'destroy'])->name('promo.destroy');
    });
});

// ====================================================
// GROUP 5: MONITORING
// ====================================================
Route::middleware(['auth', 'role:admin,owner,kasir'])->group(function () {
    Route::get('/attendance/monitoring', [App\Http\Controllers\AttendanceController::class, 'monitoring'])->name('attendance.monitoring');
    Route::get('/transaksi/riwayat', [TransaksiController::class, 'riwayat'])->name('transaksi.riwayat');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
});

require __DIR__.'/auth.php';