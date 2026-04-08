<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_masuk'  => 'required',
            'jam_pulang' => 'required',
        ]);

        Shift::create($request->all());

        return redirect()->back()->with('success', 'Shift berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $request->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_masuk'  => 'required',
            'jam_pulang' => 'required',
        ]);

        $shift->update($request->all());

        return redirect()->back()->with('success', 'Shift berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        
        // Cek jika ada user yang menggunakan shift ini
        if (\App\Models\User::where('shift_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Shift tidak bisa dihapus karena masih digunakan oleh pegawai!');
        }

        $shift->delete();
        return redirect()->back()->with('success', 'Shift berhasil dihapus!');
    }
}