<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
            
            /* --- 1. BACKGROUND SYSTEM --- */
            .mesh-background {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
                background-color: #F1F5F9;
                background-image: 
                    radial-gradient(at 0% 0%, hsla(25, 100%, 88%, 1) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(210, 100%, 96%, 1) 0, transparent 50%);
            }

            /* --- 2. GLASS CARD --- */
            .glass-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
            }

            /* --- 3. FLOATING ANIMATION --- */
            @keyframes float {
                0% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
                100% { transform: translateY(0px) rotate(0deg); }
            }
            .floating-icon { animation: float 6s ease-in-out infinite; }
            .floating-icon-delayed { animation: float 8s ease-in-out infinite; animation-delay: 2s; }

            /* --- 4. INPUT STYLING --- */
            .modern-input {
                background: rgba(255, 255, 255, 0.5);
                border: 1px solid rgba(226, 232, 240, 0.8);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .modern-input:focus {
                background: rgba(255, 255, 255, 1);
                border-color: #FF6B35;
                box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
                transform: translateY(-1px);
            }
        </style>
    </head>

    <div class="relative w-full">
        {{-- Background Decoration --}}
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-orange-50 via-white to-orange-50/50 rounded-3xl"></div>
        
        {{-- Decorative Elements --}}
        <div class="fixed top-40 left-[10%] opacity-10 floating-icon pointer-events-none hidden lg:block">
            <svg class="w-24 h-24 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11 9H9V2H7V9H5V2H3V9c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>
        </div>

        <div class="max-w-5xl mx-auto py-6">

            {{-- Breadcrumb & Header --}}
            <div class="mb-10">
                <a href="{{ route('pegawai.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-[#FF6B35] transition-colors mb-4 group">
                    <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Manajemen Pegawai
                </a>
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-gradient-to-br from-[#FF6B35] to-red-600 rounded-3xl shadow-lg shadow-orange-200">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tight">Edit <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF6B35] to-red-500">Pegawai</span></h1>
                            <p class="text-gray-500 font-medium">Memperbarui data profil <span class="text-gray-800 font-bold">{{ $pegawai->nama }}</span></p>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <span class="inline-flex items-center px-6 py-3 rounded-2xl text-sm font-black bg-white/50 border border-white text-orange-600 shadow-sm backdrop-blur-md">
                            ID: #PGW-{{ $pegawai->id }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Error Handling --}}
            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl flex gap-4 animate-shake">
                    <div class="p-2 bg-red-100 rounded-full h-fit"><svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg></div>
                    <div>
                        <h3 class="font-bold text-red-800">Ups! Ada kesalahan pengisian:</h3>
                        <ul class="text-sm text-red-700 mt-1 list-disc list-inside font-medium">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Main Form Card --}}
            <div class="glass-card rounded-[2.5rem] overflow-hidden bg-white/60 backdrop-blur-xl border border-white/50 shadow-xl">
                <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" enctype="multipart/form-data" class="p-6 lg:p-10">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="cropped_image" id="cropped_image">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                        
                        {{-- LEFT COLUMN: PHOTO --}}
                        <div class="lg:col-span-4 space-y-6">
                            <div class="relative group mx-auto w-fit">
                                <div class="w-56 h-56 rounded-[3rem] overflow-hidden ring-4 ring-white shadow-2xl relative bg-white transition-all duration-500 group-hover:shadow-orange-200">
                                    <img id="preview-image" 
                                         src="{{ $pegawai->foto_url }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-end pb-6">
                                        <p class="text-white text-xs font-black uppercase tracking-widest">Ubah Foto</p>
                                    </div>
                                </div>
                                <button type="button" onclick="document.getElementById('foto-input').click()" class="absolute -bottom-4 -right-4 bg-gray-900 text-white p-4 rounded-3xl shadow-xl hover:bg-[#FF6B35] transition-all hover:scale-110 active:scale-95 group/btn">
                                    <svg class="w-6 h-6 transform group-hover/btn:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </button>
                                <input type="file" name="foto" id="foto-input" class="hidden" accept="image/*" onchange="openCropper(event)">
                            </div>
                            <div class="text-center">
                                <h3 class="font-black text-gray-900 text-xl tracking-tight">{{ $pegawai->nama }}</h3>
                                <p class="text-orange-600 text-sm font-black uppercase tracking-widest mt-1">{{ $pegawai->role }}</p>
                            </div>

                            <div class="mt-10 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-[2rem] border border-blue-100/50">
                                <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-4">Informasi Akun</h4>
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-blue-600 shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                        <div><p class="text-[10px] text-gray-400 font-bold uppercase">Terdaftar Sejak</p><p class="text-xs text-gray-800 font-black">{{ $pegawai->created_at->format('d M Y') }}</p></div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-blue-600 shadow-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                        <div><p class="text-[10px] text-gray-400 font-bold uppercase">Update Terakhir</p><p class="text-xs text-gray-800 font-black">{{ $pegawai->updated_at->diffForHumans() }}</p></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN: FIELDS --}}
                        <div class="lg:col-span-8 space-y-10">
                            
                            {{-- Section: Dasar --}}
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="h-8 w-1.5 bg-[#FF6B35] rounded-full"></div>
                                    <h2 class="text-xl font-black text-gray-900">Biodata <span class="text-gray-400 font-medium">Pegawai</span></h2>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Nama Lengkap</label>
                                        <input type="text" name="nama" value="{{ old('nama', $pegawai->nama) }}" required
                                               class="w-full modern-input rounded-2xl py-4 px-6 text-gray-800 font-bold focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Jabatan</label>
                                        <input type="text" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" required
                                               class="w-full modern-input rounded-2xl py-4 px-6 text-gray-800 font-bold focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Nomor HP/WA</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-6 text-gray-400 font-black">+62</span>
                                            <input type="text" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}" required
                                                   class="w-full modern-input rounded-2xl py-4 pl-16 pr-6 text-gray-800 font-bold focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Jadwal Shift</label>
                                        <select name="shift_id" class="w-full modern-input rounded-2xl py-4 px-6 text-gray-800 font-bold focus:outline-none appearance-none cursor-pointer">
                                            <option value="" {{ $pegawai->shift_id == null ? 'selected' : '' }}>Tidak Ada Shift (Office Hours)</option>
                                            @foreach($shifts as $shift)
                                                <option value="{{ $shift->id }}" {{ old('shift_id', $pegawai->shift_id) == $shift->id ? 'selected' : '' }}>
                                                    {{ $shift->nama_shift }} ({{ \Carbon\Carbon::parse($shift->jam_masuk)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->jam_pulang)->format('H:i') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Login --}}
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="h-8 w-1.5 bg-[#FF6B35] rounded-full"></div>
                                    <h2 class="text-xl font-black text-gray-900">Akses <span class="text-gray-400 font-medium">Aplikasi</span></h2>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Username</label>
                                        <input type="text" name="username" value="{{ old('username', $pegawai->username) }}" required
                                               class="w-full modern-input rounded-2xl py-4 px-6 text-gray-800 font-bold focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Level Akses (Role)</label>
                                        <select name="role" required class="w-full modern-input rounded-2xl py-4 px-6 text-gray-800 font-bold focus:outline-none appearance-none cursor-pointer">
                                            <option value="kasir" {{ old('role', $pegawai->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                            <option value="admin" {{ old('role', $pegawai->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                            <option value="owner" {{ old('role', $pegawai->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                                        </select>
                                    </div>

                                    <div class="col-span-2 p-6 bg-orange-50 rounded-2xl border border-orange-100 flex items-start gap-4">
                                        <div class="p-2 bg-orange-100 rounded-lg text-orange-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                        <p class="text-sm text-orange-800 font-bold">Kosongkan password jika tidak ingin mengubahnya.</p>
                                    </div>

                                    <div class="relative">
                                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Password Baru</label>
                                        <input type="password" name="password" id="password" placeholder="••••••••"
                                               class="w-full modern-input rounded-2xl py-4 px-6 text-gray-800 font-bold placeholder-gray-400 focus:outline-none">
                                        <button type="button" onclick="togglePassword('password', 'eye1')" class="absolute top-[46px] right-6 text-gray-400 hover:text-[#FF6B35]">
                                            <svg id="eye1" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <label class="block text-sm font-black text-gray-700 mb-2 ml-1">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirm" placeholder="••••••••"
                                               class="w-full modern-input rounded-2xl py-4 px-6 text-gray-800 font-bold placeholder-gray-400 focus:outline-none">
                                        <button type="button" onclick="togglePassword('password_confirm', 'eye2')" class="absolute top-[46px] right-6 text-gray-400 hover:text-[#FF6B35]">
                                            <svg id="eye2" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-10 flex flex-col-reverse sm:flex-row gap-4">
                                <a href="{{ route('pegawai.index') }}" class="flex-1 text-center px-8 py-5 rounded-3xl text-gray-500 font-black hover:bg-white transition-all uppercase tracking-widest text-xs">Batalkan</a>
                                <button type="submit" class="flex-[2] px-8 py-5 bg-gray-900 text-white rounded-3xl font-black shadow-xl shadow-gray-200 hover:bg-[#FF6B35] hover:shadow-orange-200 hover:-translate-y-1 transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                    Simpan Perubahan
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Cropper Modal --}}
    <div id="cropperModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-2xl font-black text-gray-900">Sesuaikan <span class="text-[#FF6B35]">Foto</span></h3>
                <button onclick="closeCropper()" class="text-gray-400 hover:text-red-500 transition-colors"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"/></svg></button>
            </div>
            <div class="p-8">
                <div class="h-[400px] w-full bg-gray-100 rounded-3xl overflow-hidden">
                    <img id="image-to-crop" class="max-w-full block">
                </div>
            </div>
            <div class="p-8 bg-gray-50 flex gap-4">
                <button onclick="closeCropper()" class="flex-1 px-8 py-4 rounded-2xl font-bold text-gray-500 hover:bg-gray-200 transition-all">Batal</button>
                <button id="cropButton" class="flex-1 px-8 py-4 bg-[#FF6B35] text-white rounded-2xl font-black shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all">Simpan Potongan</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const modal = document.getElementById('cropperModal');
        const image = document.getElementById('image-to-crop');
        const preview = document.getElementById('preview-image');
        const croppedInput = document.getElementById('cropped_image');

        function openCropper(event) {
            const files = event.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    image.src = e.target.result;
                    modal.classList.remove('hidden');
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                        background: false,
                        autoCropArea: 1,
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        }

        function closeCropper() {
            modal.classList.add('hidden');
            if (cropper) cropper.destroy();
            document.getElementById('foto-input').value = '';
        }

        document.getElementById('cropButton').addEventListener('click', () => {
            const canvas = cropper.getCroppedCanvas({ width: 500, height: 500 });
            const base64 = canvas.toDataURL('image/jpeg');
            preview.src = base64;
            croppedInput.value = base64;
            closeCropper();
        });

        function togglePassword(id, iconId) {
            const input = document.getElementById(id);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</x-app-layout>