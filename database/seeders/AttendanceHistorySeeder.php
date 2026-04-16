<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * LOGIKA SUPER PROFESIONAL: Sinkronisasi dengan Waktu Sekarang
     */
    public function run(): void
    {
        // 1. Bersihkan tabel
        DB::table('attendances')->truncate();

        $employees = User::where('role', '!=', 'owner')->whereNotNull('shift_id')->get();
        
        if ($employees->isEmpty()) {
            $this->command->warn('Peringatan: Tidak ada pegawai dengan shift_id.');
            return;
        }

        $now = Carbon::now();
        $startDate = $now->copy()->subYear();
        
        $this->command->info('Menghasilkan riwayat absensi 1 tahun (Sinkron dengan waktu sekarang)...');
        $totalDays = $startDate->diffInDays($now);
        $bar = $this->command->getOutput()->createProgressBar($totalDays);
        $bar->start();

        // 2. Iterasi Hari demi Hari
        for ($date = $startDate->copy(); $date->lte($now); $date->addDay()) {
            
            foreach ($employees as $employee) {
                $shift = $employee->shift;
                
                // Jika hari ini, gunakan logika "Live"
                if ($date->isToday()) {
                    $this->createLiveAttendanceToday($employee, $shift, $now);
                } else {
                    // Logika history acak untuk hari-hari sebelumnya
                    $chance = rand(1, 100);
                    if ($chance <= 90) { // 90% hadir di masa lalu
                        $this->createPastAttendance($employee, $shift, $date);
                    } elseif ($chance <= 97) {
                        $this->createPastIzin($employee, $date);
                    }
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->command->info("\nBerhasil! Data hari ini telah disinkronkan dengan waktu sekarang: " . $now->format('H:i:s'));
    }

    /**
     * Logika untuk Hari Ini (Live Feel)
     */
    private function createLiveAttendanceToday($user, $shift, $now)
    {
        $jamMasukShift = Carbon::parse($now->format('Y-m-d') . ' ' . $shift->jam_masuk);
        $jamPulangShift = Carbon::parse($now->format('Y-m-d') . ' ' . $shift->jam_pulang);

        // Kasus A: Waktu sekarang sudah melewati jam masuk shift
        if ($now->greaterThan($jamMasukShift)) {
            
            // Simulasi dia sudah masuk (misal 5 menit sebelum shift atau pas shift mulai)
            $waktuMasuk = $jamMasukShift->copy()->subMinutes(rand(0, 10));
            
            $status = 'hadir';
            $keterangan = 'Hadir Tepat Waktu (Live)';

            // Jika sekarang sudah lewat jam pulang, isi juga jam pulangnya
            $waktuPulang = null;
            if ($now->greaterThan($jamPulangShift)) {
                $waktuPulang = $jamPulangShift->copy()->addMinutes(rand(5, 20));
            }

            Attendance::create([
                'user_id' => $user->id,
                'shift_id' => $shift->id,
                'tanggal' => $now->format('Y-m-d'),
                'waktu_masuk' => $waktuMasuk->format('H:i:s'),
                'waktu_pulang' => $waktuPulang ? $waktuPulang->format('H:i:s') : null,
                'status' => $status,
                'keterangan' => $keterangan,
                'created_at' => $waktuMasuk,
                'updated_at' => $waktuPulang ?? $now,
            ]);
        }
    }

    /**
     * Logika untuk Hari-hari Kemarin
     */
    private function createPastAttendance($user, $shift, $date)
    {
        $jamMasukShift = Carbon::parse($date->format('Y-m-d') . ' ' . $shift->jam_masuk);
        $jamPulangShift = Carbon::parse($date->format('Y-m-d') . ' ' . $shift->jam_pulang);

        $offset = rand(-15, 45); // Variasi telat atau awal
        $waktuMasuk = $jamMasukShift->copy()->addMinutes($offset);
        
        $status = ($offset > 15) ? 'telat' : 'hadir';
        $keterangan = ($status == 'telat') ? "Terlambat {$offset} menit." : "Tepat waktu.";

        $waktuPulang = $jamPulangShift->copy()->addMinutes(rand(5, 30));

        Attendance::create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'tanggal' => $date->format('Y-m-d'),
            'waktu_masuk' => $waktuMasuk->format('H:i:s'),
            'waktu_pulang' => $waktuPulang->format('H:i:s'),
            'status' => $status,
            'keterangan' => $keterangan,
            'created_at' => $waktuMasuk,
            'updated_at' => $waktuPulang,
        ]);
    }

    private function createPastIzin($user, $date)
    {
        $status = rand(0, 1) ? 'sakit' : 'izin';
        Attendance::create([
            'user_id' => $user->id,
            'shift_id' => $user->shift_id,
            'tanggal' => $date->format('Y-m-d'),
            'status' => $status,
            'keterangan' => "Izin $status di masa lalu.",
            'created_at' => $date->copy()->setTime(7, 0, 0),
        ]);
    }
}