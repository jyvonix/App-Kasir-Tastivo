<x-app-layout>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; overflow-x: hidden; }
            
            /* --- 1. BACKGROUND SYSTEM --- */
            .mesh-background {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
                background-color: #F1F5F9;
                background-image: 
                    radial-gradient(at 0% 0%, hsla(25, 100%, 88%, 1) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(210, 100%, 96%, 1) 0, transparent 50%);
            }

            /* --- 2. GLASS CARD ROW (PEGAWAI STYLE) --- */
            .pegawai-card {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.6);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
                transition: all 0.3s ease;
            }
            .pegawai-card:hover {
                transform: translateY(-2px);
                background: rgba(255, 255, 255, 1);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
                border-color: rgba(249, 115, 22, 0.3);
            }

            /* --- 3. SKELETON LOADER --- */
            .skeleton {
                background: #e2e8f0;
                background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
                background-size: 200% 100%;
                animation: 1.5s shine linear infinite;
                border-radius: 8px;
            }
            @keyframes shine { to { background-position-x: -200%; } }

            /* --- 4. CUSTOM PAGINATION (ORANGE) --- */
            nav[role="navigation"] div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:first-child { display: none; }
            nav[role="navigation"] div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between { justify-content: center; }
            nav[role="navigation"] span[aria-current="page"] > span {
                background-color: #FF6B35 !important; border-color: #FF6B35 !important; color: white !important; font-weight: 800; border-radius: 8px;
            }
            nav[role="navigation"] a { color: #64748b; font-weight: 600; border-radius: 8px; margin: 0 2px; border: 1px solid transparent; }
            nav[role="navigation"] a:hover { background-color: #fff7ed !important; color: #FF6B35 !important; }

            /* --- 5. FLOATING ANIMATION --- */
            @keyframes float {
                0% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
                100% { transform: translateY(0px) rotate(0deg); }
            }
            .floating-icon { animation: float 6s ease-in-out infinite; }
            .floating-icon-delayed { animation: float 8s ease-in-out infinite; animation-delay: 2s; }
        </style>
    </head>

    <div class="relative w-full">
        {{-- Background Decoration for this page specifically --}}
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-orange-50 via-white to-orange-50/50 rounded-3xl"></div>
        
        {{-- Decorative Elements --}}
        <div class="absolute top-0 right-0 -mr-10 -mt-10 opacity-20 pointer-events-none hidden lg:block">
            <svg class="w-64 h-64 text-orange-200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,79.6,-46.9C87.4,-34.7,90.1,-20.4,85.8,-8.3C81.5,3.8,70.2,13.7,60.8,22.2C51.4,30.7,43.9,37.8,35.5,43.8C27.1,49.8,17.8,54.7,7.7,56.8C-2.4,58.9,-13.3,58.2,-23.1,54.7C-32.9,51.2,-41.6,44.9,-51.6,37.1C-61.6,29.3,-72.9,20,-76.3,8.4C-79.7,-3.2,-75.2,-17.1,-67.6,-29.4C-60,-41.7,-49.3,-52.4,-37.2,-60.8C-25.1,-69.2,-11.6,-75.3,1.5,-77.9C14.6,-80.5,29.2,-79.6,44.7,-76.4Z" transform="translate(100 100)" />
            </svg>
        </div>

        <div class="w-full">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-10 relative z-10">
                <div class="space-y-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-[2px] bg-[#FF6B35] rounded-full"></span>
                        <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.3em]">Human Resource Management</span>
                    </div>
                    <h1 class="text-4xl lg:text-5xl text-gray-900 tracking-tight leading-tight">
                        <span class="font-medium">Manajemen</span>
                        <span class="font-light text-gray-400">Pegawai</span>
                    </h1>
                    <p class="text-gray-400 font-medium tracking-wide pt-2">Kelola akses dan data staf TASTIVO.</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <div class="relative group w-full lg:w-[320px]">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#FF6B35] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" id="searchInput" 
                            class="block w-full pl-12 pr-12 py-3 bg-white border border-gray-100 rounded-xl text-gray-800 shadow-sm focus:ring-2 focus:ring-[#FF6B35] focus:border-[#FF6B35] focus:outline-none transition-all font-semibold placeholder-gray-400" 
                            placeholder="Cari nama, role..." autocomplete="off" value="{{ request('search') }}">
                        
                        <div id="searchSpinner" class="absolute inset-y-0 right-0 pr-4 flex items-center hidden">
                            <svg class="animate-spin h-5 w-5 text-[#FF6B35]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <a href="{{ route('pegawai.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 hover:bg-[#FF6B35] text-white font-bold rounded-xl shadow-lg hover:shadow-orange-200 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah
                    </a>
                </div>
            </div>

            <div class="relative z-10">
                {{-- Table Header --}}
                <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-3 bg-white/50 backdrop-blur-sm rounded-t-2xl border-b border-gray-100 text-xs font-extrabold text-gray-400 uppercase tracking-widest">
                    <div class="col-span-1 text-center">#</div>
                    <div class="col-span-4">Profil Pegawai</div>
                    <div class="col-span-2">Role & Jabatan</div>
                    <div class="col-span-2">Kontak</div>
                    <div class="col-span-2 text-center">Status</div>
                    <div class="col-span-1 text-right">Aksi</div>
                </div>

                {{-- Skeleton --}}
                <div id="skeletonWrapper" class="space-y-3 hidden mt-4">
                    @for ($i = 0; $i < 5; $i++)
                    <div class="bg-white rounded-2xl p-4 grid grid-cols-1 lg:grid-cols-12 gap-4 items-center border border-gray-100 animate-pulse">
                        <div class="hidden lg:block col-span-1"><div class="h-4 w-4 bg-gray-200 rounded mx-auto"></div></div>
                        <div class="col-span-12 lg:col-span-4 flex items-center gap-4">
                            <div class="h-12 w-12 bg-gray-200 rounded-full"></div> 
                            <div class="space-y-2 w-full">
                                <div class="h-4 w-1/2 bg-gray-200 rounded"></div>
                                <div class="h-3 w-1/3 bg-gray-200 rounded"></div>
                            </div>
                        </div>
                        <div class="col-span-2 hidden lg:block"><div class="h-6 w-24 bg-gray-200 rounded-lg"></div></div>
                        <div class="col-span-2 hidden lg:block"><div class="h-5 w-32 bg-gray-200 rounded"></div></div>
                        <div class="col-span-2 hidden lg:block"><div class="h-6 w-16 bg-gray-200 rounded-full mx-auto"></div></div>
                        <div class="col-span-1 hidden lg:block"><div class="h-8 w-8 bg-gray-200 rounded-lg ml-auto"></div></div>
                    </div>
                    @endfor
                </div>

                {{-- Data Wrapper --}}
                <div id="dataWrapper">
                    <div id="contentWrapper" class="space-y-3 mt-2">
                        @forelse ($pegawaiList as $index => $user)
                            <div class="pegawai-card bg-white hover:bg-orange-50/30 rounded-2xl p-4 lg:py-4 lg:px-6 grid grid-cols-1 lg:grid-cols-12 gap-4 items-center group relative overflow-hidden border border-gray-100 transition-all hover:border-orange-200 hover:shadow-md">
                                
                                <div class="hidden lg:block col-span-1 text-center text-gray-400 font-bold">
                                    {{ $loop->iteration + ($pegawaiList->currentPage() - 1) * $pegawaiList->perPage() }}
                                </div>

                                <div class="col-span-12 lg:col-span-4 flex items-center gap-4">
                                    <div class="relative w-12 h-12 shrink-0">
                                        @if ($user->foto)
                                            <img src="{{ asset('storage/' . $user->foto) }}" class="w-full h-full object-cover rounded-full shadow-sm border-2 border-white group-hover:scale-110 transition-transform">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-orange-100 to-orange-200 rounded-full flex items-center justify-center text-orange-600 font-bold text-lg border-2 border-white">
                                                {{ strtoupper(substr($user->nama, 0, 2)) }}
                                            </div>
                                        @endif
                                        <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-500 ring-2 ring-white"></span>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-800 group-hover:text-[#FF6B35] transition-colors">{{ $user->nama }}</h3>
                                        <span class="text-xs text-gray-400 font-medium">ID: #PGW-{{ $user->id }}</span>
                                    </div>
                                </div>

                                <div class="col-span-6 lg:col-span-2">
                                    <span class="lg:hidden text-xs font-bold text-gray-400 uppercase block mb-1">Role:</span>
                                    @php
                                        $roleColor = match($user->role) {
                                            'admin' => 'bg-purple-50 text-purple-700 border-purple-100',
                                            'owner' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'kasir' => 'bg-orange-50 text-orange-700 border-orange-100',
                                            default => 'bg-gray-50 text-gray-600'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-extrabold border uppercase {{ $roleColor }}">
                                        {{ $user->role }}
                                    </span>
                                    <p class="text-[10px] text-gray-400 mt-1 font-medium">{{ $user->jabatan }}</p>
                                </div>

                                <div class="col-span-6 lg:col-span-2">
                                    <span class="lg:hidden text-xs font-bold text-gray-400 uppercase block mb-1">Kontak:</span>
                                    <div class="flex items-center gap-2">
                                        <div class="p-1 rounded-full bg-green-50 text-green-600"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg></div>
                                        <span class="text-sm font-bold text-gray-700 font-mono">{{ $user->no_hp }}</span>
                                    </div>
                                </div>

                                <div class="col-span-6 lg:col-span-2 text-left lg:text-center">
                                    <span class="lg:hidden text-xs font-bold text-gray-400 uppercase block mb-1">Status:</span>
                                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-black border border-green-100">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> AKTIF
                                    </span>
                                </div>

                                <div class="col-span-6 lg:col-span-1 flex justify-end items-center gap-2">
                                    <a href="{{ route('pegawai.card', $user->id) }}" target="_blank" class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="Cetak Kartu">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                    <a href="{{ route('pegawai.edit', $user->id) }}" class="p-2 rounded-lg text-gray-400 hover:text-[#FF6B35] hover:bg-orange-50 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <button onclick="confirmDelete('{{ $user->id }}', '{{ $user->nama }}')" class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('pegawai.destroy', $user->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 text-center bg-white rounded-3xl border border-dashed border-gray-200">
                                <div class="bg-orange-50 p-4 rounded-full mb-4">
                                    <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Belum ada pegawai</h3>
                                <p class="text-gray-500 text-sm mt-1">Mulai dengan menambahkan staf baru.</p>
                                <a href="{{ route('pegawai.create') }}" class="mt-4 px-6 py-2 bg-gray-900 text-white rounded-lg text-sm font-bold hover:bg-gray-800 transition">Tambah Pegawai</a>
                            </div>
                        @endforelse
                    </div>

                    <div id="paginationWrapper" class="mt-8 px-4 flex justify-center">
                        {{ $pegawaiList->links() }} 
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Pegawai?',
                html: "Yakin ingin menghapus <b>" + name + "</b>?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#1F2937',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-xl px-6', cancelButton: 'rounded-xl px-6' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            })
        }

        // AJAX SEARCH ENGINE
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const dataWrapper = document.getElementById('dataWrapper');
            const skeletonWrapper = document.getElementById('skeletonWrapper');
            const spinner = document.getElementById('searchSpinner');
            let timeout = null;

            searchInput.addEventListener('input', function() {
                const query = this.value;
                spinner.classList.remove('hidden');
                dataWrapper.classList.add('opacity-50'); 

                clearTimeout(timeout);

                timeout = setTimeout(() => {
                    dataWrapper.classList.add('hidden'); 
                    dataWrapper.classList.remove('opacity-50');
                    skeletonWrapper.classList.remove('hidden');

                    fetch(`${window.location.pathname}?search=${query}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newData = doc.getElementById('dataWrapper');
                        if(newData) dataWrapper.innerHTML = newData.innerHTML;

                        skeletonWrapper.classList.add('hidden');
                        dataWrapper.classList.remove('hidden');
                        spinner.classList.add('hidden');
                    })
                    .catch(err => {
                        console.error(err);
                        skeletonWrapper.classList.add('hidden');
                        dataWrapper.classList.remove('hidden');
                        spinner.classList.add('hidden');
                    });
                }, 500);
            });
        });
        
        @if (session('success'))
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success', 
                title: '{{ session('success') }}', 
                showConfirmButton: false, timer: 3000,
                background: '#fff', iconColor: '#10B981',
                customClass: { popup: 'rounded-2xl shadow-xl border border-gray-100' }
            });
        @endif
    </script>
</x-app-layout>