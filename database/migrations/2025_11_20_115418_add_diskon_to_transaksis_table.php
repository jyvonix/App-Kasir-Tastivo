<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambah kolom diskon setelah total_harga
            if (!Schema::hasColumn('transaksis', 'diskon')) {
                $table->decimal('diskon', 12, 2)->default(0)->after('total_harga');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'diskon')) {
                $table->dropColumn('diskon');
            }
        });
    }
};