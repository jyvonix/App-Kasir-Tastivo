<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('pengaturan_toko', function (Blueprint $table) {
        if (!Schema::hasColumn('pengaturan_toko', 'diskon_persen')) {
            $table->integer('diskon_persen')->default(0)->after('diskon_aktif');
        }
        if (!Schema::hasColumn('pengaturan_toko', 'minimum_transaksi')) {
            $table->decimal('minimum_transaksi', 15, 2)->default(0)->after('diskon_persen');
        }
    });
}

public function down()
{
    Schema::table('pengaturan_toko', function (Blueprint $table) {
        $table->dropColumn(['diskon_persen', 'minimum_transaksi']);
    });
}
};
