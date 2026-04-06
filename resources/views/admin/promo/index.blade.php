<x-app-layout>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; }
            [x-cloak] { display: none !important; }
            
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

            /* --- 4. CUSTOM SCROLLBAR --- */
            .custom-scroll::-webkit-scrollbar { width: 6px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255, 107, 53, 0.2); border-radius: 10px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255, 107, 53, 0.4); }
        </style>
    </head>

    <div class="mesh-background"></div>

    {{-- Decorative Elements --}}
    <div class="fixed top-20 right-[5%] opacity-10 floating-icon pointer-events-none hidden lg:block">
        <svg class="w-32 h-32 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11 9H9V2H7V9H5V2H3V9c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>
    </div>
    <div class="fixed bottom-20 left-[5%] opacity-10 floating-icon-delayed pointer-events-none hidden lg:block">
        <svg class="w-24 h-24 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V1h-1.97v4.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v8.05zM1 22.99V9c0-2.21 1.79-4 4-4h10c2.21 0 4 1.79 4 4v13.99H1zM3.4 15h12.2l-6.1-9-6.1 9z"/></svg>
    </div>

    <div class="relative w-full">
        {{-- Background Decoration --}}
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-orange-50 via-white to-orange-50/50 rounded-3xl"></div>
        
        <div class="w-full">
            <!-- HEADER SECTION -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-10 relative z-10">
                <div class="space-y-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-[2px] bg-[#FF6B35] rounded-full"></span>
                        <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.3em]">Marketing & Promotion</span>
                    </div>
                    <h1 class="text-4xl lg:text-5xl text-gray-900 tracking-tight leading-tight">
                        <span class="font-medium">Promo &</span>
                        <span class="font-light text-gray-400">Voucher</span>
                    </h1>
                    <p class="text-gray-400 font-medium tracking-wide pt-2">Kelola strategi diskon untuk memanjakan pelanggan Tastivo.</p>
                </div>
                
                @if(Auth::user()->role === 'admin')
                <button onclick="openModal('create')" 
                    class="group relative inline-flex items-center justify-center px-8 py-4 text-sm font-black text-white transition-all duration-300 bg-gray-900 rounded-[1.5rem] hover:bg-[#FF6B35] hover:shadow-xl hover:shadow-orange-200 hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2 transform group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Voucher Baru
                </button>
                @endif
            </div>

            <!-- GRID KARTU PROMO -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 relative z-10">
                @forelse($promos as $promo)
                    @php
                        $percent = 0;
                        $isUnlimited = is_null($promo->batas_pemakaian);
                        $isHabis = false;
                        if (!$isUnlimited) {
                            $percent = ($promo->batas_pemakaian > 0) ? ($promo->jumlah_klaim / $promo->batas_pemakaian) * 100 : 100;
                            if ($percent >= 100) { $percent = 100; $isHabis = true; }
                        }
                    @endphp

                    <div class="group relative">
                        {{-- Background Glow --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-[#FF6B35] to-red-500 opacity-0 group-hover:opacity-10 rounded-[2.5rem] blur-2xl transition-opacity duration-500"></div>
                        
                        <div class="glass-card rounded-[2.5rem] flex flex-col h-full relative transition-all duration-500 group-hover:-translate-y-2 group-hover:shadow-2xl overflow-hidden">
                            
                            {{-- Voucher Cutout Effect --}}
                            <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-[#F1F5F9] rounded-full z-20"></div>
                            <div class="absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-[#F1F5F9] rounded-full z-20"></div>

                            <!-- Card Top -->
                            <div class="p-8 pb-4 flex justify-between items-start relative z-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-black shadow-lg
                                        {{ $promo->tipe == 'persen' ? 'bg-gradient-to-br from-orange-400 to-red-500 text-white shadow-orange-200' : 'bg-gradient-to-br from-green-400 to-emerald-600 text-white shadow-green-200' }}">
                                        {{ $promo->tipe == 'persen' ? '%' : 'Rp' }}
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-black text-gray-900 tracking-tight group-hover:text-[#FF6B35] transition-colors uppercase">{{ $promo->kode }}</h3>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block w-2 h-2 rounded-full {{ $promo->isValid() ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                                {{ $promo->isValid() ? 'Active' : 'Expired' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="px-8 flex-1 relative z-10">
                                <div class="mb-4">
                                    <span class="text-5xl font-black text-gray-900 tracking-tighter">
                                        {{ $promo->tipe == 'persen' ? $promo->nilai . '%' : number_format($promo->nilai / 1000, 0) . 'k' }}
                                    </span>
                                    <span class="text-sm font-black text-gray-400 ml-2 uppercase tracking-widest">Off</span>
                                </div>

                                <!-- Usage Progress -->
                                <div class="space-y-2 mb-6">
                                    <div class="flex justify-between items-end">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Usage</span>
                                        <span class="text-xs font-black {{ $percent > 80 ? 'text-red-500' : 'text-[#FF6B35]' }}">{{ round($percent) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100/50 rounded-full h-2 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000 {{ $isHabis ? 'bg-red-500' : 'bg-gradient-to-r from-[#FF6B35] to-red-500' }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-bold text-right">
                                        {{ $isUnlimited ? 'Unlimited' : $promo->jumlah_klaim . '/' . $promo->batas_pemakaian }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 pb-6 border-b border-dashed border-gray-200 mb-4">
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Min. Order</p>
                                        <p class="text-sm font-black text-gray-800">Rp {{ number_format($promo->minimum_belanja, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Valid Until</p>
                                        <p class="text-sm font-black text-gray-800">{{ $promo->berakhir_pada ? $promo->berakhir_pada->format('d M Y') : 'Forever' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons (Direct Access) -->
                            @if(Auth::user()->role === 'admin')
                            <div class="px-8 pb-8 flex gap-3 relative z-20">
                                <button onclick="openModal('edit', {{ json_encode($promo) }})" 
                                    class="flex-1 py-3 rounded-xl bg-orange-50 text-[#FF6B35] font-black text-xs uppercase tracking-wider hover:bg-[#FF6B35] hover:text-white transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </button>
                                <button onclick="confirmDelete({{ $promo->id }})" 
                                    class="py-3 px-4 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            @else
                            <div class="px-8 pb-8 flex gap-3 relative z-20">
                                <div class="w-full py-3 rounded-xl bg-gray-50 text-gray-400 font-black text-[10px] uppercase tracking-widest text-center border border-gray-100">
                                    View Only Mode
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center glass-card rounded-[3rem]">
                        <div class="w-24 h-24 bg-gradient-to-br from-orange-50 to-red-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <svg class="w-10 h-10 text-[#FF6B35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-800">Belum ada Voucher</h3>
                        <p class="text-gray-500 font-medium mt-2 mb-8">Buat promo pertama Anda untuk menarik lebih banyak pelanggan!</p>
                        @if(Auth::user()->role === 'admin')
                        <button onclick="openModal('create')" class="px-10 py-4 bg-gray-900 text-white rounded-2xl font-black shadow-xl hover:bg-[#FF6B35] transition-all transform hover:-translate-y-1">Mulai Sekarang</button>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Hidden Delete Forms Container --}}
    <div id="delete-forms-container" class="hidden">
        @foreach($promos as $promo)
            <form action="{{ route('promo.destroy', $promo->id) }}" method="POST" id="delete-form-{{ $promo->id }}">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>

    <!-- MODAL PREMIUM FORM -->
    <div id="promoModal" class="fixed inset-0 z-[100] hidden overflow-y-auto custom-scroll" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>

            <div class="relative bg-white rounded-[2.5rem] shadow-2xl transform transition-all max-w-xl w-full overflow-hidden">
                {{-- Modal Header Decor --}}
                <div class="h-2 w-full bg-gradient-to-r from-[#FF6B35] to-red-600"></div>

                <div class="p-8 lg:p-10">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 tracking-tight" id="modalTitle">Konfigurasi Voucher</h3>
                            <p class="text-gray-400 text-sm font-medium mt-1">Lengkapi detail penawaran spesial Anda.</p>
                        </div>
                        <button onclick="closeModal()" class="p-3 bg-gray-50 rounded-2xl text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form id="promoForm" method="POST" action="{{ route('promo.store') }}" class="space-y-8">
                        @csrf
                        <div id="methodField"></div>

                        @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-2xl">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-red-700 uppercase tracking-wider">Perbaiki Kesalahan Berikut:</p>
                                    <ul class="mt-1 list-disc list-inside text-xs text-red-600 font-medium">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- NAMA & KODE -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Voucher Name</label>
                                <input type="text" name="nama_promo" id="inputNama" value="{{ old('nama_promo') }}"
                                    class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 placeholder-gray-300 focus:ring-4 focus:ring-orange-100 transition-all" placeholder="Diskon Akhir Tahun">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Voucher Code</label>
                                <input type="text" name="kode" id="inputKode" value="{{ old('kode') }}" required 
                                    class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-black text-xl uppercase tracking-widest placeholder-gray-300 focus:ring-4 focus:ring-orange-100 transition-all text-center" placeholder="GAJIANHOKI">
                            </div>
                        </div>

                        <!-- TIPE & NILAI -->
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Discount Type</label>
                                <select name="tipe" id="inputTipe" onchange="toggleTipe()" 
                                    class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-4 focus:ring-orange-100 appearance-none cursor-pointer">
                                    <option value="persen" {{ old('tipe') == 'persen' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="nominal" {{ old('tipe') == 'nominal' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Discount Value</label>
                                <div class="relative">
                                    <span id="prefixNilai" class="absolute left-6 top-4 text-gray-400 font-black text-lg">%</span>
                                    <input type="number" name="nilai" id="inputNilai" value="{{ old('nilai') }}" required 
                                        class="w-full pl-12 pr-6 py-4 bg-gray-50 border-none rounded-2xl font-black text-gray-900 focus:ring-4 focus:ring-orange-100 transition-all" placeholder="15">
                                </div>
                            </div>
                        </div>

                        <!-- ADVANCED SETTINGS -->
                        <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-[2rem] p-8 border border-orange-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-1.5 h-6 bg-[#FF6B35] rounded-full"></div>
                                <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest">Rules & Conditions</h4>
                            </div>

                            <div class="space-y-6">
                                {{-- Limit Switch --}}
                                <div class="flex items-center justify-between bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-white">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-800">Usage Limit</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider" id="limitDesc">Unlimited Claims</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="limit_toggle" id="toggleLimit" class="sr-only peer" onchange="toggleLimitInput()">
                                        <input type="hidden" name="limit_type" id="limitType" value="unlimited">
                                        <div class="w-12 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#FF6B35]"></div>
                                    </label>
                                </div>

                                {{-- Input Limit --}}
                                <div id="limitInputContainer" class="hidden animate-fade-in-down">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Total Coupons Available</label>
                                    <input type="number" name="batas_pemakaian" id="inputBatas" 
                                        class="w-full px-6 py-3 bg-white border-none rounded-xl font-bold text-gray-800 focus:ring-4 focus:ring-orange-200" placeholder="e.g. 100">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Min. Purchase</label>
                                        <input type="number" name="minimum_belanja" id="inputMinBelanja" 
                                            class="w-full px-4 py-3 bg-white border-none rounded-xl font-bold text-gray-800 focus:ring-4 focus:ring-orange-200" value="{{ old('minimum_belanja', 0) }}">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Max. Discount</label>
                                        <input type="number" name="maksimum_potongan" id="inputMaxPotongan" 
                                            class="w-full px-4 py-3 bg-white border-none rounded-xl font-bold text-gray-800 focus:ring-4 focus:ring-orange-200" value="{{ old('maksimum_potongan') }}" placeholder="Optional">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Date Ranges --}}
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Start Date</label>
                                <input type="date" name="mulai_berlaku" id="inputMulai" value="{{ old('mulai_berlaku') }}"
                                    class="w-full px-4 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-4 focus:ring-orange-100">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">End Date</label>
                                <input type="date" name="berakhir_pada" id="inputSelesai" value="{{ old('berakhir_pada') }}"
                                    class="w-full px-4 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-700 focus:ring-4 focus:ring-orange-100">
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" 
                                class="w-full py-5 bg-gray-900 text-white font-black rounded-[1.5rem] hover:bg-[#FF6B35] shadow-2xl hover:shadow-orange-200 transition-all transform hover:-translate-y-1 uppercase tracking-widest text-sm">
                                Save Voucher Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-reopen modal if validation errors exist
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->any())
                @if(old('_method') == 'PUT')
                    // Logic to reopen edit modal would be complex with old data, 
                    // for now reopen as create with old values is better than nothing
                    openModal('create');
                @else
                    openModal('create');
                @endif
            @endif
        });

        function toggleTipe() {
            const tipe = document.getElementById('inputTipe').value;
            const prefix = document.getElementById('prefixNilai');
            const input = document.getElementById('inputNilai');
            
            if (tipe === 'persen') {
                prefix.innerText = '%';
                input.placeholder = '15';
            } else {
                prefix.innerText = 'Rp';
                input.placeholder = '10000';
            }
        }

        function toggleLimitInput() {
            const isChecked = document.getElementById('toggleLimit').checked;
            const container = document.getElementById('limitInputContainer');
            const desc = document.getElementById('limitDesc');
            const hiddenType = document.getElementById('limitType');

            if (isChecked) {
                container.classList.remove('hidden');
                desc.innerText = 'Limited Coupons Available';
                hiddenType.value = 'limited';
            } else {
                container.classList.add('hidden');
                desc.innerText = 'Unlimited Claims Allowed';
                hiddenType.value = 'unlimited';
            }
        }

        function openModal(mode, data = null) {
            const modal = document.getElementById('promoModal');
            const form = document.getElementById('promoForm');
            const title = document.getElementById('modalTitle');
            const methodField = document.getElementById('methodField');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            if (mode === 'create') {
                title.innerText = "New Voucher";
                form.action = "{{ route('promo.store') }}";
                methodField.innerHTML = '';
                // Don't reset if we have errors, we want to keep old values
                @if(!$errors->any())
                    form.reset();
                    document.getElementById('toggleLimit').checked = false;
                    toggleLimitInput();
                @endif
            } else {
                title.innerText = "Update Voucher";
                form.action = "{{ url('promo') }}/" + data.id;
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                
                document.getElementById('inputNama').value = data.nama_promo || '';
                document.getElementById('inputKode').value = data.kode;
                document.getElementById('inputTipe').value = data.tipe;
                document.getElementById('inputNilai').value = data.nilai;
                document.getElementById('inputMinBelanja').value = data.minimum_belanja;
                document.getElementById('inputMaxPotongan').value = data.maksimum_potongan || '';
                
                // Handle Date Strings correctly
                const formatDate = (dateStr) => {
                    if (!dateStr) return '';
                    return dateStr.includes('T') ? dateStr.split('T')[0] : dateStr;
                };

                document.getElementById('inputMulai').value = formatDate(data.mulai_berlaku);
                document.getElementById('inputSelesai').value = formatDate(data.berakhir_pada);
                
                if (data.batas_pemakaian !== null) {
                    document.getElementById('toggleLimit').checked = true;
                    document.getElementById('inputBatas').value = data.batas_pemakaian;
                } else {
                    document.getElementById('toggleLimit').checked = false;
                    document.getElementById('inputBatas').value = '';
                }
                toggleLimitInput();
                toggleTipe();
            }
        }

        function closeModal() {
            document.getElementById('promoModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Voucher?',
                text: "Voucher ini akan dihapus permanen dan tidak bisa digunakan lagi oleh pelanggan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF6B35',
                cancelButtonColor: '#1F2937',
                confirmButtonText: 'Ya, Hapus Sekarang!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { 
                    popup: 'rounded-[2rem]', 
                    confirmButton: 'rounded-xl px-6 py-3 font-black uppercase tracking-widest text-xs', 
                    cancelButton: 'rounded-xl px-6 py-3 font-black uppercase tracking-widest text-xs' 
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Delete form not found for ID:', id);
                    }
                }
            })
        }

        @if(session('success'))
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success', 
                title: '{{ session('success') }}', 
                showConfirmButton: false, timer: 3000,
                background: '#fff', iconColor: '#FF6B35',
                customClass: { popup: 'rounded-2xl shadow-xl border border-gray-100' }
            });
        @endif
    </script>
</x-app-layout>