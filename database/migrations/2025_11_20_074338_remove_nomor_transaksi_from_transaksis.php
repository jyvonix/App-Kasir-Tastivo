<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Cek apakah kolom pengganggu 'nomor_transaksi' ada? Jika ada, hapus/drop.
            if (Schema::hasColumn('transaksis', 'nomor_transaksi')) {
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