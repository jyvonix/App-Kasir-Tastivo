<x-app-layout>
    <div class="space-y-8 animate-fade-in">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-tight">Jadwal <br><span class="text-orange-600">Shift Kerja</span></h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Kelola jam kerja operasional staf Anda.</p>
            </div>

            <button onclick="openModal('modal-tambah')" 
                    class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.1em] shadow-xl shadow-orange-500/20 hover:shadow-orange-500/40 hover:-translate-y-1 active:scale-95 transition-all duration-300 group">
                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Shift</span>
            </button>
        </div>

        <!-- Shift Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($shifts as $s)
                <div class="group relative bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-500 hover:-translate-y-2">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-all duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editShift({{ $s->id }}, '{{ $s->nama_shift }}', '{{ $s->jam_masuk }}', '{{ $s->jam_pulang }}')" class="p-2 text-gray-400 hover:text-orange-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form action="{{ route('shifts.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus shift ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <h4 class="text-xl font-black text-gray-900 mb-4">{{ $s->nama_shift }}</h4>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Jam Masuk</span>
                            <span class="font-black text-gray-900">{{ \Carbon\Carbon::parse($s->jam_masuk)->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Jam Pulang</span>
                            <span class="font-black text-gray-900">{{ \Carbon\Carbon::parse($s->jam_pulang)->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-gray-500 font-bold">Belum ada data shift. Silakan klik /seed-shift di browser.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div id="modal-tambah" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeModal('modal-tambah')"></div>
            <div class="relative bg-white rounded-[3rem] w-full max-w-lg p-10 shadow-2xl animate-zoom-in">
                <h3 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight">Shift <span class="text-orange-600">Baru</span></h3>
                <form action="{{ route('shifts.store') }}" method="POST" class="space-y-6 text-left">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nama Shift</label>
                        <input type="text" name="nama_shift" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all" placeholder="Contoh: Shift Pagi">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Jam Masuk</label>
                            <input type="time" name="jam_masuk" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Jam Pulang</label>
                            <input type="time" name="jam_pulang" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                        </div>
                    </div>
                    <div class="pt-4 flex gap-4">
                        <button type="button" onclick="closeModal('modal-tambah')" class="flex-1 px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest">Batal</button>
                        <button type="submit" class="flex-[2] px-8 py-4 bg-orange-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-orange-600/20">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="modal-edit" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeModal('modal-edit')"></div>
            <div class="relative bg-white rounded-[3rem] w-full max-w-lg p-10 shadow-2xl animate-zoom-in">
                <h3 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight">Edit <span class="text-orange-600">Shift</span></h3>
                <form id="form-edit" method="POST" class="space-y-6 text-left">
                    @csrf @method('PUT')
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nama Shift</label>
                        <input type="text" name="nama_shift" id="edit-nama" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Jam Masuk</label>
                            <input type="time" name="jam_masuk" id="edit-masuk" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Jam Pulang</label>
                            <input type="time" name="jam_pulang" id="edit-pulang" required class="w-full bg-gray-50 border-transparent rounded-2xl px-6 py-4 text-sm font-bold text-gray-800 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all">
                        </div>
                    </div>
                    <div class="pt-4 flex gap-4">
                        <button type="button" onclick="closeModal('modal-edit')" class="flex-1 px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest">Batal</button>
                        <button type="submit" class="flex-[2] px-8 py-4 bg-orange-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-orange-600/20">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

        function editShift(id, nama, masuk, pulang) {
            document.getElementById('form-edit').action = `/shifts/${id}`;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-masuk').value = masuk.substring(0, 5);
            document.getElementById('edit-pulang').value = pulang.substring(0, 5);
            openModal('modal-edit');
        }
    </script>
</x-app-layout>