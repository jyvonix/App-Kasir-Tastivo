<x-app-layout>
    <div class="relative overflow-hidden -m-6 lg:-m-8 p-6 lg:p-8">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-orange-200 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-red-200 rounded-full blur-3xl opacity-20"></div>

        <div class="max-w-6xl mx-auto relative">
            <!-- Breadcrumb & Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <nav class="flex text-sm text-gray-500 mb-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li><a href="{{ route('dashboard') }}" class="hover:text-tastivo-600 transition-colors">Dashboard</a></li>
                            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                            <li><a href="{{ route('produk.index') }}" class="hover:text-tastivo-600 transition-colors">Produk</a></li>
                            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                            <li class="text-tastivo-600 font-medium">Edit Produk</li>
                        </ol>
                    </nav>
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                        Edit <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-600">Produk</span>
                    </h1>
                    <p class="text-gray-500 mt-2 font-medium">Kustomisasi detail menu andalan Anda di sini.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('produk.index') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-2xl text-gray-700 hover:bg-gray-50 hover:border-tastivo-300 transition-all duration-300 shadow-sm">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-tastivo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="font-bold">Kembali</span>
                    </a>
                </div>
            </div>

            <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Sidebar: Image Preview & Status -->
                    <div class="lg:col-span-4 space-y-6">
                        <!-- Image Card -->
                        <div class="bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-xl shadow-orange-100/50 border border-white relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-3">
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                                </span>
                            </div>
                            
                            <label class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Foto Utama</label>
                            
                            @php
                                $imgSrc = null;
                                if (!empty($produk->gambar_url)) {
                                    $imgSrc = $produk->gambar_url;
                                } elseif (!empty($produk->gambar_file)) {
                                    $imgSrc = asset('storage/produk/' . basename($produk->gambar_file));
                                }
                            @endphp

                            <div class="relative">
                                <div class="aspect-square w-full rounded-2xl bg-gradient-to-br from-orange-50 to-red-50 overflow-hidden border-2 border-dashed border-orange-200 flex items-center justify-center relative transition-all group-hover:border-orange-400 group-hover:shadow-inner">
                                    @if($imgSrc)
                                        <img src="{{ $imgSrc }}" 
                                             alt="Preview" 
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                             id="img-preview"
                                             onerror="this.onerror=null; this.src='https://via.placeholder.com/400x400?text=Produk+Tastivo';">
                                    @else
                                        <div id="placeholder-icon" class="text-center p-6">
                                            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <span class="text-sm font-semibold text-orange-400 uppercase tracking-widest">No Image</span>
                                        </div>
                                        <img id="img-preview" class="w-full h-full object-cover hidden">
                                    @endif

                                    <!-- Overlay Gradient -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6">
                                        <p class="text-white text-xs font-bold uppercase tracking-widest">Ganti Gambar</p>
                                    </div>
                                </div>

                                <!-- Floating Upload Button -->
                                <label for="gambar_file" class="absolute -bottom-4 -right-4 w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl shadow-lg shadow-orange-500/30 flex items-center justify-center cursor-pointer hover:scale-110 active:scale-95 transition-all border-4 border-white">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </label>
                                <input type="file" name="gambar_file" id="gambar_file" class="hidden" onchange="previewImage(event)" accept="image/*">
                            </div>
                            
                            <div class="mt-8 grid grid-cols-2 gap-3">
                                <div class="bg-orange-50 p-3 rounded-2xl border border-orange-100 text-center">
                                    <p class="text-[10px] text-orange-400 font-bold uppercase tracking-wider">Format</p>
                                    <p class="text-sm font-extrabold text-orange-700">PNG/JPG</p>
                                </div>
                                <div class="bg-red-50 p-3 rounded-2xl border border-red-100 text-center">
                                    <p class="text-[10px] text-red-400 font-bold uppercase tracking-wider">Maks</p>
                                    <p class="text-sm font-extrabold text-red-700">2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Quick Info -->
                        <div class="bg-gradient-to-br from-gray-900 to-gray-800 p-6 rounded-3xl shadow-xl text-white">
                            <h4 class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-4">Ringkasan</h4>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">ID Produk</span>
                                    <span class="font-mono text-orange-400">#{{ str_pad($produk->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Dibuat</span>
                                    <span class="text-sm">{{ $produk->created_at ? $produk->created_at->format('d M Y') : 'N/A' }}</span>
                                </div>
                                <div class="pt-4 border-t border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase font-bold tracking-tighter">Margin Profit</p>
                                            <p class="font-extrabold text-lg">
                                                @php
                                                    $profit = $produk->harga_jual - $produk->harga_beli;
                                                    $margin = $produk->harga_beli > 0 ? ($profit / $produk->harga_beli) * 100 : 0;
                                                @endphp
                                                {{ number_format($margin, 1) }}%
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Form: Product Details -->
                    <div class="lg:col-span-8">
                        <div class="bg-white/80 backdrop-blur-xl p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-white">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-200">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-extrabold text-gray-900">Informasi Dasar</h3>
                                    <p class="text-sm text-gray-500 font-medium">Lengkapi detail produk dengan akurat.</p>
                                </div>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Nama & Kategori -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label class="block text-sm font-bold text-gray-700 mb-2 transition-colors group-focus-within:text-tastivo-600">Nama Produk</label>
                                        <div class="relative">
                                            <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" 
                                                   class="w-full bg-gray-50 border-gray-200 rounded-2xl focus:ring-4 focus:ring-tastivo-500/10 focus:border-tastivo-500 focus:bg-white transition-all duration-300 py-3.5 px-4 font-semibold text-gray-700" 
                                                   placeholder="Contoh: Burger Lava Super" required>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none opacity-0 group-focus-within:opacity-100 transition-opacity">
                                                <svg class="w-5 h-5 text-tastivo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group">
                                        <label class="block text-sm font-bold text-gray-700 mb-2 transition-colors group-focus-within:text-tastivo-600">Kategori</label>
                                        <select name="kategori_id" class="w-full bg-gray-50 border-gray-200 rounded-2xl focus:ring-4 focus:ring-tastivo-500/10 focus:border-tastivo-500 focus:bg-white transition-all duration-300 py-3.5 px-4 font-semibold text-gray-700">
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}" {{ old('kategori_id', $produk->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Harga & Stok Section -->
                                <div class="pt-4">
                                    <h4 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <span class="w-8 h-[2px] bg-gray-200"></span>
                                        Finansial & Inventori
                                        <span class="flex-grow h-[2px] bg-gray-200"></span>
                                    </h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="group">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Harga Beli</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <span class="text-gray-400 font-bold">Rp</span>
                                                </div>
                                                <input type="number" name="harga_beli" value="{{ old('harga_beli', $produk->harga_beli) }}" 
                                                       class="w-full bg-gray-50 border-gray-200 rounded-2xl pl-12 focus:ring-4 focus:ring-tastivo-500/10 focus:border-tastivo-500 focus:bg-white transition-all duration-300 py-3.5 font-bold text-gray-700" 
                                                       placeholder="0" required>
                                            </div>
                                        </div>
                                        <div class="group">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Harga Jual</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <span class="text-tastivo-500 font-bold">Rp</span>
                                                </div>
                                                <input type="number" name="harga_jual" value="{{ old('harga_jual', $produk->harga_jual) }}" 
                                                       class="w-full bg-orange-50/50 border-orange-200 rounded-2xl pl-12 focus:ring-4 focus:ring-tastivo-500/10 focus:border-tastivo-500 focus:bg-white transition-all duration-300 py-3.5 font-bold text-tastivo-700" 
                                                       placeholder="0" required>
                                            </div>
                                        </div>
                                        <div class="group">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Stok & Satuan</label>
                                            <div class="flex gap-2">
                                                <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" 
                                                       class="w-1/2 bg-gray-50 border-gray-200 rounded-2xl focus:ring-4 focus:ring-tastivo-500/10 focus:border-tastivo-500 focus:bg-white transition-all duration-300 py-3.5 font-bold text-gray-700 text-center" 
                                                       placeholder="0" required>
                                                <input type="text" name="satuan" value="{{ old('satuan', $produk->satuan) }}" 
                                                       class="w-1/2 bg-gray-50 border-gray-200 rounded-2xl focus:ring-4 focus:ring-tastivo-500/10 focus:border-tastivo-500 focus:bg-white transition-all duration-300 py-3.5 font-bold text-gray-700 text-center" 
                                                       placeholder="Pcs">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="group">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 transition-colors group-focus-within:text-tastivo-600">Deskripsi Menu</label>
                                    <textarea name="deskripsi" rows="4" 
                                              class="w-full bg-gray-50 border-gray-200 rounded-3xl focus:ring-4 focus:ring-tastivo-500/10 focus:border-tastivo-500 focus:bg-white transition-all duration-300 p-4 font-medium text-gray-600 leading-relaxed" 
                                              placeholder="Tuliskan keunggulan atau komposisi produk ini...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                </div>

                                <!-- URL Gambar (Advanced) -->
                                <div class="pt-4">
                                    <div x-data="{ open: false }">
                                        <button type="button" @click="open = !open" class="flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-tastivo-500 transition-colors uppercase tracking-widest">
                                            <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-90': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            Opsi Lanjutan: Gunakan URL
                                        </button>
                                        <div x-show="open" x-transition class="mt-4 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">External Image URL</label>
                                            <input type="text" name="gambar_url" value="{{ old('gambar_url', $produk->gambar_url) }}" 
                                                   class="w-full bg-white border-gray-200 rounded-xl py-2 px-3 text-sm focus:border-tastivo-500 transition-all" 
                                                   placeholder="https://example.com/food-image.jpg">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-4">
                                <button type="reset" class="px-8 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 hover:text-gray-700 transition-all active:scale-95">
                                    Reset Form
                                </button>
                                <button type="submit" class="group relative px-10 py-4 bg-gradient-to-r from-orange-500 to-red-600 text-white font-extrabold rounded-2xl shadow-xl shadow-orange-500/25 hover:shadow-orange-500/40 hover:-translate-y-1 active:scale-95 transition-all overflow-hidden">
                                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                    <div class="relative flex items-center justify-center gap-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                        Update Produk
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Preview Image -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('img-preview');
                const placeholder = document.getElementById('placeholder-icon');
                
                output.src = reader.result;
                output.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>