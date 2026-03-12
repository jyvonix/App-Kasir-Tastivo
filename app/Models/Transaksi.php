<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel (Best Practice)
    protected $table = 'transaksis';

    // 2. Daftar kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'nama_pelanggan', // ✅ Sudah Benar (Kolom Baru)
        'kode_transaksi',
        'total_harga',
        'diskon',
        'pajak',
        'bayar',
        'kembalian',
        'status_pembayaran',
        'metode_pembayaran',
        'snap_token',
        'tanggal',
    ];

    // 3. FITUR TAMBAHAN: Casting
    // Agar angka tersimpan sebagai integer dan tanggal sebagai objek Date
    protected $casts = [
        'tanggal' => 'datetime',      // Biar bisa langsung $trx->tanggal->format('d M Y')
        'total_harga' => 'integer',
        'bayar' => 'integer',
        'kembalian' => 'integer',
    ];

    // 4. Relasi ke User (Kasir) - PERBAIKAN
    public function user()
    {
        // Cukup satu baris ini saja
        return $this->belongsTo(User::class, 'user_id');
    }

    // 5. Relasi ke Detail Transaksi
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id', 'id');
    }
}