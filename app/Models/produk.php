<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    public $timestamps = true;

    protected $fillable = [
        'nama_produk',
        'kategori_id',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
        'deskripsi',
        'gambar_url',
        'gambar_file',
        'status',
    ];

    public function kategori()
    {
        return $this->belongsTo(\App\Models\Kategori::class, 'kategori_id');
    }
}
