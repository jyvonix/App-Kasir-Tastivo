<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanIzin;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    // --- 1. PROSES PENGAJUAN (Pegawai) ---
    
    // Simpan Pengajuan ke Database (Status: Pending)
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:sakit,izin',
            'keterangan' => 'required|string|max:255',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        // Cek apakah sudah ada pengajuan hari ini (COMMENTED FOR TESTING)
        // $existing = PengajuanIzin::where('user_id', $user->id)
        //     ->whereDate('tanggal', $today)
        //     ->where('status_approval', '!=', 'ditolak') 
        //     ->first();

        // if ($existing) {
        //     return redirect()->route('attendance.index')->with('error', 'Anda sudah mengajukan izin/sakit hari ini. Mohon tunggu konfirmasi Owner.');
        // }

        // Cek apakah sudah absen masuk
        $absen = Attendance::where('user_id', $user->id)->whereDate('tanggal', $today)->first();
        if ($absen) {
            return redirect()->route('attendance.index')->with('error', 'Anda tercatat sudah hadir hari ini, tidak bisa mengajukan izin.');
        }

        // Upload Foto
        $path = null;
        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('bukti_izin', 'public');
        }

        PengajuanIzin::create([
            'user_id' => $user->id,
            'nama_pegawai' => $request->nama_input ?? $user->name, // SIMPAN NAMA MANUAL
            'tanggal' => $today,
            'jenis' => $request->status,
            'keterangan' => $request->keterangan,
            'bukti_foto' => $path,
            'status_approval' => 'pending',
        ]);

        return redirect()->route('attendance.index')->with('success', 'Pengajuan berhasil dikirim! Menunggu persetujuan Owner.');
    }

    // --- 2. HALAMAN APPROVAL (Owner) ---
    
    public function index()
    {
        // Ambil pengajuan yang masih pending (Termasuk user yang sudah di-soft delete)
        $pengajuan = PengajuanIzin::with(['user' => function($q) {
                $q->withTrashed();
            }])
            ->where('status_approval', 'pending')
            ->latest()
            ->get();
            
        // Riwayat pengajuan (Termasuk user yang sudah di-soft delete)
        $riwayat = PengajuanIzin::with(['user' => function($q) {
                $q->withTrashed();
            }])
            ->where('status_approval', '!=', 'pending')
            ->latest()
            ->take(10)
            ->get();

        return view('owner.approval', compact('pengajuan', 'riwayat'));
    }

    // --- 3. PROSES ACC / TOLAK (Owner) ---
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $pengajuan = PengajuanIzin::findOrFail($id);

        if ($request->action == 'reject') {
            $pengajuan->update(['status_approval' => 'ditolak']);
            return redirect()->back()->with('success', 'Pengajuan telah DITOLAK.');
        }

        // JIKA DI-APPROVE (ACC)
        // 1. Update status pengajuan
        $pengajuan->update(['status_approval' => 'disetujui']);

        // 2. OTOMATIS MASUKKAN KE TABEL ABSENSI (Agar muncul di rekap)
        // Cek dulu biar gak duplikat
        $existingAbsen = Attendance::where('user_id', $pengajuan->user_id)
            ->whereDate('tanggal', $pengajuan->tanggal)
            ->first();
            
        if (!$existingAbsen) {
            Attendance::create([
                'user_id' => $pengajuan->user_id,
                'shift_id' => null, // Tidak ada shift karena izin
                'tanggal' => $pengajuan->tanggal,
                'waktu_masuk' => null, // Kosong karena tidak scan
                'waktu_pulang' => null,
                'status' => $pengajuan->jenis, // 'sakit' atau 'izin'
                'keterangan' => $pengajuan->keterangan . ' (Di-ACC Owner)',
                'bukti_foto' => $pengajuan->bukti_foto,
            ]);
        }

        return redirect()->back()->with('success', 'Pengajuan telah DI-ACC. Data otomatis masuk ke Rekap Absensi.');
    }
}
