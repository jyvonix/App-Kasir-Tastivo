<x-app-layout>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="space-y-8 animate-fade-in">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-tight">Manajemen <br><span class="text-orange-600">Kategori Produk</span></h2>
                <p class="text-sm text-gray-500 font-medium mt-1 uppercase tracking-widest">Atur klasifikasi menu Anda agar lebih terorganisir.</p>
            </div>

            <button onclick="openModal('modal-tambah')" 
                    class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-br from-orange-500 via-orange-600 to-red-600 text-white rounded-[2rem] font-black uppercase tracking-[0.1em] shadow-xl shadow-orange-500/20 hover:shadow-orange-500/40 hover:-translate-y-1 active:scale-95 transition-all duration-300 group">
                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Kategori</span>
            </button>
        </div>

        <!-- Stats Quick Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 backdrop-blur-xl p-6 rounded-[2.5rem] border border-gray-100 shadow-sm flex items-center gap-5 group hover:bg-white hover:shadow-xl transition-all duration-500">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-50 to-orange-100 rounded-[1.5rem] flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] mb-1">Total Kategori</p>
                    <p class="text-3xl font-black text-gray-900 leading-none">{{ $kategoris->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Category Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($kategoris as $k)
                <div class="group relative bg-white rounded-[3rem] p-10 border border-gray-50 shadow-sm hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-700 hover:-translate-y-3 overflow-hidden">
                    
                    <!-- Top Actions -->
                    <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-2 group-hover:translate-y-0">
                        <button onclick="editKategori({{ $k->id }}, '{{ addslashes($k->nama_kategori) }}', '{{ addslashes($k->deskripsi) }}')" 
                                class="w-10 h-10 bg-white shadow-lg rounded-full flex items-center justify-center text-gray-400 hover:text-orange-500 hover:scale-110 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="confirmDelete({{ $k->id }}, '{{ addslashes($k->nama_kategori) }}')" 
                                class="w-10 h-10 bg-white shadow-lg rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:scale-110 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>

                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-[2rem] flex items-center justify-center text-gray-400 group-hover:from-orange-500 group-hover:to-red-600 group-hover:text-white transition-all duration-700 shadow-sm group-hover:shadow-2xl group-hover:shadow-orange-500/40 mb-8 rotate-3 group-hover:rotate-0">
                        <span class="text-3xl font-black italic">{{ strtoupper(substr($k->nama_kategori, 0, 1)) }}</span>
                    </div>

                    <h4 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-orange-600 transition-colors uppercase tracking-tight">{{ $k->nama_kategori }}</h4>
                    <p class="text-sm text-gray-500 leading-relaxed font-medium mb-10 line-clamp-3 h-15">
                        {{ $k->deskripsi ?? 'Belum ada deskripsi untuk kategori ini. Tambahkan detail untuk memudahkan identifikasi.' }}
                    </p>
                    
                    <div class="pt-8 border-t border-gray-50 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] mb-1">Items Terdaftar</span>
                            <span class="text-xl font-black text-gray-800">{{ $k->produk_count }} <span class="text-xs font-bold text-gray-400 italic">Menu</span></span>
                        </div>
                        <div class="px-4 py-2 bg-gray-50 rounded-2xl group-hover:bg-orange-50 transition-colors">
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 flex flex-col items-center justify-center text-center">
                    <div class="w-32 h-32 bg-gray-50 rounded-[3rem] flex items-center justify-center text-gray-200 mb-8 animate-pulse">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 mb-3 uppercase tracking-tighter">Ops! Kosong Melompong</h3>
                    <p class="text-gray-400 font-medium max-w-sm">Mulai petualangan kuliner Anda dengan menambahkan kategori menu pertama hari ini!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div id="modal-tambah" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-6">
            <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-md transition-opacity" onclick="closeModal('modal-tambah')"></div>
            
            <div class="relative bg-white rounded-[4rem] w-full max-w-xl p-12 shadow-2xl overflow-hidden animate-zoom-in">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-orange-500 via-red-600 to-orange-500"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-6 mb-12">
                        <div class="w-16 h-16 bg-orange-100 rounded-[1.5rem] flex items-center justify-center text-orange-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none">Buat <span class="text-orange-600 italic">Kategori</span></h3>
                            <p class="text-xs font-bold text-gray-400 tracking-widest mt-1">LENGKAPI INFORMASI DI BAWAH INI</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('kategori.store') }}" method="POST" class="space-y-8 text-left">
                        @csrf
                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-[0.3em] ml-2">Nama Kategori</label>
                            <input type="text" name="nama_kategori" required 
                                   class="w-full bg-gray-50 border-2 border-transparent rounded-[1.5rem] px-8 py-5 text-sm font-black text-gray-800 focus:bg-white focus:ring-8 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none" 
                                   placeholder="Contoh: PAKET HEMAT" value="{{ old('nama_kategori') }}">
                            @error('nama_kategori') <p class="text-xs font-bold text-red-500 ml-2 italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-[0.3em] ml-2">Deskripsi Kategori</label>
                            <textarea name="deskripsi" rows="4" 
                                      class="w-full bg-gray-50 border-2 border-transparent rounded-[1.5rem] px-8 py-5 text-sm font-black text-gray-800 focus:bg-white focus:ring-8 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none resize-none" 
                                      placeholder="Tuliskan detail kategori menu ini...">{{ old('deskripsi') }}</textarea>
                        </div>
                        
                        <div class="pt-6 flex gap-4">
                            <button type="button" onclick="closeModal('modal-tambah')" 
                                    class="flex-1 px-8 py-5 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                            <button type="submit" 
                                    class="flex-[2] px-8 py-5 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-orange-600/30 hover:shadow-orange-600/50 hover:-translate-y-1 active:scale-95 transition-all">Simpan Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="modal-edit" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-6">
            <div class="fixed inset-0 bg-gray-950/80 backdrop-blur-md transition-opacity" onclick="closeModal('modal-edit')"></div>
            
            <div class="relative bg-white rounded-[4rem] w-full max-w-xl p-12 shadow-2xl overflow-hidden animate-zoom-in">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-orange-500 via-red-600 to-orange-500"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-6 mb-12">
                        <div class="w-16 h-16 bg-orange-100 rounded-[1.5rem] flex items-center justify-center text-orange-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none">Ubah <span class="text-orange-600 italic">Kategori</span></h3>
                            <p class="text-xs font-bold text-gray-400 tracking-widest mt-1">PERBARUI DATA KATEGORI PRODUK</p>
                        </div>
                    </div>
                    
                    <form id="form-edit" method="POST" class="space-y-8 text-left">
                        @csrf @method('PUT')
                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-[0.3em] ml-2">Nama Kategori</label>
                            <input type="text" name="nama_kategori" id="edit-nama" required 
                                   class="w-full bg-gray-50 border-2 border-transparent rounded-[1.5rem] px-8 py-5 text-sm font-black text-gray-800 focus:bg-white focus:ring-8 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-[0.3em] ml-2">Deskripsi Kategori</label>
                            <textarea name="deskripsi" id="edit-deskripsi" rows="4" 
                                      class="w-full bg-gray-50 border-2 border-transparent rounded-[1.5rem] px-8 py-5 text-sm font-black text-gray-800 focus:bg-white focus:ring-8 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none resize-none"></textarea>
                        </div>
                        
                        <div class="pt-6 flex gap-4">
                            <button type="button" onclick="closeModal('modal-edit')" 
                                    class="flex-1 px-8 py-5 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                            <button type="submit" 
                                    class="flex-[2] px-8 py-5 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-orange-600/30 hover:shadow-orange-600/50 hover:-translate-y-1 active:scale-95 transition-all">Perbarui Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for deletion -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Modal functions
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function editKategori(id, nama, deskripsi) {
            const form = document.getElementById('form-edit');
            // Gunakan url() helper agar path-nya tepat (XAMPP/Hosting)
            const baseUrl = "{{ url('kategori') }}";
            form.action = `${baseUrl}/${id}`;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-deskripsi').value = deskripsi === 'null' ? '' : deskripsi;
            openModal('modal-edit');
        }

        // SweetAlert2 Toast Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Delete Confirmation with SweetAlert2
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Kategori?',
                text: `Kategori "${name}" akan dihapus permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'YA, HAPUS!',
                cancelButtonText: 'BATAL',
                border: 'none',
                background: '#fff',
                customClass: {
                    popup: 'rounded-[3rem]',
                    confirmButton: 'rounded-2xl px-8 py-4 font-black uppercase tracking-widest',
                    cancelButton: 'rounded-2xl px-8 py-4 font-black uppercase tracking-widest'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    const baseUrl = "{{ url('kategori') }}";
                    form.action = `${baseUrl}/${id}`;
                    form.submit();
                }
            })
        }

        // Success & Error Notifications
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ea580c',
                customClass: {
                    popup: 'rounded-[3rem]',
                    confirmButton: 'rounded-2xl px-8 py-4 font-black uppercase tracking-widest'
                }
            });
        @endif

        // If there are validation errors, reopen the modal
        @if($errors->any())
            openModal('modal-tambah');
        @endif
    </script>

    <style>
        .animate-zoom-in {
            animation: zoomIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Floating Animation */
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Shadow Pulse Animation */
        .animate-shadow-pulse {
            animation: shadowPulse 3s ease-in-out infinite;
        }
        @keyframes shadowPulse {
            0%, 100% { transform: translateX(-50%) scaleX(1); opacity: 0.1; }
            50% { transform: translateX(-50%) scaleX(0.7); opacity: 0.05; }
        }
    </style>
</x-app-layout>