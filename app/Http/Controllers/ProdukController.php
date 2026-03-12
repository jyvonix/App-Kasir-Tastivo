<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
 public function index(Request $request)
    {
        // 1. Siapkan Query
        $query = Produk::with('kategori');

        // 2. Logic Search Server-Side
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_produk', 'LIKE', '%' . $keyword . '%')
                  ->orWhereHas('kategori', function($subQ) use ($keyword) {
                      $subQ->where('nama_kategori', 'LIKE', '%' . $keyword . '%');
                  });
            });
        }

        // 3. PENTING: Gunakan paginate(10)
        $produkList = $query->latest()->paginate(10)->withQueryString();

        return view('produk.index', compact('produkList'));
    }


    // ... method create, store, edit, update, destroy TETAP SAMA seperti sebelumnya ...
    // (Tidak perlu diubah, cukup copy paste isi method lainnya dari file lamamu)
    
    public function create()
    {
        $kategoris = Kategori::all();
        return view('produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'harga_beli'  => 'required',
            'harga_jual'  => 'required',
            'stok'        => 'required|integer',
            'satuan'      => 'required|string',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gambar_url'  => 'nullable|url'
        ]);

        $hargaBeli = (int) preg_replace('/[^0-9]/', '', $request->harga_beli);
        $hargaJual = (int) preg_replace('/[^0-9]/', '', $request->harga_jual);

        $data = $request->except(['gambar_file', 'gambar_url', 'harga_beli', 'harga_jual']);
        $data['harga_beli'] = $hargaBeli;
        $data['harga_jual'] = $hargaJual;

        if ($request->hasFile('gambar_file')) {
            $path = $request->file('gambar_file')->store('produk', 'public');
            $data['gambar_file'] = basename($path);
            $data['gambar_url'] = null;
        } elseif ($request->filled('gambar_url')) {
            $data['gambar_url'] = $request->gambar_url;
            $data['gambar_file'] = null;
        }

        Produk::create($data);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'harga_beli'  => 'required',
            'harga_jual'  => 'required',
            'stok'        => 'required|integer',
            'satuan'      => 'required|string',
            'gambar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gambar_url'  => 'nullable|url'
        ]);

        // Clean price input from any non-numeric characters (just in case)
        $hargaBeli = (int) preg_replace('/[^0-9]/', '', $request->harga_beli);
        $hargaJual = (int) preg_replace('/[^0-9]/', '', $request->harga_jual);

        $data = $request->except(['gambar_file', 'gambar_url', 'harga_beli', 'harga_jual']);
        $data['harga_beli'] = $hargaBeli;
        $data['harga_jual'] = $hargaJual;

        if ($request->hasFile('gambar_file')) {
            // Delete old file if exists
            if ($produk->gambar_file && Storage::disk('public')->exists('produk/' . $produk->gambar_file)) {
                Storage::disk('public')->delete('produk/' . $produk->gambar_file);
            }
            $path = $request->file('gambar_file')->store('produk', 'public');
            $data['gambar_file'] = basename($path);
            $data['gambar_url'] = null;
        } elseif ($request->filled('gambar_url')) {
            // If URL is provided, we use URL and clear the local file
            $data['gambar_url'] = $request->gambar_url;
            if ($produk->gambar_file && Storage::disk('public')->exists('produk/' . $produk->gambar_file)) {
                Storage::disk('public')->delete('produk/' . $produk->gambar_file);
            }
            $data['gambar_file'] = null;
        }
        // If both are empty in the request, we don't touch gambar_file and gambar_url 
        // because they are excluded from $data initially.

        $produk->update($data);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->gambar_file && Storage::disk('public')->exists('produk/' . $produk->gambar_file)) {
            Storage::disk('public')->delete('produk/' . $produk->gambar_file);
        }
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}