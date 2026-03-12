<x-app-layout>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>

    <div class="space-y-8 animate-fade-in-up">
        
        <!-- HEADER & ACTIONS -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Katalog Produk</h1>
                <p class="text-gray-500 font-medium mt-1">Kelola menu dan ketersediaan stok restoran Anda.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Search Bar -->
                <form action="{{ route('produk.index') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari menu..." 
                           class="pl-11 pr-4 py-3 bg-white border border-gray-100 rounded-2xl w-full md:w-64 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all font-bold text-sm shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-orange-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>

                <a href="{{ route('produk.create') }}" class="px-6 py-3.5 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-orange-500 hover:shadow-xl hover:shadow-orange-500/20 transition-all flex items-center gap-2 group">
                    <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Tambah Produk
                </a>
            </div>
        </div>

        <!-- BENTO STATS -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col justify-between group hover:border-orange-200 transition-colors">
                <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Menu</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-none mt-1">{{ $produkList->total() }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col justify-between group hover:border-red-200 transition-colors">
                <div class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Stok Tipis</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-none mt-1">
                        {{ \App\Models\Produk::where('stok', '<=', 10)->count() }}
                    </h3>
                </div>
            </div>

            <!-- Tambahkan kategori stats jika diperlukan -->
        </div>

        <!-- PRODUCT LIST TABLE -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 text-[10px] font-black uppercase tracking-widest border-b border-gray-50">
                            <th class="py-6 pl-8">Info Produk</th>
                            <th class="py-6">Kategori</th>
                            <th class="py-6">Harga Jual</th>
                            <th class="py-6 text-center">Stok</th>
                            <th class="py-6 pr-8 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($produkList as $item)
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="py-5 pl-8">
                                <div class="flex items-center gap-4">
                                    <div class="relative w-16 h-16 shrink-0 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 shadow-sm group-hover:scale-105 transition-transform duration-500">
                                        @php
                                            $imageUrl = $item->gambar_url ?: ($item->gambar_file ? asset('storage/produk/' . $item->gambar_file) : null);
                                        @endphp
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 tracking-tight">{{ $item->nama_produk }}</p>
                                        <p class="text-xs font-bold text-gray-400">ID: #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5">
                                <span class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-5">
                                <p class="font-black text-orange-600">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</p>
                                <p class="text-[10px] font-bold text-gray-400 line-through">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-5">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-black {{ $item->stok <= 10 ? 'text-red-500' : 'text-gray-900' }}">
                                        {{ $item->stok }}
                                    </span>
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $item->satuan }}</span>
                                </div>
                            </td>
                            <td class="py-5 pr-8">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('produk.edit', $item->id) }}" class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center hover:bg-orange-50 hover:text-orange-600 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('produk.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" onclick="confirmDelete({{ $item->id }}, '{{ $item->nama_produk }}')" class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center text-gray-400 font-bold">
                                Belum ada produk yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MODERN PAGINATION -->
            @if($produkList->hasPages())
                <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50">
                    {{ $produkList->links('vendor.pagination.tailwind-custom') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Custom Pagination Styles (Inlined for safety) -->
    <style>
        .pagination-container nav {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        /* Style override for standard laravel pagination */
        nav[role="navigation"] {
            display: flex;
            justify-content: center;
            padding: 10px;
        }
        nav[role="navigation"] .relative.z-0 {
            display: inline-flex;
            gap: 4px;
            border-radius: 1rem;
            background: white;
            padding: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>

    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Menu '" + name + "' akan dihapus permanen dari katalog.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF6B35',
                cancelButtonColor: '#1F2937',
                confirmButtonText: 'Ya, Hapus Sekarang!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] p-10',
                    title: 'text-2xl font-black text-gray-900',
                    htmlContainer: 'text-gray-500 font-medium',
                    confirmButton: 'rounded-2xl px-8 py-4 font-black uppercase tracking-widest text-xs',
                    cancelButton: 'rounded-2xl px-8 py-4 font-black uppercase tracking-widest text-xs'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                background: '#fff',
                iconColor: '#FF6B35',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-gray-100'
                }
            });
        @endif
    </script>
</x-app-layout>