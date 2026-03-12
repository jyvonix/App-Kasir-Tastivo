<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id(); // INI OTOMATIS JADI BIG INTEGER (JANGAN UBAH JADI INCREMENTS)
        
        $table->string('nama');
        $table->string('username')->unique();
        $table->string('email')->unique();
        $table->string('password');
        $table->string('role')->default('kasir');
        $table->string('jabatan')->nullable();
        $table->string('no_hp')->nullable();
        $table->boolean('status')->default(1);
        $table->rememberToken();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};