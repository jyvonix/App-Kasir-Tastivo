<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama_promo',
        'tipe', // persen, nominal
        'nilai',
        'minimum_belanja',
        'maksimum_potongan',
        'batas_pemakaian', // NEW
        'jumlah_klaim',    // NEW
        'mulai_berlaku',
        'berakhir_pada',
        'khusus_produk_id',
        'is_active',
    ];

    protected $casts = [
        'mulai_berlaku' => 'date',
        'berakhir_pada' => 'date',
        'is_active' => 'boolean',
    ];

    // Relasi ke Produk (jika promo khusus produk tertentu)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'khusus_produk_id');
    }

    /**
     * Cek apakah promo valid hari ini
     */
    public function isValid()
    {
        if (!$this->is_active) return false;

        // Cek Tanggal
        $today = Carbon::today();
        if ($this->mulai_berlaku && $today->lt($this->mulai_berlaku)) return false;
        if ($this->berakhir_pada && $today->gt($this->berakhir_pada)) return false;

        // Cek Kuota (Jika batas_pemakaian tidak null)
        if ($this->batas_pemakaian !== null && $this->jumlah_klaim >= $this->batas_pemakaian) {
            return false;
        }

        return true;
    }
}