<x-app-layout>
    <div class="relative min-h-[calc(100vh-100px)] flex items-center justify-center p-4 md:p-8">
        
        <!-- Premium Background Decor (Lava Theme) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden rounded-[3rem]">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500 via-orange-600 to-red-700 opacity-95"></div>
            <div class="absolute top-[-10%] left-[-10%] w-[40rem] h-[40rem] bg-yellow-400 rounded-full blur-[120px] opacity-30 animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[30rem] h-[30rem] bg-red-500 rounded-full blur-[100px] opacity-40 animate-pulse" style="animation-delay: 2s"></div>
            
            <!-- Pattern Overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <!-- MAIN FORM CARD (Glassmorphism) -->
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" 
              class="relative z-10 w-full max-w-5xl bg-white/90 backdrop-blur-2xl rounded-[3rem] shadow-[0_20px_50px_rgba(0,0,0,0.2)] border border-white/40 overflow-hidden animate-fade-in-up">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12">
                
                <!-- LEFT PANEL: Visuals -->
                <div class="lg:col-span-4 bg-gray-50/50 p-8 border-r border-gray-100 flex flex-col justify-between">
                    <div>
                        <a href="{{ route('produk.index') }}" class="inline-flex items-center text-xs font-black text-orange-600 uppercase tracking-widest hover:text-orange-700 transition-colors mb-8">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                            Kembali
                        </a>
                        
                        <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-tight mb-2">Tambah <br><span class="text-orange-600">Produk Baru</span></h2>
                        <p class="text-sm text-gray-500 font-medium">Visualisasikan menu terbaik Anda di sini.</p>
                    </div>

                    <!-- Image Upload Zone -->
                    <div class="mt-10 space-y-6">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest block text-center">Preview Foto</label>
                        <div class="relative aspect-square rounded-[2.5rem] bg-white shadow-inner border-4 border-white overflow-hidden group">
                            <input type="file" name="gambar_file" id="gambar_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(event)">
                            
                            <!-- Placeholder -->
                            <div id="placeholder-upload" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 bg-orange-50/50 group-hover:bg-orange-100/50 transition-colors">
                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-orange-500 mb-3 group-hover:scale-110 transition-transform duration-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <span class="text-xs font-black text-orange-600 uppercase tracking-wider">Upload Foto</span>
                            </div>

                            <!-- Preview Image -->
                            <img id="img-preview" class="absolute inset-0 w-full h-full object-cover hidden scale-100 group-hover:scale-110 transition-transform duration-700">
                        </div>

                        <div class="pt-4">
                            <input type="text" name="gambar_url" class="w-full bg-white border-gray-200 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all placeholder-gray-300 shadow-sm" placeholder="Atau tempel URL gambar...">
                        </div>
                    </div>
                </div>

                <!-- RIGHT PANEL: Details -->
                <div class="lg:col-span-8 p-8 md:p-12 space-y-10">
                    
                    <!-- Form Section 1 -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 bg-orange-500 rounded-full"></div>
                            <h3 class="text-xl font-black text-gray-900 tracking-tight uppercase">Informasi Dasar</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nama Menu</label>
                                <input type="text" name="nama_produk" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all" placeholder="Misal: Burger Lava">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Kategori</label>
                                <select name="kategori_id" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Kategori...</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Form Section 2 -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-8 bg-orange-500 rounded-full"></div>
                            <h3 class="text-xl font-black text-gray-900 tracking-tight uppercase">Harga & Inventaris</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Harga Jual</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                        <span class="text-sm font-black text-orange-600">Rp</span>
                                    </div>
                                    <input type="number" name="harga_jual" required class="w-full bg-gray-50 border-transparent rounded-2xl pl-14 pr-6 py-4 text-xl font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all" placeholder="0">
                                </div>
                            </div>

                            <input type="hidden" name="harga_beli" value="0">

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Stok Awal</label>
                                    <input type="number" name="stok" value="0" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Satuan</label>
                                    <input type="text" name="satuan" value="Pcs" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-8 flex items-center justify-end gap-6">
                        <button type="submit" class="w-full md:w-auto px-12 py-5 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-orange-500/30 hover:shadow-orange-500/50 hover:-translate-y-1 active:scale-95 transition-all duration-300 flex items-center justify-center gap-3 group">
                            <span>Simpan Produk</span>
                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Script Preview Gambar -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('img-preview');
                const placeholder = document.getElementById('placeholder-upload');
                output.src = reader.result;
                output.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>