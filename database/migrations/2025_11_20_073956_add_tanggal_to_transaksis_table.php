<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada, jika belum baru tambahkan
            if (!Schema::hasColumn('transaksis', 'tanggal')) {
                // Menambahkan kolom tanggal (dateTime) setelah kolom kembalian
                // Kita buat nullable() jaga-jaga agar tidak error data lama
                $table->dateTime('tanggal')->nullable()->after('kembalian');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
        });
    }
};