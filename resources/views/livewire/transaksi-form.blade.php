<div>
    {{-- Ini akan mengisi <slot name="header"> di layout Anda --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            TRANSAKSI
        </h2>
    </x-slot>

    {{-- Notifikasi (Penting untuk error stok, dll) --}}
    <div class="max-w-full mx-auto sm:px-6 lg:px-8 pt-6">
        @if (session()->has('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
    </div>
    
    {{-- Kita gunakan max-w-full agar bisa selebar mungkin --}}
    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 grid grid-cols-12 gap-6">

            {{-- ====================================== --}}
            {{-- KOLOM KIRI (Info Order) - 4 Kolom Grid --}}
            {{-- ====================================== --}}
            <div class="col-span-12 lg:col-span-4 bg-white p-6 rounded-lg shadow-sm">
                
                {{-- No. Transaksi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nomor transaksi</label>
                    {{-- 'wire:model' mengikat variabel. 'disabled' agar tidak bisa diubah --}}
                    <input type="text" wire:model="nomor_transaksi" disabled 
                           class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
                
                {{-- Tanggal Transaksi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tanggal transaksi</label>
                    <input type="text" wire:model="tanggal_transaksi" disabled 
                           class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
                
                {{-- Nama Kasir --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama kasir</label>
                    <input type="text" wire:model="nama_kasir" disabled 
                           class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                </div>
                
                {{-- Nama Pelanggan --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama pelanggan</label>
                    {{-- 'wire:model.live' akan update otomatis saat diketik --}}
                    <input type="text" wire:model.live="nama_pelanggan" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                           placeholder="Opsional">
                </div>
            </div>

            {{-- =========================================== --}}
            {{-- KOLOM TENGAH (Katalog Produk) - 5 Kolom Grid --}}
            {{-- =========================================== --}}
            <div class="col-span-12 lg:col-span-4 bg-white p-6 rounded-lg shadow-sm">
                <div class="flex mb-4">
                    {{-- 'wire:model.live.debounce.300ms' = cari setelah 300ms berhenti ngetik --}}
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search Food..." 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-l-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                    <button class="px-4 py-2 bg-orange-500 text-white rounded-r-md">Filter</button>
                </div>
                
                <h3 class="text-lg font-medium text-gray-800 mb-3">Produk</h3>
                
                {{-- Area scroll produk --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 overflow-y-auto" style="height: 60vh;">
                    {{-- Loop semua produk --}}
                    @forelse ($semuaProduk as $produk)
                        {{-- 'wire:click' memanggil fungsi di PHP saat diklik --}}
                        <div wire:click="tambahKeKeranjang({{ $produk->id }})" 
                             class="border p-3 rounded-lg text-center cursor-pointer hover:bg-orange-50 transition duration-150">
                            
                            {{-- Tampilkan gambar --}}
                            <img src="{{ $produk->gambar_file ? asset('storage/produk/'. $produk->gambar_file) : 'https://placehold.co/100x100/FBF6EF/E98000?text=Tastivo' }}" 
                                 alt="{{ $produk->nama_produk }}" 
                                 class="w-full h-24 object-cover rounded-md mb-2">
                            
                            <h5 class="text-sm font-medium text-gray-900 truncate">{{ $produk->nama_produk }}</h5>
                            <p class="text-sm font-bold text-orange-600">Rp. {{ number_format($produk->harga_jual) }}</p>
                        </div>
                    @empty
                        <p class="col-span-3 text-center text-gray-500">Produk tidak ditemukan.</p>
                    @endforelse
                </div>
            </div>

            {{-- ======================================= --}}
            {{-- KOLOM KANAN (Keranjang) - 4 Kolom Grid --}}
            {{-- ======================================= --}}
            <div class="col-span-12 lg:col-span-4 bg-white p-6 rounded-lg shadow-sm flex flex-col">
                
                <h3 class="text-lg font-medium text-gray-800 mb-3">Keranjang</h3>
                
                {{-- Area scroll keranjang --}}
                <div class="flex-grow overflow-y-auto" style="min-height: 25vh; max-height: 35vh;">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-2 text-left">Nama</th>
                                <th class="py-2 px-2 text-center">Jml</th>
                                <th class="py-2 px-2 text-right">Subtotal</th>
                                <th class="py-2 px-1"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($keranjang as $id => $item)
                                <tr>
                                    <td class="py-3 px-2 truncate">{{ $item['nama'] }}</td>
                                    <td class="py-3 px-2 text-center">{{ $item['qty'] }}</td>
                                    <td class="py-3 px-2 text-right">Rp{{ number_format($item['harga'] * $item['qty']) }}</td>
                                    <td class="py-3 px-1 text-center">
                                        {{-- Tombol Hapus Item --}}
                                        <button wire:click="hapusDariKeranjang({{ $id }})" 
                                                class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-gray-500 py-6">Keranjang kosong</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pembayaran (Sesuai Desain Anda) --}}
                <div class="flex justify-around my-4 py-3 border-t border-b">
                    

[Image of DANA logo]

                    
                    
                    
                </div>
                
                {{-- Kalkulasi Total --}}
                <div class="space-y-3">
                    <div class="flex justify-between text-base">
                        <span>Sub Total</span>
                        <span class="font-medium">Rp. {{ number_format($subTotal) }}</span>
                    </div>
                    <div class="flex justify-between text-base">
                        <span>Diskon</span>
                        <span class="font-medium">Rp. 0</span>
                    </div>
                    <div class="flex justify-between text-base">
                        <span>Pajak (10%)</span>
                        <span class="font-medium">Rp. {{ number_format($pajak) }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between font-bold text-xl text-orange-600">
                        <span>Total Bayar</span>
                        <span>Rp. {{ number_format($totalBayar) }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between items-center">
                        <label class="text-base">Jumlah Uang Diterima</label>
                        <input type="number" wire:model.live="uangDiterima" 
                               class="form-input w-1/2 text-right font-bold text-lg border-gray-300 rounded-md focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                               placeholder="0">
                    </div>
                    <div class="flex justify-between text-base">
                        <span>Kembalian</span>
                        <span class="font-medium">Rp. {{ number_format($kembalian) }}</span>
                    </div>
                </div>
                
                {{-- Tombol Aksi --}}
                <div class="mt-6 grid grid-cols-2 gap-4">
                    {{-- Tombol Simpan (wire:click) --}}
                    <button wire:click="simpanTransaksi" 
                            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg shadow-sm transition duration-150">
                        Simpan/Bayar
                    </button>
                    <button class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg shadow-sm transition duration-150">
                        Batal
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>