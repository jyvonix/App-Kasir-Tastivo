<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel kosong dulu agar tidak duplikat jika di-seed ulang
        // DB::table('shifts')->truncate(); // Hati-hati dengan truncate jika ada foreign key, lebih aman pakai firstOrCreate

        $shifts = [
            [
                'nama_shift' => 'Pagi',
                'jam_masuk' => '07:00:00',
                'jam_pulang' => '15:00:00',
            ],
            [
                'nama_shift' => 'Siang',
                'jam_masuk' => '14:00:00',
                'jam_pulang' => '22:00:00',
            ],
            [
                'nama_shift' => 'Malam', // Opsional, untuk resto 24 jam atau lembur
                'jam_masuk' => '22:00:00',
                'jam_pulang' => '06:00:00',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::firstOrCreate(
                ['nama_shift' => $shift['nama_shift']], // Cek berdasarkan nama
                $shift // Data yang diinsert jika belum ada
            );
        }
    }
}