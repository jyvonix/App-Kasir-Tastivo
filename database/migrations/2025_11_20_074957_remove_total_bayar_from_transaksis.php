<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Hapus kolom 'total_bayar' karena ini duplikat dari 'total_harga'
            if (Schema::hasColumn('transaksis', 'total_bayar')) {
                $table->dropColumn('total_bayar');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Kembalikan jika rollback
            if (!Schema::hasColumn('transaksis', 'total_bayar')) {
                $table->decimal('total_bayar', 12, 2)->default(0);
            }
        });
    }
};