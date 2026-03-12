<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanToko extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_toko';

    protected $fillable = [
        'nama_toko',
        'alamat_toko',
        'no_telepon',
        'pajak_persen',
        'diskon_aktif',
        'diskon_persen',
        'minimum_transaksi',
        'toleransi_keterlambatan',
        'jam_masuk_awal',
    ];
}
