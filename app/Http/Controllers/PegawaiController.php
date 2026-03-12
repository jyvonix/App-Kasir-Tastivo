<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shift; // Tambah Model Shift
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        // Gunakan AJAX Search logic yang sama dengan Produk
        $query = User::with('shift'); // Load relasi shift agar bisa ditampilkan

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('jabatan', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%')
                  ->orWhere('username', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }

        // Urutkan dari yang terbaru
        $pegawaiList = $query->latest()->paginate(5)->withQueryString();

        return view('pegawai.index', compact('pegawaiList'));
    }

    public function create()
    {
        $shifts = Shift::all(); // Ambil semua data shift
        return view('pegawai.create', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'jabatan' => ['required', 'string', 'max:50'],
            'no_hp' => ['required', 'string', 'max:20'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,manager,kasir,owner'], 
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'cropped_image' => ['nullable', 'string'], // Tambahkan ini
            'shift_id' => ['nullable', 'exists:shifts,id'],
        ]);

        $fotoPath = null;
        
        // PRIORITAS: Cek apakah ada foto hasil CROP (Base64)
        if ($request->filled('cropped_image')) {
            $data = $request->cropped_image;
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);
                $data = base64_decode($data);
                $fileName = 'fotos/' . uniqid() . '.' . $type;
                Storage::disk('public')->put($fileName, $data);
                $fotoPath = $fileName;
            }
        } elseif ($request->hasFile('foto')) {
            // Jika tidak dicrop, ambil file asli
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        // GENERATE KODE PEGAWAI UNIK
        // Format: PEG-1001, PEG-4829, dll.
        do {
            $kodePegawai = 'PEG-' . rand(1000, 9999);
        } while (User::where('kode_pegawai', $kodePegawai)->exists());

        User::create([
            'nama' => $request->nama,
            'name' => $request->nama,
            'kode_pegawai' => $kodePegawai, // <-- TAMBAHKAN INI
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'username' => $request->username,
            'email' => $request->username . '@tastivo.local',
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'status' => 1,
            'foto' => $fotoPath,
            'shift_id' => $request->shift_id,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai baru berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $pegawai = User::findOrFail($id);
        $shifts = Shift::all(); // Ambil data shift untuk dropdown
        return view('pegawai.edit', compact('pegawai', 'shifts'));
    }

    public function update(Request $request, string $id)
    {
        $pegawai = User::findOrFail($id);

        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'jabatan' => ['required', 'string', 'max:50'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,'.$pegawai->id],
            'role' => ['required', 'in:admin,manager,kasir,owner'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'cropped_image' => ['nullable', 'string'], // Validasi Base64
            'shift_id' => ['nullable', 'exists:shifts,id'],
        ]);

        // HANDLE FOTO: Prioritas Cropped Image > File Upload Biasa
        if ($request->filled('cropped_image')) {
            // Hapus foto lama
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }

            // Proses Base64
            $data = $request->cropped_image;
            
            // Cek header data URI (e.g., data:image/jpeg;base64,...)
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                
                if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                    throw new \Exception('Tipe file gambar tidak didukung');
                }
                
                $data = base64_decode($data);
                
                if ($data === false) {
                    throw new \Exception('Gagal mendekode gambar');
                }
            } else {
                throw new \Exception('Format data gambar tidak valid');
            }

            // Simpan ke Storage
            $fileName = 'fotos/' . uniqid() . '.' . $type;
            Storage::disk('public')->put($fileName, $data);
            
            $pegawai->foto = $fileName;

        } elseif ($request->hasFile('foto')) {
            // Fallback: Jika upload manual tanpa crop (jaga-jaga)
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $pegawai->foto = $request->file('foto')->store('fotos', 'public');
        }

        $pegawai->nama = $request->nama;
        $pegawai->jabatan = $request->jabatan;
        $pegawai->no_hp = $request->no_hp;
        $pegawai->username = $request->username;
        $pegawai->email = $request->username . '@tastivo.local'; 
        $pegawai->role = $request->role;
        $pegawai->shift_id = $request->shift_id;

        // PROTEKSI: JANGAN SAMPAI KODE PEGAWAI HILANG SAAT UPDATE
        if (!$pegawai->kode_pegawai) {
            do {
                $code = 'PEG-' . rand(1000, 9999);
            } while (User::where('kode_pegawai', $code)->exists());
            $pegawai->kode_pegawai = $code;
        }

        if ($request->filled('password')) {
            $pegawai->password = Hash::make($request->password);
        }

        $pegawai->save();

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pegawai = User::findOrFail($id);
        
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus permanen.');
    }

    public function cetakKartu(string $id)
    {
        $pegawai = User::findOrFail($id);
        return view('pegawai.cetak', compact('pegawai'));
    }
}