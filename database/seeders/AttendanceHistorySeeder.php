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
     * LOGIKA PROFESIONAL: Simulasi 1 Tahun Absensi
     */
    public function run(): void
    {
        // 1. Bersihkan tabel absensi lama agar tidak duplikat
        DB::table('attendances')->truncate();

        // 2. Ambil semua pegawai yang punya shift (kecuali owner)
        $employees = User::where('role', '!=', 'owner')->whereNotNull('shift_id')->get();
        
        if ($employees->isEmpty()) {
            $this->command->warn('Peringatan: Tidak ada pegawai dengan shift_id. Silakan jalankan UserSeeder & ShiftSeeder dulu.');
            return;
        }

        $this->command->info('Memulai simulasi absensi 1 tahun untuk ' . $employees->count() . ' pegawai...');

        // 3. Konfigurasi Waktu (1 Tahun ke belakang)
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        
        // Kita gunakan Progress Bar agar lebih profesional di CLI
        $totalDays = $startDate->diffInDays($endDate);
        $bar = $this->command->getOutput()->createProgressBar($totalDays);
        $bar->start();

        // 4. Iterasi Hari demi Hari
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            
            // Opsional: Misal hari Minggu libur (tidak ada absensi)
            // if ($date->isSunday()) { $bar->advance(); continue; }

            foreach ($employees as $employee) {
                $shift = $employee->shift;
                
                // --- LOGIKA VARIASI KEHADIRAN (PROFESIONAL) ---
                $chance = rand(1, 100);

                // 85% HADIR / TELAT
                if ($chance <= 85) {
                    $this->createHadirAtauTelat($employee, $shift, $date);
                } 
                // 10% IZIN / SAKIT
                elseif ($chance <= 95) {
                    $this->createIzinAtauSakit($employee, $date);
                }
                // 5% ALPHA (Tidak ada record absensi hari itu)
            }
            $bar->advance();
        }

        $bar->finish();
        $this->command->info("\nSimulasi selesai! Sejarah absensi 1 tahun berhasil dibuat.");
    }

    /**
     * Logika Simulasi Kehadiran Normal/Telat
     */
    private function createHadirAtauTelat($user, $shift, $date)
    {
        $jamMasukShift = Carbon::parse($date->format('Y-m-d') . ' ' . $shift->jam_masuk);
        $jamPulangShift = Carbon::parse($date->format('Y-m-d') . ' ' . $shift->jam_pulang);

        // Simulasi datang antara 30 menit sebelum s/d 45 menit sesudah shift mulai
        // (Random agar ada yang telat dan ada yang rajin)
        $offsetMasuk = rand(-30, 45);
        $waktuMasukActual = $jamMasukShift->copy()->addMinutes($offsetMasuk);

        // Tentukan Status (Toleransi 15 Menit)
        $status = 'hadir';
        $keterangan = 'Hadir Tepat Waktu';

        if ($offsetMasuk > 15) {
            $status = 'telat';
            $keterangan = "Terlambat {$offsetMasuk} menit karena alasan lalu lintas/pribadi.";
        } elseif ($offsetMasuk < 0) {
            $keterangan = "Hadir lebih awal " . abs($offsetMasuk) . " menit.";
        }

        // Simulasi Pulang (Selalu setelah jam pulang shift, antara 5 - 60 menit lembur tipis-tipis)
        $offsetPulang = rand(5, 60);
        $waktuPulangActual = $jamPulangShift->copy()->addMinutes($offsetPulang);

        Attendance::create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'tanggal' => $date->format('Y-m-d'),
            'waktu_masuk' => $waktuMasukActual->format('H:i:s'),
            'waktu_pulang' => $waktuPulangActual->format('H:i:s'),
            'status' => $status,
            'keterangan' => $keterangan,
            'created_at' => $date->copy()->setTime(8, 0), // Dibuat pagi hari simulasi
            'updated_at' => $date->copy()->setTime(17, 0), // Diupdate sore hari simulasi
        ]);
    }

    /**
     * Logika Simulasi Izin / Sakit
     */
    private function createIzinAtauSakit($user, $date)
    {
        $isSakit = rand(0, 1);
        $status = $isSakit ? 'sakit' : 'izin';
        
        $keteranganSakit = ['Demam tinggi', 'Flu berat', 'Sakit kepala/Migrain', 'Izin ke Dokter'];
        $keteranganIzin  = ['Urusan keluarga mendadak', 'Ada keperluan di bank', 'Acara keluarga', 'Perbaikan kendaraan'];

        Attendance::create([
            'user_id' => $user->id,
            'shift_id' => $user->shift_id,
            'tanggal' => $date->format('Y-m-d'),
            'waktu_masuk' => null,
            'waktu_pulang' => null,
            'status' => $status,
            'keterangan' => $isSakit ? $keteranganSakit[array_rand($keteranganSakit)] : $keteranganIzin[array_rand($keteranganIzin)],
            'bukti_foto' => 'bukti_izin/dummy_proof.jpg', // Simulasi path foto
            'created_at' => $date->copy()->setTime(7, 30),
        ]);
    }
}