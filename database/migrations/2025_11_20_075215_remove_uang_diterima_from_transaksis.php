<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Hapus kolom 'uang_diterima' karena kita sudah pakai 'bayar'
            if (Schema::hasColumn('transaksis', 'uang_diterima')) {
                $table->dropColumn('uang_diterima');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Kembalikan jika rollback
            if (!Schema::hasColumn('transaksis', 'uang_diterima')) {
                $table->decimal('uang_diterima', 12, 2)->default(0);
            }
        });
    }
};