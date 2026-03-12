<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            // 1. Tambahkan kolom 'nama_produk' jika belum ada
            if (!Schema::hasColumn('transaksi_details', 'nama_produk')) {
                $table->string('nama_produk')->after('produk_id')->nullable();
            }

            // 2. Perbaiki kolom 'subtotal' menjadi 'sub_total_produk' (sesuai Controller baru)
            if (Schema::hasColumn('transaksi_details', 'subtotal')) {
                // Jika ada kolom 'subtotal', ganti namanya jadi 'sub_total_produk'
                $table->renameColumn('subtotal', 'sub_total_produk');
            } 
            elseif (!Schema::hasColumn('transaksi_details', 'sub_total_produk')) {
                // Jika belum ada sama sekali, buat baru
                $table->decimal('sub_total_produk', 15, 2)->default(0)->after('diskon_item');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksi_details', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_details', 'nama_produk')) {
                $table->dropColumn('nama_produk');
            }
            if (Schema::hasColumn('transaksi_details', 'sub_total_produk')) {
                $table->renameColumn('sub_total_produk', 'subtotal');
            }
        });
    }
};