<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pegawai', // Tambahan
        'tanggal',
        'jenis',
        'keterangan',
        'bukti_foto',
        'status_approval',
    ];

    // Relasi ke User (siapa yang mengajukan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}