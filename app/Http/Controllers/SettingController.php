<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengaturanToko;
use App\Models\Shift;

class SettingController extends Controller
{
    public function index()
    {
        $setting = PengaturanToko::first();
        if (!$setting) {
            $setting = PengaturanToko::create([
                'nama_toko' => 'Tastivo POS',
            ]);
        }
        $shifts = Shift::all();
        return view('pengaturan.index', compact('setting', 'shifts'));
    }

    public function updateShifts(Request $request) {
        $data = $request->validate([
            'shifts.*.jam_masuk' => 'required',
            'shifts.*.jam_pulang' => 'required',
        ]);

        foreach ($request->shifts as $id => $shiftData) {
            Shift::where('id', $id)->update([
                'jam_masuk' => $shiftData['jam_masuk'],
                'jam_pulang' => $shiftData['jam_pulang'],
            ]);
        }

        return redirect()->back()->with('success', 'Jam kerja shift berhasil diperbarui.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat_toko' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'pajak_persen' => 'required|numeric|min:0|max:100',
        ]);

        $setting = PengaturanToko::first();
        if (!$setting) {
            $setting = new PengaturanToko();
        }

        $setting->nama_toko = $request->nama_toko;
        $setting->alamat_toko = $request->alamat_toko;
        $setting->no_telepon = $request->no_telepon;
        $setting->pajak_persen = $request->pajak_persen;
        
        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan toko berhasil diperbarui.');
    }
}
