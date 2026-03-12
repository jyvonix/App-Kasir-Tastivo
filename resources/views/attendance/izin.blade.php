<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Formulir Izin / Sakit') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Info Card (Premium Glassmorphism) -->
            <div class="mb-8 relative overflow-hidden bg-gradient-to-br from-orange-500 to-red-600 rounded-3xl p-8 text-white shadow-xl">
                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white opacity-10 blur-3xl"></div>
                <div class="relative z-10 flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        @if(Auth::user()->foto)
                            <img class="h-20 w-20 rounded-2xl object-cover border-2 border-white/30 shadow-lg" src="{{ asset('storage/' . Auth::user()->foto) }}" alt="">
                        @else
                            <div class="h-20 w-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl font-bold border border-white/30">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-orange-100 text-sm font-medium uppercase tracking-wider">Pengaju Izin</p>
                        <h3 class="text-3xl font-extrabold tracking-tight">{{ Auth::user()->name }}</h3>
                        <p class="text-white/80 text-sm mt-1">Sistem akan mencatat pengajuan ini secara otomatis.</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl shadow-2xl shadow-orange-100 border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('attendance.store_izin') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <!-- Nama Pegawai -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 tracking-wide uppercase">Nama Pegawai</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="nama_input" value="{{ Auth::user()->name }}" class="pl-11 w-full rounded-2xl border-gray-200 bg-white text-gray-800 font-bold focus:ring-orange-500 focus:border-orange-500 transition-all">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 italic">*Anda bisa menyesuaikan nama jika diperlukan.</p>
                        </div>

                        <!-- Status Selection (Sakit/Izin) -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-4 tracking-wide uppercase">Alasan Tidak Masuk</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex flex-col p-4 bg-gray-50 rounded-2xl border-2 border-transparent cursor-pointer hover:bg-orange-50 transition-all group">
                                    <input type="radio" name="status" value="sakit" class="hidden peer" required>
                                    <div class="peer-checked:border-orange-500 peer-checked:bg-orange-50 absolute inset-0 rounded-2xl border-2 transition-all"></div>
                                    <div class="relative z-10 flex items-center space-x-3">
                                        <div class="p-2 bg-red-100 text-red-600 rounded-xl group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                        </div>
                                        <span class="font-bold text-gray-700">Sakit</span>
                                    </div>
                                </label>

                                <label class="relative flex flex-col p-4 bg-gray-50 rounded-2xl border-2 border-transparent cursor-pointer hover:bg-orange-50 transition-all group">
                                    <input type="radio" name="status" value="izin" class="hidden peer">
                                    <div class="peer-checked:border-orange-500 peer-checked:bg-orange-50 absolute inset-0 rounded-2xl border-2 transition-all"></div>
                                    <div class="relative z-10 flex items-center space-x-3">
                                        <div class="p-2 bg-blue-100 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="font-bold text-gray-700">Izin</span>
                                    </div>
                                </label>
                            </div>
                            @error('status') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-2 tracking-wide uppercase">Keterangan / Alasan Lengkap</label>
                            <textarea name="keterangan" id="keterangan" rows="4" class="w-full rounded-2xl border-gray-200 focus:border-orange-500 focus:ring-orange-500 bg-gray-50 transition-all shadow-inner" placeholder="Contoh: Sakit demam dan butuh istirahat..." required></textarea>
                            @error('keterangan') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- Bukti Foto -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 tracking-wide uppercase">Unggah Bukti (Opsional)</label>
                            <div onclick="document.getElementById('bukti_foto').click()" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-3xl hover:border-orange-400 hover:bg-orange-50 transition-all bg-gray-50 group cursor-pointer relative">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-orange-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 00-4 4H12a4 4 0 00-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 005.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <span id="file-name" class="relative font-bold text-orange-600 group-hover:text-orange-500">
                                            Klik untuk Upload File
                                        </span>
                                        <p id="upload-text" class="pl-1 text-gray-400">atau drag & drop</p>
                                        <input id="bukti_foto" name="bukti_foto" type="file" class="sr-only" onchange="updateFileName(this)">
                                    </div>
                                    <p class="text-xs text-gray-400 italic">PNG, JPG, JPEG (Maks. 2MB). Gunakan surat dokter jika sakit.</p>
                                </div>
                            </div>
                            @error('bukti_foto') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        <script>
                            function updateFileName(input) {
                                const fileNameDisplay = document.getElementById('file-name');
                                const uploadText = document.getElementById('upload-text');
                                if (input.files && input.files.length > 0) {
                                    fileNameDisplay.innerText = "File Terpilih: " + input.files[0].name;
                                    fileNameDisplay.classList.remove('text-orange-600');
                                    fileNameDisplay.classList.add('text-green-600');
                                    uploadText.classList.add('hidden');
                                }
                            }
                        </script>

                        <!-- Info Footer -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-2xl">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Status kehadiran Anda hari ini akan diperbarui secara otomatis setelah disetujui oleh Owner.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl text-lg font-bold text-white bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transform hover:-translate-y-1 transition-all duration-200">
                                Kirim Pengajuan
                            </button>
                            <a href="{{ route('attendance.index') }}" class="block text-center mt-4 text-sm font-medium text-gray-400 hover:text-gray-600 transition-colors">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>