<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Shift Kerja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-2xl font-black text-gray-800">Jadwal Shift</h3>
                    <p class="text-sm text-gray-500">Atur jam kerja untuk pegawai (Kasir).</p>
                </div>
                <button onclick="openModal('add')" class="bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold py-3 px-6 rounded-2xl shadow-lg transform transition hover:-translate-y-1">
                    + Tambah Shift
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 text-xs uppercase font-bold tracking-wider border-b border-gray-100">
                                <th class="p-6">Nama Shift</th>
                                <th class="p-6">Jam Masuk</th>
                                <th class="p-6">Jam Pulang</th>
                                <th class="p-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @foreach($shifts as $shift)
                            <tr class="hover:bg-orange-50/50 transition border-b border-gray-50 last:border-none">
                                <td class="p-6 font-bold">{{ $shift->nama_shift }}</td>
                                <td class="p-6">
                                    <span class="bg-blue-100 text-blue-600 py-1 px-3 rounded-full font-bold text-xs">
                                        {{ \Carbon\Carbon::parse($shift->jam_masuk)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="p-6">
                                    <span class="bg-green-100 text-green-600 py-1 px-3 rounded-full font-bold text-xs">
                                        {{ \Carbon\Carbon::parse($shift->jam_pulang)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="p-6 text-center flex justify-center gap-2">
                                    <button onclick="editShift({{ $shift }})" class="text-gray-400 hover:text-orange-500 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    
                                    <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Hapus shift ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($shifts->isEmpty())
                        <div class="p-10 text-center text-gray-400">
                            Belum ada jadwal shift. Silakan tambah baru.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="shiftModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form id="shiftForm" action="{{ route('shifts.store') }}" method="POST" class="p-8">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <h3 class="text-2xl font-black text-gray-900 mb-6" id="modalTitle">Tambah Shift Baru</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Shift</label>
                            <input type="text" name="nama_shift" id="nama_shift" placeholder="Contoh: Pagi, Siang" class="w-full rounded-xl border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 transition" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Jam Masuk</label>
                                <input type="time" name="jam_masuk" id="jam_masuk" class="w-full rounded-xl border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Jam Pulang</label>
                                <input type="time" name="jam_pulang" id="jam_pulang" class="w-full rounded-xl border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 transition" required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 shadow-lg hover:shadow-orange-500/30 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(mode) {
            document.getElementById('shiftModal').classList.remove('hidden');
            const form = document.getElementById('shiftForm');
            const title = document.getElementById('modalTitle');
            const method = document.getElementById('formMethod');

            if (mode === 'add') {
                title.innerText = 'Tambah Shift Baru';
                form.action = "{{ route('shifts.store') }}";
                method.value = 'POST';
                form.reset();
            }
        }

        function editShift(data) {
            document.getElementById('shiftModal').classList.remove('hidden');
            const form = document.getElementById('shiftForm');
            const title = document.getElementById('modalTitle');
            const method = document.getElementById('formMethod');

            title.innerText = 'Edit Shift';
            // Update URL action ke route update (id dinamis)
            form.action = "/shifts/" + data.id; 
            method.value = 'PUT';

            // Isi form dengan data yang ada
            document.getElementById('nama_shift').value = data.nama_shift;
            document.getElementById('jam_masuk').value = data.jam_masuk;
            document.getElementById('jam_pulang').value = data.jam_pulang;
        }

        function closeModal() {
            document.getElementById('shiftModal').classList.add('hidden');
        }
        
        // SweetAlert untuk notifikasi sukses (Opsional jika sudah pakai di layout)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#F97316'
            });
        @endif
    </script>
</x-app-layout>