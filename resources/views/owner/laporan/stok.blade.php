<x-app-layout>
    <div class="space-y-8">
        
        <!-- HEADER -->
        <div class="flex flex-col lg:flex-row justify-between items-end gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Laporan Stok & Aset</h2>
                <p class="text-gray-500 font-medium">Monitoring inventaris dan valuasi produk.</p>
            </div>
            
            <div class="relative w-full lg:w-80 group">
                <input type="text" id="searchInput" value="{{ request('search') }}" 
                       placeholder="Cari nama produk..." 
                       class="w-full pl-12 pr-12 py-3 bg-white border border-gray-100 rounded-2xl shadow-sm font-bold text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <!-- Search Spinner -->
                <div id="searchSpinner" class="absolute inset-y-0 right-0 pr-4 flex items-center hidden">
                    <svg class="animate-spin h-5 w-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div id="dataWrapper">
            @section('content')
            <div class="space-y-8">
                <!-- STATS CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card 1: Stok Kritis -->
                    <div class="relative overflow-hidden bg-gradient-to-br {{ $itemKritisCount > 0 ? 'from-red-600 to-orange-600' : 'from-gray-800 to-gray-900' }} rounded-3xl p-6 text-white shadow-xl group">
                        <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white opacity-5 blur-2xl group-hover:scale-110 transition-transform"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                @if($itemKritisCount > 0)
                                    <span class="animate-pulse bg-white text-red-600 text-[10px] font-black px-2 py-1 rounded-lg uppercase">Urgent</span>
                                @endif
                            </div>
                            <p class="text-white/70 text-xs font-bold uppercase tracking-widest">Produk Kritis</p>
                            <h3 class="text-3xl font-black mt-1">{{ $itemKritisCount }} <span class="text-sm font-bold opacity-60">Item</span></h3>
                            <p class="text-xs text-white/50 mt-1 font-medium">Butuh restock segera</p>
                        </div>
                    </div>

                    <!-- Card 2: Potensi Omset -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-50 text-green-600 rounded-2xl group-hover:bg-green-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                        </div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Potensi Omset</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">Rp {{ number_format($potensiOmset, 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-400 mt-1 font-medium">Jika stok habis terjual</p>
                    </div>

                    <!-- Card 3: Fisik -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-50 text-orange-600 rounded-2xl group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                        </div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Total Stok Fisik</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($totalStokFisik) }} <span class="text-sm font-bold text-gray-400">Unit</span></h3>
                        <p class="text-xs text-gray-400 mt-1 font-medium">Barang di gudang</p>
                    </div>

                    <!-- Card 4: Varian -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            </div>
                        </div>
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Varian Produk</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($totalProduk) }} <span class="text-sm font-bold text-gray-400">Item</span></h3>
                        <p class="text-xs text-gray-400 mt-1 font-medium">Jenis menu tersedia</p>
                    </div>
                </div>

                <!-- ALERT: LOW STOCK -->
                @if($stokMenipis->count() > 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-2xl shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex gap-4">
                        <div class="p-2 bg-red-100 text-red-600 rounded-xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-800">Perhatian: {{ $stokMenipis->count() }} Produk Menipis!</h3>
                            <p class="text-sm text-red-600 font-medium">Segera lakukan restock untuk menghindari kehilangan potensi penjualan.</p>
                        </div>
                    </div>
                    <div class="flex -space-x-2 overflow-hidden">
                        @foreach($stokMenipis->take(5) as $item)
                            <div class="relative group/tooltip">
                                @if($item->gambar_file)
                                    <img class="inline-block h-10 w-10 rounded-full ring-2 ring-white object-cover" src="{{ asset('storage/produk/' . $item->gambar_file) }}" alt="">
                                @else
                                    <div class="inline-block h-10 w-10 rounded-full ring-2 ring-white bg-red-200 flex items-center justify-center text-xs font-bold text-red-700">
                                        {{ substr($item->nama_produk, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover/tooltip:opacity-100 transition whitespace-nowrap z-10">
                                    {{ $item->nama_produk }} ({{ $item->stok }})
                                </div>
                            </div>
                        @endforeach
                        @if($stokMenipis->count() > 5)
                            <div class="inline-block h-10 w-10 rounded-full ring-2 ring-white bg-white flex items-center justify-center text-xs font-bold text-gray-500 shadow-sm">
                                +{{ $stokMenipis->count() - 5 }}
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- MAIN TABLE -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                                    <th class="px-6 py-4">Produk</th>
                                    <th class="px-6 py-4">Kategori</th>
                                    <th class="px-6 py-4 text-right">Harga Beli (HPP)</th>
                                    <th class="px-6 py-4 text-right">Harga Jual</th>
                                    <th class="px-6 py-4 w-48">Level Stok</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($semuaStok as $produk)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="h-12 w-12 rounded-xl bg-gray-100 shrink-0 overflow-hidden border border-gray-200">
                                                @if($produk->gambar_file)
                                                    <img src="{{ asset('storage/produk/' . $produk->gambar_file) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-sm">{{ $produk->nama_produk }}</h4>
                                                <p class="text-xs text-gray-400 font-mono">SKU: {{ str_pad($produk->id, 4, '0', STR_PAD_LEFT) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold uppercase tracking-wider">
                                            {{ $produk->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono text-sm text-gray-500">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-mono text-sm font-bold text-gray-800">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                                @php
                                                    $percent = min(100, ($produk->stok / 50) * 100); 
                                                    $color = $produk->stok < 5 ? 'bg-red-500' : ($produk->stok < 15 ? 'bg-yellow-500' : 'bg-green-500');
                                                @endphp
                                                <div class="h-full {{ $color }} rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold w-8 text-right">{{ $produk->stok }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($produk->stok <= 0)
                                            <span class="inline-flex px-2 py-1 bg-red-100 text-red-600 text-[10px] font-black uppercase rounded-lg">Habis</span>
                                        @elseif($produk->stok < 5)
                                            <span class="inline-flex px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-black uppercase rounded-lg">Kritis</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 bg-green-100 text-green-600 text-[10px] font-black uppercase rounded-lg">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">Tidak ada data produk ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100">
                        {{ $semuaStok->links() }}
                    </div>
                </div>
            </div>
            @show
        </div>

    </div>

    <!-- AJAX SEARCH ENGINE -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const dataWrapper = document.getElementById('dataWrapper');
            const spinner = document.getElementById('searchSpinner');
            let timeout = null;

            searchInput.addEventListener('input', function() {
                const query = this.value;
                spinner.classList.remove('hidden');
                dataWrapper.classList.add('opacity-50'); 

                clearTimeout(timeout);

                timeout = setTimeout(() => {
                    fetch(`${window.location.pathname}?search=${query}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.text())
                    .then(html => {
                        dataWrapper.innerHTML = html;
                        dataWrapper.classList.remove('opacity-50');
                        spinner.classList.add('hidden');
                    })
                    .catch(err => {
                        console.error(err);
                        dataWrapper.classList.remove('opacity-50');
                        spinner.classList.add('hidden');
                    });
                }, 500);
            });
        });
    </script>

    </div>
</x-app-layout>