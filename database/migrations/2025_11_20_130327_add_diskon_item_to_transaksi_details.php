<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            // Cek apakah kolom sudah ada, jika belum baru tambahkan
            if (!Schema::hasColumn('transaksi_details', 'diskon_item')) {
                // Tambahkan kolom diskon_item setelah harga_satuan
                $table->decimal('diskon_item', 12, 2)->default(0)->after('harga_satuan');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_details', 'diskon_item')) {
                $table->dropColumn('diskon_item');
            }
        });
    }
};