<x-app-layout>
    <div class="space-y-8 animate-fade-in">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-tight">Manajemen <br><span class="text-orange-600">Kategori Produk</span></h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Atur klasifikasi menu Anda agar lebih terorganisir.</p>
            </div>

            <button onclick="openModal('modal-tambah')" 
                    class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.1em] shadow-xl shadow-orange-500/20 hover:shadow-orange-500/40 hover:-translate-y-1 active:scale-95 transition-all duration-300 group">
                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Kategori</span>
            </button>
        </div>

        <!-- Stats Quick Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Kategori</p>
                    <p class="text-2xl font-black text-gray-900">{{ $kategoris->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Category Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($kategoris as $k)
                <div class="group relative bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-500 hover:-translate-y-2">
                    <!-- Glass Background Pattern -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700 -z-10"></div>
                    
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all duration-500 shadow-sm group-hover:shadow-lg group-hover:shadow-orange-500/30">
                            <span class="text-xl font-black">{{ strtoupper(substr($k->nama_kategori, 0, 1)) }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editKategori({{ $k->id }}, '{{ $k->nama_kategori }}', '{{ $k->deskripsi }}')" class="p-2 text-gray-400 hover:text-orange-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <h4 class="text-xl font-black text-gray-900 mb-2 group-hover:text-orange-600 transition-colors">{{ $k->nama_kategori }}</h4>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6 line-clamp-2">{{ $k->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    
                    <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Produk Terkait</span>
                        <span class="px-3 py-1 bg-gray-50 text-gray-600 rounded-full text-[10px] font-black group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">{{ $k->produk_count }} Items</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mb-6">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2 uppercase tracking-tight">Belum Ada Kategori</h3>
                    <p class="text-gray-500 max-w-xs">Kategori Anda masih kosong. Silakan tambahkan kategori baru untuk mulai mengelompokkan menu.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div id="modal-tambah" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-tambah')"></div>
            
            <div class="relative bg-white rounded-[3rem] w-full max-w-lg p-10 shadow-2xl overflow-hidden animate-zoom-in">
                <!-- Decorative Circle -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-500/10 rounded-full blur-3xl"></div>
                
                <div class="relative">
                    <h3 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight">Kategori <span class="text-orange-600">Baru</span></h3>
                    
                    <form action="{{ route('kategori.store') }}" method="POST" class="space-y-6 text-left">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nama Kategori</label>
                            <input type="text" name="nama_kategori" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all" placeholder="Misal: Minuman Segar">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all" placeholder="Ceritakan sedikit tentang kategori ini..."></textarea>
                        </div>
                        
                        <div class="pt-4 flex gap-4">
                            <button type="button" onclick="closeModal('modal-tambah')" class="flex-1 px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-200 transition-colors">Batal</button>
                            <button type="submit" class="flex-[2] px-8 py-4 bg-orange-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-orange-600/20 hover:bg-orange-700 transition-colors">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="modal-edit" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-edit')"></div>
            
            <div class="relative bg-white rounded-[3rem] w-full max-w-lg p-10 shadow-2xl overflow-hidden animate-zoom-in">
                <div class="relative">
                    <h3 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight">Edit <span class="text-orange-600">Kategori</span></h3>
                    
                    <form id="form-edit" method="POST" class="space-y-6 text-left">
                        @csrf @method('PUT')
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="edit-nama" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" id="edit-deskripsi" rows="3" class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all"></textarea>
                        </div>
                        
                        <div class="pt-4 flex gap-4">
                            <button type="button" onclick="closeModal('modal-edit')" class="flex-1 px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-200 transition-colors">Batal</button>
                            <button type="submit" class="flex-[2] px-8 py-4 bg-orange-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-orange-600/20 hover:bg-orange-700 transition-colors">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function editKategori(id, nama, deskripsi) {
            document.getElementById('form-edit').action = `/kategori/${id}`;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-deskripsi').value = deskripsi;
            openModal('modal-edit');
        }
    </script>

    <style>
        .animate-zoom-in {
            animation: zoomIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</x-app-layout>