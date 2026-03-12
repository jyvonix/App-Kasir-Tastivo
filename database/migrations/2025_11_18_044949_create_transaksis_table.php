<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('transaksis', function (Blueprint $table) {
        $table->id(); 

        $table->string('nomor_transaksi')->unique();
        
        // =========================================================
        // CARA PALING AMAN (MANUAL):
        // Kita definisikan tipe datanya secara eksplisit
        // =========================================================
        $table->unsignedBigInteger('user_id'); 
        $table->foreign('user_id')->references('id')->on('users');
        // =========================================================
        
        $table->string('nama_pelanggan')->nullable();
        $table->decimal('sub_total', 15, 2);
        $table->decimal('pajak', 15, 2)->default(0);
        $table->decimal('diskon', 15, 2)->default(0);
        $table->decimal('total_bayar', 15, 2);
        $table->decimal('uang_diterima', 15, 2);
        $table->decimal('kembalian', 15, 2);
        $table->string('metode_pembayaran')->nullable();
        $table->string('status')->default('selesai');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};