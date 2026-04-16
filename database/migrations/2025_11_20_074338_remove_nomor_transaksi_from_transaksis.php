<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Fix Profesional: Di SQLite, index UNIK harus dihapus eksplisit sebelum kolomnya
            if (Schema::hasColumn('transaksis', 'nomor_transaksi')) {
                // Gunakan try-catch agar tidak error jika index memang tidak ada
                try {
                    $table->dropUnique('transaksis_nomor_transaksi_unique');
                } catch (\Exception $e) { }
                
                $table->dropColumn('nomor_transaksi');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Kembalikan jika di-rollback (opsional)
            if (!Schema::hasColumn('transaksis', 'nomor_transaksi')) {
                $table->string('nomor_transaksi')->nullable();
            }
        });
    }
};