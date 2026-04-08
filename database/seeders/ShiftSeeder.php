<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            ['nama_shift' => 'Pagi', 'jam_masuk' => '07:00', 'jam_pulang' => '15:00'],
            ['nama_shift' => 'Siang', 'jam_masuk' => '11:00', 'jam_pulang' => '19:00'],
            ['nama_shift' => 'Sore', 'jam_masuk' => '15:00', 'jam_pulang' => '23:00'],
            ['nama_shift' => 'Malam', 'jam_masuk' => '23:00', 'jam_pulang' => '07:00'],
        ];

        foreach ($shifts as $shift) {
            Shift::updateOrCreate(
                ['nama_shift' => $shift['nama_shift']],
                $shift
            );
        }
    }
}