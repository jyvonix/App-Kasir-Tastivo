<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (siapa yang absen)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke shift (dia absen di shift apa hari itu)
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('set null');

            $table->date('tanggal'); // Tanggal absen (2024-03-20)
            
            $table->time('waktu_masuk')->nullable();  // Jam check-in
            $table->time('waktu_pulang')->nullable(); // Jam check-out
            
            // Status Absen: 'hadir', 'telat', 'izin', 'sakit', 'alpha'
            $table->string('status')->default('alpha'); 
            
            $table->text('keterangan')->nullable(); // Jika izin/sakit atau catatan telat
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};