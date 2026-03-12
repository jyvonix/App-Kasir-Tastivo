<x-app-layout>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .custom-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
            .custom-scroll::-webkit-scrollbar-track { background: transparent; }
            .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
            .custom-scroll::-webkit-scrollbar-thumb:hover { background: #fb923c; }
            .pos-container { display: flex; height: calc(100vh - 60px); width: 100%; gap: 20px; margin: 0; padding: 10px 0; }
            .catalog-panel { flex: 1; display: flex; flex-direction: column; min-width: 0; }
            .cart-panel { width: 400px; flex-shrink: 0; background: white; border-radius: 2.5rem; display: flex; flex-direction: column; border: 1px solid #f1f5f9; box-shadow: 0 10px 50px rgba(255, 107, 53, 0.08); overflow: hidden; }
            .menu-item-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            .menu-item-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(255, 107, 53, 0.1); }
            [x-cloak] { display: none !important; }
        </style>
    </head>

    <div class="pos-container" x-data="posApp()">
        
        <!-- KOLOM KIRI: KATALOG MENU -->
        <div class="catalog-panel">
            <div class="mb-6 space-y-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="w-8 h-[2px] bg-[#FF6B35] rounded-full"></span>
                            <span class="text-[10px] font-black text-orange-500 uppercase tracking-[0.3em]">Point of Sale</span>
                        </div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Tastivo <span class="text-gray-400 font-light">POS</span></h1>
                    </div>
                    <div class="relative w-full md:w-80">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" id="searchInput" @input="filterMenu($event.target.value)" placeholder="Cari menu favorit..." 
                               class="w-full pl-12 pr-4 py-3 bg-white border border-gray-100 rounded-2xl text-sm font-bold shadow-sm focus:ring-4 focus:ring-orange-100 focus:border-[#FF6B35] transition-all">
                    </div>
                </div>

                <div class="flex gap-2 overflow-x-auto pb-2 custom-scroll">
                    <button @click="filterCategory('all')" :class="activeCategory === 'all' ? 'bg-gray-900 text-white shadow-lg active' : 'bg-white text-gray-400 border border-gray-100'" class="filter-btn shrink-0 px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                        🔥 Semua
                    </button>
                    @if(isset($kategoris))
                        @foreach($kategoris as $kat)
                            <button @click="filterCategory('{{ $kat->id }}')" :class="activeCategory == '{{ $kat->id }}' ? 'bg-gray-900 text-white shadow-lg active' : 'bg-white text-gray-400 border border-gray-100'" class="filter-btn shrink-0 px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all hover:text-[#FF6B35] hover:border-orange-100">
                                {{ $kat->nama_kategori }}
                            </button>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 custom-scroll">
                <div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6 pb-10" id="menuContainer">
                    @foreach ($produks as $produk)
                        @php
                            $imgSrc = $produk->gambar_url ?? ($produk->gambar_file ? asset('storage/produk/' . basename($produk->gambar_file)) : null);
                            $harga = (int) preg_replace('/[^0-9]/', '', $produk->harga_jual ?? $produk->harga ?? 0);
                        @endphp
                        <div class="menu-item menu-item-card group bg-white rounded-[2rem] border border-gray-100 overflow-hidden flex flex-col cursor-pointer select-none"
                             @click="addToCart({{ $produk->id }})" 
                             data-name="{{ strtolower($produk->nama_produk) }}" 
                             data-category-id="{{ $produk->kategori_id }}">
                            <div class="h-40 w-full overflow-hidden relative bg-gray-50">
                                @if($imgSrc)
                                    <img src="{{ $imgSrc }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-orange-100">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                @if($produk->stok <= 5)
                                    <div class="absolute top-3 left-3 px-2.5 py-1 bg-red-500/90 backdrop-blur-md text-white text-[9px] font-black uppercase rounded-lg">Sisa {{ $produk->stok }}</div>
                                @endif
                            </div>
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <h3 class="font-black text-gray-800 text-sm leading-snug line-clamp-2">{{ $produk->nama_produk }}</h3>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-base font-black text-[#FF6B35]">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                                    <div class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center group-hover:bg-[#FF6B35] group-hover:text-white transition-all shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: PANEL KERANJANG -->
        <div class="cart-panel relative flex flex-col h-full bg-white rounded-[2rem] border border-gray-100 shadow-xl overflow-hidden">
            <!-- Header Compact -->
            <div class="shrink-0">
                <div class="px-5 py-3.5 bg-gray-900 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-sm font-black tracking-widest uppercase">Cart</h2>
                        <p class="text-[8px] font-medium text-gray-500 uppercase tracking-[0.2em]">Order Summary</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('transaksi.reset') }}" class="p-1.5 hover:bg-red-500 rounded-lg transition-all text-gray-500 hover:text-white" title="Reset">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </a>
                    </div>
                </div>
                
                <!-- Customer Name Compact -->
                <div class="px-4 py-2.5 bg-gray-50/50 border-b border-gray-100">
                    <input type="text" form="checkoutForm" name="nama_pelanggan" id="nama_pelanggan" required
                        class="w-full px-3 py-2 bg-white border border-gray-100 rounded-xl text-[10px] font-black text-gray-800 placeholder-gray-300 focus:ring-1 focus:ring-orange-500 outline-none transition-all uppercase tracking-wider" 
                        placeholder="PELANGGAN / NO. MEJA">
                </div>
            </div>

            <!-- Cart List (Flexible height) -->
            <div id="cartList" class="flex-1 min-h-0 overflow-y-auto p-3 space-y-2 custom-scroll bg-white">
                @include('transaksi.components.cart_item')
            </div>

            <!-- Checkout Area Compact -->
            <div class="bg-gray-50/50 border-t border-gray-100 p-4 shrink-0">
                <form action="{{ route('transaksi.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="total_bayar" :value="grandTotal">
                    <input type="hidden" name="kode_promo" :value="voucherCode">
                    <input type="hidden" name="potongan_promo" :value="voucherAmount">
                    <input type="hidden" name="uang_dibayar" :value="paymentMethod === 'tunai' ? cashInput : 0">

                    <!-- Totals Tighter -->
                    <div class="space-y-1 mb-3">
                        <div class="flex justify-between text-[9px] font-bold text-gray-400 uppercase tracking-tighter">
                            <span>Subtotal</span>
                            <span class="text-gray-800" x-text="formatRupiah(subTotal)">Rp 0</span>
                        </div>
                        <div x-show="diskonToko > 0" class="flex justify-between text-[9px] font-bold text-green-600 tracking-tighter">
                            <span>Disc. Shop</span>
                            <span x-text="'- ' + formatRupiah(diskonToko)">- Rp 0</span>
                        </div>
                        <div x-show="voucherAmount > 0" class="flex justify-between text-[9px] font-bold text-orange-500 tracking-tighter">
                            <span>Voucher</span>
                            <span x-text="'- ' + formatRupiah(voucherAmount)">- Rp 0</span>
                        </div>
                        <div x-show="pajakPersen > 0" class="flex justify-between text-[9px] font-bold text-gray-400 uppercase tracking-tighter">
                            <span>Pajak (<span x-text="pajakPersen"></span>%)</span>
                            <span class="text-gray-800" x-text="formatRupiah(pajakAmount)">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-1 border-t border-gray-200">
                            <span class="text-[9px] font-black text-gray-900 uppercase">Total</span>
                            <span class="text-xl font-black text-gray-900 tracking-tighter" x-text="formatRupiah(grandTotal)">Rp 0</span>
                        </div>
                    </div>

                    <!-- Payment Tighter -->
                    <div class="grid grid-cols-2 gap-1.5 mb-3">
                        <button type="button" @click="paymentMethod = 'tunai'"
                            :class="paymentMethod === 'tunai' ? 'bg-[#FF6B35] text-white shadow-lg shadow-orange-100' : 'bg-white text-gray-400 border border-gray-100'"
                            class="py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">TUNAI</button>
                        <button type="button" @click="paymentMethod = 'qris'"
                            :class="paymentMethod === 'qris' ? 'bg-[#FF6B35] text-white shadow-lg shadow-orange-100' : 'bg-white text-gray-400 border border-gray-100'"
                            class="py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">QRIS</button>
                    </div>

                    <!-- Tunai Section Tighter -->
                    <div x-show="paymentMethod === 'tunai'" x-collapse>
                        <div class="flex gap-1.5 mb-2">
                            <input type="number" x-model.number="cashInput" 
                                class="flex-1 px-3 py-2 bg-white border border-gray-200 rounded-xl font-black text-xs text-gray-900 focus:ring-1 focus:ring-orange-500 outline-none" 
                                placeholder="BAYAR...">
                            <button type="button" @click="cashInput = grandTotal" class="px-3 bg-gray-900 text-white rounded-xl text-[9px] font-black">PAS</button>
                        </div>
                        <div class="flex justify-between items-center px-1 mb-3">
                            <span class="text-[9px] text-gray-400 font-bold uppercase">Kembali</span>
                            <span class="text-xs font-black text-green-600" x-text="formatRupiah(Math.max(0, cashInput - grandTotal))">Rp 0</span>
                        </div>
                    </div>

                    <button type="button" @click="submitCheckout()" 
                        class="w-full py-3.5 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl font-black text-[10px] text-white uppercase tracking-[0.2em] shadow-xl hover:shadow-orange-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span x-text="paymentMethod === 'tunai' ? 'BAYAR SEKARANG' : 'GENERATE QRIS'"></span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>

                    <div class="mt-2 text-center">
                        <button type="button" @click="toggleVoucherInput()" class="text-[8px] font-black text-gray-400 uppercase tracking-widest hover:text-[#FF6B35]">
                            + Pakai Voucher
                        </button>
                        <div x-show="showVoucherInput" class="mt-2 flex gap-1" style="display: none;">
                            <input type="text" x-model="voucherInput" placeholder="KODE" class="flex-1 bg-white border border-gray-200 rounded-lg text-[9px] font-black uppercase px-2 py-1.5 focus:ring-1 focus:ring-[#FF6B35]">
                            <button type="button" @click="checkPromo()" class="px-2 bg-gray-900 text-white rounded-lg text-[8px] font-black">OK</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- GLOBAL FUNCTIONS FOR COMPATIBILITY WITH BLADE COMPONENTS -->
    <script>
        // Bridge functions to allow onclick from Blade (e.g., cart items) to call Alpine methods
        function updateQty(id, action) { window.dispatchEvent(new CustomEvent('update-qty', { detail: { id, action } })); }
        function hapusItem(id) { window.dispatchEvent(new CustomEvent('hapus-item', { detail: { id } })); }
        function addToCart(id) { window.dispatchEvent(new CustomEvent('add-to-cart', { detail: { id } })); }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posApp', () => ({
                subTotal: {{ $summary['subTotal'] }},
                pajakPersen: {{ isset($setting) ? $setting->pajak_persen : 0 }},
                diskonToko: {{ $summary['diskon'] }},
                voucherAmount: 0,
                voucherCode: '',
                voucherInput: '',
                paymentMethod: 'tunai',
                cashInput: '',
                activeCategory: 'all',
                showVoucherInput: false,
                csrfToken: document.querySelector('meta[name="csrf-token"]').content,

                get pajakAmount() {
                    let taxBase = this.subTotal - this.diskonToko - this.voucherAmount;
                    if(taxBase < 0) taxBase = 0;
                    return Math.round(taxBase * (this.pajakPersen / 100));
                },

                get grandTotal() {
                    let taxBase = this.subTotal - this.diskonToko - this.voucherAmount;
                    if(taxBase < 0) taxBase = 0;
                    return taxBase + this.pajakAmount;
                },

                init() {
                    // Listen for global events
                    window.addEventListener('add-to-cart', (e) => this.addToCartLogic(e.detail.id));
                    window.addEventListener('update-qty', (e) => this.updateQtyLogic(e.detail.id, e.detail.action));
                    window.addEventListener('hapus-item', (e) => this.hapusItemLogic(e.detail.id));
                },

                formatRupiah(n) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
                },

                filterCategory(id) {
                    this.activeCategory = id;
                    document.querySelectorAll('.menu-item').forEach(el => {
                        if(id === 'all' || el.getAttribute('data-category-id') == id) el.style.display = 'flex'; else el.style.display = 'none';
                    });
                },

                filterMenu(term) {
                    term = term.toLowerCase();
                    document.querySelectorAll('.menu-item').forEach(el => {
                        if(el.getAttribute('data-name').includes(term)) el.style.display = 'flex'; else el.style.display = 'none';
                    });
                },

                toggleVoucherInput() {
                    this.showVoucherInput = !this.showVoucherInput;
                },

                async checkPromo() {
                    if(!this.voucherInput.trim()) return;
                    
                    try {
                        let res = await fetch("{{ route('promo.check') }}", { 
                            method: "POST", 
                            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": this.csrfToken }, 
                            body: JSON.stringify({ kode: this.voucherInput, total_belanja: this.subTotal }) 
                        });
                        let data = await res.json();
                        
                        if(data.status === 'success') {
                            this.voucherAmount = data.potongan;
                            this.voucherCode = data.kode;
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Voucher diterapkan.', timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-[2rem]' } });
                        } else {
                            this.voucherAmount = 0;
                            this.voucherCode = '';
                            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, customClass: { popup: 'rounded-[2rem]' } });
                        }
                    } catch(err) {
                        console.error(err);
                    }
                },

                async updateCartState(url, body) {
                    try {
                        let res = await fetch(url, { 
                            method: "POST", 
                            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": this.csrfToken }, 
                            body: JSON.stringify(body) 
                        });
                        let data = await res.json();
                        
                        if(data.status === 'success') {
                            document.getElementById('cartList').innerHTML = data.html;
                            this.subTotal = data.totals.subTotal;
                            this.diskonToko = data.totals.diskon;
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, customClass: { popup: 'rounded-[2rem]' } });
                        }
                    } catch(err) { console.error(err); }
                },

                addToCartLogic(id) { this.updateCartState("{{ route('transaksi.tambah') }}", { id: id }); },
                updateQtyLogic(id, action) { this.updateCartState("{{ route('transaksi.update_qty') }}", { id: id, action: action }); },
                
                async hapusItemLogic(id) {
                    try {
                        let res = await fetch("/transaksi/hapus/" + id, { method: "GET" });
                        let data = await res.json();
                        document.getElementById('cartList').innerHTML = data.html;
                        this.subTotal = data.totals.subTotal;
                        this.diskonToko = data.totals.diskon;
                    } catch(err) { console.error(err); }
                },

                submitCheckout() {
                    const nama = document.getElementById('nama_pelanggan').value.trim();
                    if(!nama) {
                        Swal.fire({ icon: 'warning', title: 'Pelanggan Belum Diisi', text: 'Mohon isi nama pelanggan.', confirmButtonColor: '#FF6B35', customClass: { popup: 'rounded-[2rem]' } }); return;
                    }
                    if(this.subTotal <= 0) {
                        Swal.fire({ icon: 'warning', title: 'Keranjang Kosong', confirmButtonColor: '#FF6B35', customClass: { popup: 'rounded-[2rem]' } }); return; 
                    }
                    if(this.paymentMethod === 'tunai' && this.cashInput < this.grandTotal) {
                        Swal.fire({ icon: 'error', title: 'Uang Kurang', text: 'Pembayaran tunai kurang ' + this.formatRupiah(this.grandTotal - this.cashInput), confirmButtonColor: '#FF6B35', customClass: { popup: 'rounded-[2rem]' } }); return;
                    }
                    
                    Swal.fire({ title: 'Memproses...', didOpen: () => { Swal.showLoading() }, customClass: { popup: 'rounded-[2rem]' } });
                    document.getElementById('checkoutForm').submit();
                }
            }));
        });
    </script>
</x-app-layout>