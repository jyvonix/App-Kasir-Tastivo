<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\User;
use App\Models\PengaturanToko;
use App\Models\PengajuanIzin;
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

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        $currentShift = $user->shift_id ? Shift::find($user->shift_id) : null;

        $riwayatIzin = PengajuanIzin::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('attendance.index', compact('attendance', 'currentShift', 'riwayatIzin'));
    }

    public function scan()
    {
        return view('attendance.scan'); 
    }

    public function card($id)
    {
        $user = User::with('shift')->findOrFail($id);
        $user->refresh(); 
        return view('attendance.card', compact('user'));
    }

    public function processScan(Request $request)
    {
        $request->validate(['kode_pegawai' => 'required|exists:users,kode_pegawai']);
        $user = User::where('kode_pegawai', $request->kode_pegawai)->first();
        
        if (!$user->shift_id) {
            return response()->json(['status' => 'error', 'message' => 'Pegawai belum memiliki shift.'], 400);
        }

        $shift = Shift::find($user->shift_id);
        $settings = PengaturanToko::first();
        $today = Carbon::today();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)->whereDate('tanggal', $today)->first();

        if (!$attendance) {
            $jamMasuk = Carbon::parse($today->format('Y-m-d') . ' ' . $shift->jam_masuk);
            $jamMasukAwal = (int) ($settings->jam_masuk_awal ?? 60);
            $batasAwal = $jamMasuk->copy()->subMinutes($jamMasukAwal);
            
            if ($now->lessThan($batasAwal)) {
                return response()->json(['status' => 'error', 'message' => "Belum waktunya absen masuk."], 400);
            }

            $toleransi = (int) ($settings->toleransi_keterlambatan ?? 15);
            $batasTelat = $jamMasuk->copy()->addMinutes($toleransi);
            $status = $now->greaterThan($batasTelat) ? 'telat' : 'hadir';
            $keterangan = ($status == 'telat') ? "Terlambat: " . $now->diff($jamMasuk)->format('%H:%I') : "Tepat Waktu";

            Attendance::create([
                'user_id' => $user->id,
                'shift_id' => $shift->id,
                'tanggal' => $today,
                'waktu_masuk' => $now,
                'status' => $status,
                'keterangan' => $keterangan
            ]);

            return response()->json(['status' => 'success', 'nama' => $user->nama, 'message' => 'Absen Masuk Berhasil!', 'time' => $now->format('H:i')]);
        } else {
            if ($attendance->waktu_pulang) {
                return response()->json(['status' => 'error', 'message' => 'Sudah absen pulang.'], 400);
            }
            $attendance->update(['waktu_pulang' => $now]);
            return response()->json(['status' => 'success', 'nama' => $user->nama, 'message' => 'Absen Pulang Berhasil!', 'time' => $now->format('H:i')]);
        }
    }

    // --- LOGIKA FILTER TERPADU (Digunakan oleh Monitoring & PDF) ---
    private function applyFilters(Request $request)
    {
        $query = Attendance::with(['user', 'shift']);

        // Default Filter: Bulan Ini
        $startDate = $request->filled('start_date') ? $request->start_date : Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : Carbon::now()->endOfMonth()->format('Y-m-d');

        $query->whereBetween('tanggal', [$startDate, $endDate]);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return [$query, $startDate, $endDate];
    }

    public function monitoring(Request $request)
    {
        [$query, $startDate, $endDate] = $this->applyFilters($request);
        
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

    public function exportPdf(Request $request)
    {
        [$query, $startDate, $endDate] = $this->applyFilters($request);
        
        // Gunakan urutan Ascending untuk laporan PDF agar rapi secara kronologis
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

        $filename = 'Laporan_Absensi_' . $startDate . '_sd_' . $endDate . '.pdf';
        return $pdf->download($filename);
    }
}