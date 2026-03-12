<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            // Nullable = Unlimited. Jika diisi angka, berarti ada batasnya.
            $table->integer('batas_pemakaian')->nullable()->after('maksimum_potongan');
            
            // Menghitung berapa kali voucher ini sudah dipakai
            $table->integer('jumlah_klaim')->default(0)->after('batas_pemakaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn(['batas_pemakaian', 'jumlah_klaim']);
        });
    }
};