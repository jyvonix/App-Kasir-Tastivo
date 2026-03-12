<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            
            // Kode Voucher (Contoh: HEMAT100)
            $table->string('kode')->unique();
            $table->string('nama_promo')->nullable(); // Penjelasan singkat
            
            // Jenis: 'persen' atau 'nominal'
            $table->enum('tipe', ['persen', 'nominal']);
            
            // Nilai diskon (Misal: 10 untuk 10%, atau 5000 untuk Rp 5000)
            $table->decimal('nilai', 15, 2);
            
            // Syarat & Ketentuan
            $table->decimal('minimum_belanja', 15, 2)->default(0);
            $table->decimal('maksimum_potongan', 15, 2)->nullable(); // Max potongan untuk persen (misal Max 20rb)
            
            // Masa Berlaku
            $table->date('mulai_berlaku')->nullable();
            $table->date('berakhir_pada')->nullable();
            
            // Filter Khusus (Opsional)
            // Jika diisi ID Produk, maka diskon hanya berlaku jika beli produk ini
            $table->foreignId('khusus_produk_id')->nullable()->constrained('produks')->onDelete('set null');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};