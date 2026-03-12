<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Simpan Token Rahasia dari Midtrans
            $table->string('snap_token')->nullable()->after('kembalian');
            // Simpan Status (pending/success/failed)
            $table->string('status_pembayaran')->default('pending')->after('snap_token');
        });
    }
    
    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'status_pembayaran']);
        });
    }
};