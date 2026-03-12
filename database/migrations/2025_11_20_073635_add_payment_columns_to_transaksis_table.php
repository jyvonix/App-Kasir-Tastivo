<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Kita tambahkan pengecekan (!Schema::hasColumn) agar aman
            
            // 1. Kolom Total Harga
            if (!Schema::hasColumn('transaksis', 'total_harga')) {
                // decimal(12,2) artinya angka desimal dengan 2 angka di belakang koma (standar uang)
                $table->decimal('total_harga', 12, 2)->default(0)->after('kode_transaksi');
            }

            // 2. Kolom Bayar (Uang yang dikasih pembeli)
            if (!Schema::hasColumn('transaksis', 'bayar')) {
                $table->decimal('bayar', 12, 2)->default(0)->after('total_harga');
            }

            // 3. Kolom Kembalian
            if (!Schema::hasColumn('transaksis', 'kembalian')) {
                $table->decimal('kembalian', 12, 2)->default(0)->after('bayar');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['total_harga', 'bayar', 'kembalian']);
        });
    }
};