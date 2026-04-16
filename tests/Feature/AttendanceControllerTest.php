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
    use RefreshDatabase;

    public function test_owner_can_access_monitoring_page()
    {
        $owner = User::factory()->create([
            'role' => 'owner'
        ]);

        $response = $this->actingAs($owner)->get(route('attendance.monitoring'));

        $response->assertStatus(200);
        $response->assertViewIs('attendance.monitoring');
    }

    public function test_regular_user_cannot_access_monitoring_page()
    {
        $kasir = User::factory()->create([
            'role' => 'kasir'
        ]);

        $response = $this->actingAs($kasir)->get(route('attendance.monitoring'));

        $response->assertStatus(403);
    }

    public function test_monitoring_filter_by_date_range()
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $user = User::factory()->create(['role' => 'kasir']);
        
        Attendance::create([
            'user_id' => $user->id,
            'tanggal' => '2023-01-01',
            'status' => 'hadir',
            'keterangan' => 'DATA_LAMA_ABSENSI'
        ]);

        Attendance::create([
            'user_id' => $user->id,
            'tanggal' => Carbon::today()->format('Y-m-d'),
            'status' => 'hadir',
            'keterangan' => 'DATA_BARU_ABSENSI'
        ]);

        $today = Carbon::today()->format('Y-m-d');
        $response = $this->actingAs($owner)->get(route('attendance.monitoring', [
            'start_date' => $today,
            'end_date' => $today
        ]));

        $response->assertStatus(200);
        $response->assertSee('DATA_BARU_ABSENSI');
        $response->assertDontSee('DATA_LAMA_ABSENSI');
    }

    public function test_export_pdf_returns_successful_download()
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->get(route('attendance.export_pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}