<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    /**
     * Nama tabel
     */
    protected $table = 'kategori';

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Kategori ini memiliki banyak produk
     */
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}