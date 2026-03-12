<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            // UBAH INI: Gunakan id() agar tipe data BIG INTEGER
            $table->id(); 
            
            // Pastikan kategori_id juga tipe datanya besar (unsignedBigInteger)
            // Jika tabel kategori belum ada, baris ini aman asalkan tidak ada ->constrained()
            $table->unsignedBigInteger('kategori_id'); 

            $table->string('nama_produk');
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->integer('stok');
            $table->string('satuan');
            $table->text('deskripsi')->nullable();
            
            $table->string('gambar_url')->nullable();
            $table->string('gambar_file')->nullable();

            $table->boolean('status')->default(1);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};