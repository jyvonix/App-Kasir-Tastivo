<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahkan ini

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_toko', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko')->default('Tastivo Point of Sale');
            $table->string('alamat_toko')->nullable();
            $table->string('no_telepon')->nullable();
            
            // Kolom Pajak & Diskon (Kita gabung disini agar rapi)
            $table->decimal('pajak_persen', 5, 2)->default(0); 
            $table->boolean('diskon_aktif')->default(false);
            $table->integer('diskon_persen')->default(0);      
            $table->decimal('minimum_transaksi', 15, 2)->default(0); 
            
            $table->timestamps();
        });

        // Kita isi data default agar tidak error saat aplikasi pertama dibuka
        DB::table('pengaturan_toko')->insert([
            'nama_toko' => 'Tastivo POS',
            'alamat_toko' => 'Jl. Rasa No. 1',
            'pajak_persen' => 11,
            'diskon_aktif' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_toko');
    }
};