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
    // Halaman ini dibuka di PC/Tablet Toko yang memiliki Kamera/Scanner
    public function scan()
    {
        return view('attendance.scan'); 
    }

    // --- CETAK ID CARD PEGAWAI ---
    public function card($id)
    {
        $user = User::with('shift')->findOrFail($id);
        
        // Refresh data untuk memastikan kode_pegawai yang baru digenerate terbaca
        $user->refresh(); 
        
        return view('attendance.card', compact('user'));
    }

    // --- PROSES SCAN QR PEGAWAI (INTI LOGIKA BARU) ---
    public function processScan(Request $request)
    {
        $request->validate([
            'kode_pegawai' => 'required|exists:users,kode_pegawai',
        ]);

        $user = User::where('kode_pegawai', $request->kode_pegawai)->first();
        
        if (!$user->shift_id) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Halo ' . $user->nama . ', Anda belum memiliki jadwal Shift tetap. Hubungi Admin.'
            ], 400);
        }

        $shift = Shift::find($user->shift_id);
        $settings = PengaturanToko::first(); // Ambil setting global
        $today = Carbon::today();
        $now = Carbon::now();

        // Cek apakah sudah absen hari ini
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // --- LOGIKA CHECK-IN ---
        if (!$attendance) {
            // Gabungkan tanggal hari ini dengan jam shift
            $jamMasuk = Carbon::parse($today->format('Y-m-d') . ' ' . $shift->jam_masuk);
            
            // 1. Cek Batas Awal Absen (Jangan terlalu pagi)
            $batasAwal = $jamMasuk->copy()->subMinutes($settings->jam_masuk_awal ?? 60);
            
            if ($now->lessThan($batasAwal)) {
                $selisih = $now->diffInMinutes($batasAwal);
                return response()->json([
                    'status' => 'error',
                    'message' => "Belum waktunya absen! Harap tunggu {$selisih} menit lagi."
                ], 400);
            }

            // 2. Tentukan Status (Tepat Waktu / Telat)
            // Toleransi misal 15 menit
            $batasTelat = $jamMasuk->copy()->addMinutes($settings->toleransi_keterlambatan ?? 15);
            $status = 'hadir';
            $keterangan = 'Tepat Waktu';

            if ($now->greaterThan($batasTelat)) {
                $status = 'telat';
                $diff = $now->diff($jamMasuk);
                $keterangan = "Terlambat: {$diff->h} Jam {$diff->i} Menit";
            } else {
                // Cek jika datang lebih awal (untuk apresiasi visual di frontend)
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
        }

        // --- LOGIKA CHECK-OUT ---
        else {
            // Jika sudah checkout sebelumnya
            if ($attendance->waktu_pulang) {
                 return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah melakukan absen pulang hari ini.'
                ], 400);
            }

            // Gabungkan tanggal hari ini dengan jam pulang
            // Handle shift malam (lintas hari) jika perlu, tapi sementara asumsi shift harian normal
            $jamPulang = Carbon::parse($today->format('Y-m-d') . ' ' . $shift->jam_pulang);

            // 3. Cek Apakah Sudah Waktunya Pulang? (STRICT)
            if ($now->lessThan($jamPulang)) {
                $kurang = $now->diff($jamPulang);
                return response()->json([
                    'status' => 'error',
                    'message' => "Belum waktunya pulang! Kurang {$kurang->h} Jam {$kurang->i} Menit."
                ], 400);
            }

            // Update data pulang
            $attendance->update([
                'waktu_pulang' => $now,
            ]);

            return response()->json([
                'status' => 'success',
                'mode' => 'out',
                'nama' => $user->nama,
                'message' => 'Absen Pulang Berhasil. Hati-hati!',
                'time' => $now->format('H:i')
            ]);
        }
    }

    // --- HALAMAN FORM IZIN/SAKIT ---
    public function createIzin()
    {
        return view('attendance.izin');
    }

    // --- PROSES SIMPAN IZIN/SAKIT ---
    public function storeIzin(Request $request)
    {
        $request->validate([
            'status' => 'required|in:sakit,izin',
            'keterangan' => 'required|string|max:255',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        $existing = Attendance::where('user_id', $user->id)->whereDate('tanggal', $today)->first();
        if ($existing) {
            return redirect()->route('attendance.index')->with('error', 'Anda sudah tercatat absen hari ini.');
        }

        $path = null;
        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('bukti_izin', 'public');
        }

        Attendance::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'bukti_foto' => $path,
            'waktu_masuk' => null, 
            'waktu_pulang' => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengajuan Izin/Sakit berhasil dikirim.');
    }

    // --- MONITORING & HISTORY (UPGRADED FOR OWNER) ---
    public function monitoring(Request $request)
    {
        $query = Attendance::with(['user', 'shift']);

        // --- FILTER LOGIC ---
        // Filter Tanggal (Default: Hari Ini jika tidak ada filter)
        $startDate = $request->get('start_date') ?? Carbon::today()->format('Y-m-d');
        $endDate = $request->get('end_date') ?? Carbon::today()->format('Y-m-d');
        
        $query->whereBetween('tanggal', [$startDate, $endDate]);

        // Filter Pegawai
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil Data Logs
        $logs = $query->latest('tanggal')->latest('waktu_masuk')->get();

        // --- ANALYTICS SUMMARY ---
        $stats = [
            'total' => $logs->count(),
            'hadir' => $logs->where('status', 'hadir')->count(),
            'telat' => $logs->where('status', 'telat')->count(),
            'izin'  => $logs->whereIn('status', ['izin', 'sakit'])->count(),
        ];

        // Ambil Daftar Pegawai untuk Dropdown Filter
        $employees = User::where('role', '!=', 'owner')->orderBy('nama')->get();

        return view('attendance.monitoring', compact('logs', 'stats', 'employees', 'startDate', 'endDate'));
    }
}