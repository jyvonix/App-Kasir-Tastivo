<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Hapus kolom sub_total jika ada, karena menghalangi proses simpan
            if (Schema::hasColumn('transaksis', 'sub_total')) {
                $table->dropColumn('sub_total');
            }
        });
    }

    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Kembalikan jika rollback
            if (!Schema::hasColumn('transaksis', 'sub_total')) {
                $table->decimal('sub_total', 12, 2)->default(0);
            }
        });
    }
};