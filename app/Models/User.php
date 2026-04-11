<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; // Tambahkan SoftDeletes di sini

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',     // Nama Lengkap Pegawai
        'kode_pegawai', // Kode Unik untuk QR
        'username', // Untuk Login
        'email',    // Wajib ada (meski dummy) untuk standar Laravel
        'password', 
        'role',     // admin, kasir, owner
        'jabatan',  
        'no_hp',    
        'status',   
        'foto',     // <--- PERBAIKAN: Wajib ada agar path foto tersimpan!
        'shift_id', // Tambahan untuk Shift
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke Shift
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    /**
     * Relasi ke Transaksi (Untuk cek riwayat penjualan)
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | FITUR PREMIUM: ACCESSOR (Otomatisasi Foto)
    |--------------------------------------------------------------------------
    |
    | Function ini membuat kita bisa memanggil $user->foto_url di Blade.
    | Jika user punya foto, tampilkan fotonya.
    | Jika TIDAK punya foto, otomatis buatkan Avatar Inisial (UI Avatars).
    |
    */
    public function getFotoUrlAttribute()
    {
        // 1. Jika kolom foto ada isinya DAN filenya beneran ada di storage
        if ($this->foto && Storage::disk('public')->exists($this->foto)) {
            return asset('storage/' . $this->foto);
        }

        // 2. Jika tidak ada foto, gunakan layanan UI Avatars (Gratis & Keren)
        // Mengambil 2 huruf pertama dari nama, background random
        $nama = urlencode($this->nama);
        return "https://ui-avatars.com/api/?name={$nama}&background=random&color=fff&bold=true";
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER ROLES (Memudahkan Pengecekan di Blade/Controller)
    |--------------------------------------------------------------------------
    */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKasir()
    {
        return $this->role === 'kasir';
    }
    
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    /**
     * Cek role dinamis
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return strtolower($this->role) === strtolower($role);
    }
}