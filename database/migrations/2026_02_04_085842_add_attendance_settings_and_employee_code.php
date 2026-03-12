<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode_pegawai')->nullable()->unique()->after('id');
        });

        Schema::table('pengaturan_toko', function (Blueprint $table) {
            $table->integer('toleransi_keterlambatan')->default(15)->after('diskon_persen'); // Menit
            $table->integer('jam_masuk_awal')->default(60)->after('toleransi_keterlambatan'); // Berapa menit sebelum shift mulai boleh absen?
        });

        // Generate Kode Pegawai untuk user yang sudah ada
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            $code = 'EMP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            DB::table('users')->where('id', $user->id)->update(['kode_pegawai' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kode_pegawai');
        });

        Schema::table('pengaturan_toko', function (Blueprint $table) {
            $table->dropColumn(['toleransi_keterlambatan', 'jam_masuk_awal']);
        });
    }
};