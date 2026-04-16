<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\User;
use App\Models\PengaturanToko;
use App\Models\PengajuanIzin; // Import
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    // --- HALAMAN UTAMA RIWAYAT ABSENSI PEGAWAI ---
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Cari absen hari ini
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Cari Shift User
        $currentShift = null;
        if ($user->shift_id) {
            $currentShift = Shift::find($user->shift_id);
        }

        // Ambil riwayat pengajuan izin milik user ini
        $riwayatIzin = PengajuanIzin::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('attendance.index', compact('attendance', 'currentShift', 'riwayatIzin'));
    }

    // --- HALAMAN SCANNER (KIOSK MODE) ---
    public function scan()
    {
        return view('attendance.scan'); 
    }

    // --- CETAK ID CARD PEGAWAI ---
    public function card($id)
    {
        $user = User::with('shift')->findOrFail($id);
        $user->refresh(); 
        return view('attendance.card', compact('user'));
    }

    // --- PROSES SCAN QR PEGAWAI ---
    public function processScan(Request $request)
    {
        $request->validate([
            'kode_pegawai' => 'required|exists:users,kode_pegawai',
        ]);

        $user = User::where('kode_pegawai', $request->kode_pegawai)->first();
        
        if (!$user->shift_id) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Halo ' . $user->nama . ', Anda belum memiliki jadwal Shift tetap.'
            ], 400);
        }

        $shift = Shift::find($user->shift_id);
        $settings = PengaturanToko::first();
        $today = Carbon::today();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$attendance) {
            $jamMasuk = Carbon::parse($today->format('Y-m-d') . ' ' . $shift->jam_masuk);
            
            $jamMasukAwal = (int) ($settings->jam_masuk_awal ?? 60);
            $batasAwal = $jamMasuk->copy()->subMinutes($jamMasukAwal);
            
            if ($now->lessThan($batasAwal)) {
                $selisih = $now->diffInMinutes($batasAwal);
                return response()->json([
                    'status' => 'error',
                    'message' => "Belum waktunya absen! Tunggu {$selisih} menit lagi."
                ], 400);
            }

            $toleransi = (int) ($settings->toleransi_keterlambatan ?? 15);
            $batasTelat = $jamMasuk->copy()->addMinutes($toleransi);
            $status = 'hadir';
            $keterangan = 'Tepat Waktu';

            if ($now->greaterThan($batasTelat)) {
                $status = 'telat';
                $diff = $now->diff($jamMasuk);
                $keterangan = "Terlambat: {$diff->h} Jam {$diff->i} Menit";
            } else {
                if ($now->lessThan($jamMasuk)) {
                     $diff = $jamMasuk->diff($now);
                     $keterangan = "Hadir lebih awal {$diff->i} Menit";
                }
            }

            Attendance::create([
                'user_id' => $user->id,
                'shift_id' => $shift->id,
                'tanggal' => $today,
                'waktu_masuk' => $now,
                'status' => $status,
                'keterangan' => $keterangan
            ]);

            return response()->json([
                'status' => 'success',
                'mode' => 'in',
                'nama' => $user->nama,
                'message' => 'Absen Masuk Berhasil!',
                'time' => $now->format('H:i'),
                'detail' => $keterangan
            ]);
        } else {
            if ($attendance->waktu_pulang) {
                 return response()->json(['status' => 'error', 'message' => 'Sudah absen pulang.'], 400);
            }

            $jamPulang = Carbon::parse($today->format('Y-m-d') . ' ' . $shift->jam_pulang);
            if ($now->lessThan($jamPulang)) {
                $kurang = $now->diff($jamPulang);
                return response()->json([
                    'status' => 'error',
                    'message' => "Belum waktunya pulang! Kurang {$kurang->h} Jam {$kurang->i} Menit."
                ], 400);
            }

            $attendance->update(['waktu_pulang' => $now]);
            return response()->json(['status' => 'success', 'mode' => 'out', 'nama' => $user->nama, 'message' => 'Absen Pulang Berhasil!', 'time' => $now->format('H:i')]);
        }
    }

    public function createIzin()
    {
        return view('attendance.izin');
    }

    // --- MONITORING & HISTORY (UPGRADED) ---
    public function monitoring(Request $request)
    {
        $query = Attendance::with(['user', 'shift']);

        $startDate = $request->get('start_date') ?? Carbon::today()->format('Y-m-d');
        $endDate = $request->get('end_date') ?? Carbon::today()->format('Y-m-d');
        
        $query->whereBetween('tanggal', [$startDate, $endDate]);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logs = $query->latest('tanggal')->latest('waktu_masuk')->get();

        $stats = [
            'total' => $logs->count(),
            'hadir' => $logs->where('status', 'hadir')->count(),
            'telat' => $logs->where('status', 'telat')->count(),
            'izin'  => $logs->whereIn('status', ['izin', 'sakit'])->count(),
        ];

        $employees = User::where('role', '!=', 'owner')->orderBy('nama')->get();

        return view('attendance.monitoring', compact('logs', 'stats', 'employees', 'startDate', 'endDate'));
    }

    // --- EXPORT PDF (SUPER PROFESSIONAL) ---
    public function exportPdf(Request $request)
    {
        $query = Attendance::with(['user', 'shift']);

        $startDate = $request->get('start_date') ?? Carbon::today()->format('Y-m-d');
        $endDate = $request->get('end_date') ?? Carbon::today()->format('Y-m-d');
        $query->whereBetween('tanggal', [$startDate, $endDate]);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $logs = $query->orderBy('tanggal', 'asc')->orderBy('waktu_masuk', 'asc')->get();
        $settings = PengaturanToko::first();

        $stats = [
            'total' => $logs->count(),
            'hadir' => $logs->where('status', 'hadir')->count(),
            'telat' => $logs->where('status', 'telat')->count(),
            'izin'  => $logs->whereIn('status', ['izin', 'sakit'])->count(),
        ];

        $pdf = Pdf::loadView('attendance.pdf', compact('logs', 'stats', 'startDate', 'endDate', 'settings'))
            ->setPaper('a4', 'portrait');

        $filename = 'Rekap_Absensi_' . $startDate . '_to_' . $endDate . '.pdf';
        return $pdf->download($filename);
    }
}