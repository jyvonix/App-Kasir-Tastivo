<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Shift;
use Carbon\Carbon;

class AttendanceControllerTest extends TestCase
{
    // Trait ini akan mereset database setiap kali test dijalankan
    // agar data test tidak mengotori database asli Anda.
    use RefreshDatabase;

    /**
     * TEST 1: Memastikan Owner bisa mengakses halaman monitoring.
     * Profesional Tip: Nama fungsi harus sangat deskriptif.
     */
    public function test_owner_can_access_monitoring_page()
    {
        // 1. Arrange (Siapkan Owner)
        $owner = User::factory()->create([
            'role' => 'owner'
        ]);

        // 2. Act (Owner mencoba buka halaman)
        $response = $this->actingAs($owner)->get(route('attendance.monitoring'));

        // 3. Assert (Pastikan berhasil)
        $response->assertStatus(200);
        $response->assertViewIs('attendance.monitoring');
    }

    /**
     * TEST 2: Memastikan user selain Owner/Admin TIDAK BISA mengakses monitoring.
     */
    public function test_regular_user_cannot_access_monitoring_page()
    {
        // 1. Arrange (Siapkan Kasir)
        $kasir = User::factory()->create([
            'role' => 'kasir'
        ]);

        // 2. Act
        $response = $this->actingAs($kasir)->get(route('attendance.monitoring'));

        // 3. Assert (Harusnya terlarang / 403 atau redirect tergantung middleware)
        // Karena di routes kita batasi role:admin,owner maka kasir akan kena 403
        $response->assertStatus(403);
    }

    /**
     * TEST 3: Memastikan filter tanggal di Monitoring bekerja.
     */
    public function test_monitoring_filter_by_date_range()
    {
        // 1. Arrange
        $owner = User::factory()->create(['role' => 'owner']);
        $user = User::factory()->create(['role' => 'kasir']);
        
        // Buat data absensi lama (Bulan lalu)
        Attendance::create([
            'user_id' => $user->id,
            'tanggal' => '2023-01-01',
            'status' => 'hadir',
            'keterangan' => 'Data Lama'
        ]);

        // Buat data absensi baru (Hari ini)
        Attendance::create([
            'user_id' => $user->id,
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'status' => 'hadir',
            'keterangan' => 'Data Baru'
        ]);

        // 2. Act (Filter hanya hari ini)
        $today = Carbon::today()->format('Y-m-d');
        $response = $this->actingAs($owner)->get(route('attendance.monitoring', [
            'start_date' => $today,
            'end_date' => $today
        ]));

        // 3. Assert
        $response->assertStatus(200);
        $response->assertSee('Data Baru'); // Harus terlihat
        $response->assertDontSee('Data Lama'); // Tidak boleh terlihat karena difilter
    }

    /**
     * TEST 4: Memastikan fitur Export PDF berfungsi.
     */
    public function test_export_pdf_returns_successful_download()
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->get(route('attendance.export_pdf'));

        // Pastikan response adalah file PDF
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}