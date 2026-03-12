<x-app-layout>
    <div class="p-6 lg:p-12 max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-4xl font-extrabold tracking-tighter text-slate-900">
                    Invoice <span class="text-orange-500 italic">Detail.</span>
                </h2>
                <p class="text-slate-400 font-medium italic mt-1">Rincian item untuk transaksi #{{ $transaksi->id }}</p>
            </div>
            <a href="{{ route('laporan.penjualan') }}" class="bg-white px-6 py-2.5 rounded-xl font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> KEMBALI
            </a>
        </div>

        <div class="bg-white p-10 rounded-[40px] shadow-sm border border-slate-100">
            <h4 class="font-bold text-lg text-slate-800 mb-8 uppercase tracking-widest">Daftar Item Terjual</h4>
            
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-[10px] uppercase font-black border-b border-slate-50">
                        <th class="pb-6 px-2">Nama Produk</th>
                        <th class="pb-6 px-2 text-center">Qty</th>
                        <th class="pb-6 px-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-bold text-slate-700">
                    @foreach($transaksi->details as $item)
                    <tr class="border-b border-slate-50 last:border-none group">
                        <td class="py-5 px-2 uppercase text-slate-800 tracking-tight">
                            {{ $item->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}
                        </td>
                        <td class="py-5 px-2 text-center text-slate-400">
                            {{ $item->jumlah }}
                        </td>
                        <td class="py-5 px-2 text-right text-slate-900 font-black">
                            {{-- LOGIKA PERBAIKAN: Hitung Harga Produk x Jumlah Item --}}
                            @php
                                // Ambil harga asli dari tabel produk, jika tidak ada pakai harga di detail, jika 0 juga tampilkan 0
                                $hargaAsli = $item->produk->harga ?? ($item->harga_satuan ?? 0);
                                $subtotal = $hargaAsli * $item->jumlah;
                            @endphp
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-10 pt-8 border-t border-slate-100 flex flex-col items-end">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Transaksi</p>
                <p class="text-4xl font-black text-slate-900 tracking-tighter">
                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>