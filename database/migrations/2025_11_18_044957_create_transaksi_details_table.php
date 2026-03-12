<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            // detail_id INT PRIMARY KEY AUTO_INCREMENT
            $table->integer('detail_id')->autoIncrement();
            
            // transaksi_id INT NOT NULL
            // Asumsi: Tabel induk bernama 'transaksis' dan PK-nya 'id'
            $table->unsignedBigInteger('transaksi_id');
            
            // produk_id INT
            $table->unsignedBigInteger('produk_id')->nullable();
            
            // jumlah INT NOT NULL
            $table->integer('jumlah');
            
            // harga_satuan DECIMAL(12,2)
            $table->decimal('harga_satuan', 12, 2);
            
            // diskon_item DECIMAL(12,2) DEFAULT 0.00
            $table->decimal('diskon_item', 12, 2)->default(0.00);
            
            // subtotal DECIMAL(12,2)
            $table->decimal('subtotal', 12, 2);
            
            // Timestamps (created_at, updated_at) - Optional tapi bagus ada
            $table->timestamps();

            // FOREIGN KEYS
            // Menghubungkan ke tabel transaksis
            $table->foreign('transaksi_id')->references('id')->on('transaksis')->onDelete('cascade');
            
            // Menghubungkan ke tabel produks
            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};