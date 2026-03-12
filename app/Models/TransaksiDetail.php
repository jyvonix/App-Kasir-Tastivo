<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_details';
    protected $primaryKey = 'id';

    // IZINKAN SEMUA KOLOM INI DIISI
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'nama_produk',     // Pastikan ini ada
        'jumlah',
        'harga_satuan',
        'diskon_item',
        'sub_total_produk' // <-- INI KUNCINYA (Jangan sampai salah ketik)
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}