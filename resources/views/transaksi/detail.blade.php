<x-app-layout>
    <head>
        <script type="text/javascript"
            src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            body { font-family: 'Outfit', sans-serif; background-color: #F8FAFC; }
            .font-mono { font-family: 'Space Mono', monospace; }
            
            /* --- LAYOUT FIX --- */
            .main-container {
                width: 100%;
                max-width: 1200px; /* Lebar maksimal yang ideal untuk struk */
                margin: 0 auto;    /* Center container */
                padding: 40px 20px;
            }

            /* --- RECEIPT STYLE (CLEAN & MODERN) --- */
            .receipt-card {
                background: white;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
                border-radius: 2rem;
                overflow: hidden;
                border: 1px solid #f1f5f9;
                position: relative;
            }
            
            /* Header Struk Gelap Premium */
            .receipt-header {
                background: #18181b; /* Zinc-900 */
                padding: 40px;
                text-align: center;
                color: white;
                position: relative;
            }
            
            /* Efek Sobekan Kertas (Zigzag) */
            .zigzag-edge {
                background-image: linear-gradient(135deg, #ffffff 25%, transparent 25%), linear-gradient(225deg, #ffffff 25%, transparent 25%);
                background-position: 0 0;
                background-size: 20px 20px;
                height: 10px;
                width: 100%;
                margin-top: -10px; /* Overlap header */
                position: relative;
                z-index: 10;
            }

            @media print {
                @page {
                    margin: 0;
                    size: 58mm auto;
                }
                
                /* Reset Global for Print */
                html, body {
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #fff !important;
                    width: 58mm !important;
                }

                /* Hide everything using visibility to keep structure but not print */
                body * {
                    visibility: hidden !important;
                }

                /* Show ONLY the printable area and its children */
                #printableArea, #printableArea * {
                    visibility: visible !important;
                    color: #000 !important;
                    background: #fff !important;
                    -webkit-print-color-adjust: exact;
                }

                /* Position the printable area at the very top left */
                #printableArea {
                    position: absolute !important;
                    left: 0 !important;
                    top: 0 !important;
                    width: 58mm !important;
                    margin: 0 !important;
                    padding: 5px !important;
                    display: block !important;
                }
                
                /* Force Layout Elements to fit 58mm */
                .receipt-card {
                    box-shadow: none !important;
                    border: none !important;
                    width: 100% !important;
                    max-width: 58mm !important;
                }

                .receipt-header {
                    background: transparent !important;
                    padding: 0 !important;
                    margin-bottom: 10px !important;
                }

                .receipt-header h1 {
                    font-size: 14pt !important;
                    font-weight: bold !important;
                    text-align: center !important;
                    display: block !important;
                }

                .receipt-header p {
                    font-size: 8pt !important;
                    text-align: center !important;
                    display: block !important;
                }

                /* Ensure Flexbox works in Print */
                .flex {
                    display: flex !important;
                    flex-direction: row !important;
                    justify-content: space-between !important;
                    width: 100% !important;
                }

                /* Compact spacing for 58mm paper */
                .p-10 { padding: 0 !important; }
                .p-6 { padding: 5px 0 !important; }
                .mb-6, .mb-8 { margin-bottom: 5px !important; }
                .pb-6 { padding-bottom: 5px !important; }
                .space-y-4 > * + * { margin-top: 5px !important; }

                /* Text Sizes for Thermal VSC */
                .text-lg, .text-sm, .text-xs, .text-2xl, .text-3xl {
                    font-size: 9pt !important;
                    font-weight: bold !important;
                }
                
                .font-mono {
                    font-family: 'Courier New', monospace !important;
                    font-size: 8pt !important;
                }

                /* Clear Dashed Lines */
                .border-b, .border-t, .border-dashed {
                    border-bottom: 1px dashed #000 !important;
                    height: 1px !important;
                    margin: 5px 0 !important;
                    display: block !important;
                    width: 100% !important;
                }

                /* Hide non-essential decorations */
                .zigzag-edge, svg, .bg-white\/10, .no-print {
                    display: none !important;
                }

                /* Table-like appearance for items */
                .group {
                    border-bottom: 0.5px solid #eee !important;
                    margin-bottom: 5px !important;
                    padding-bottom: 2px !important;
                }
            }
        </style>
    </head>

    <div class="min-h-screen bg-[#F8FAFC]">
        <div class="main-container">

            <!-- Navigasi Balik -->
            <div class="flex justify-between items-center mb-8 no-print">
                <a href="{{ route('transaksi.index') }}" class="group flex items-center gap-2 text-gray-500 hover:text-[#FF6B35] font-bold transition-colors">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke POS
                </a>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-[#FF6B35] rounded-full animate-pulse"></span>
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Transaction Details</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <!-- KARTU STRUK (TENGAH / KIRI) -->
                <div class="lg:col-span-7">
                    <div id="printableArea" class="receipt-card">
                        
                        <!-- Header Struk -->
                        <div class="receipt-header">
                            <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20 shadow-lg">
                                <svg class="w-8 h-8 text-[#FF6B35]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <h1 class="text-3xl font-black tracking-tight mb-1">TASTIVO</h1>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.3em]">Official Receipt</p>
                        </div>
                        <div class="zigzag-edge"></div>

                        <!-- Isi Struk -->
                        <div class="p-10">
                            <!-- Info Utama -->
                            <div class="flex justify-between items-end pb-6 border-b border-dashed border-gray-200 mb-6">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Customer</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $transaksi->nama_pelanggan ?? 'Guest' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Order ID</p>
                                    <p class="text-sm font-mono font-bold text-gray-600 bg-gray-50 px-2 py-1 rounded border border-gray-100">{{ $transaksi->kode_transaksi }}</p>
                                </div>
                            </div>

                            <!-- List Item -->
                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">
                                    <span>Item Description</span>
                                    <span>Amount</span>
                                </div>
                                @foreach($transaksi->details as $detail)
                                <div class="flex justify-between items-start group hover:bg-gray-50 p-2 -mx-2 rounded-lg transition-colors">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 group-hover:text-[#FF6B35] transition-colors">{{ $detail->nama_produk }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900 font-mono">Rp {{ number_format($detail->sub_total_produk, 0, ',', '.') }}</p>
                                </div>
                                @endforeach
                            </div>

                            <!-- Total Summary -->
                            <div class="bg-gray-50 rounded-2xl p-6 space-y-3 border border-gray-100">
                                <div class="flex justify-between text-xs font-medium text-gray-500">
                                    <span>Subtotal</span>
                                    <span class="font-mono">Rp {{ number_format($transaksi->details->sum('sub_total_produk'), 0, ',', '.') }}</span>
                                </div>
                                @if($transaksi->diskon > 0)
                                <div class="flex justify-between text-xs font-bold text-green-600">
                                    <span>Discount</span>
                                    <span class="font-mono">- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between text-xs font-medium text-gray-500 border-b border-gray-200 pb-3">
                                    <span>Tax (PPN)</span>
                                    <span class="font-mono">Rp {{ number_format(($transaksi->total_harga - ($transaksi->details->sum('sub_total_produk') - $transaksi->diskon)), 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-1">
                                    <span class="text-sm font-black text-gray-900 uppercase tracking-wide">Total Paid</span>
                                    <span class="text-2xl font-black text-[#FF6B35] font-mono">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <!-- Footer Struk -->
                            <div class="mt-8 text-center space-y-1">
                                <p class="text-xs text-gray-400 font-medium italic">Thank you for dining with us!</p>
                                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y • H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PANEL KANAN: STATUS & AKSI -->
                <div class="lg:col-span-5 space-y-6 no-print">
                    
                    <!-- Kartu Status -->
                    <div class="bg-white p-8 rounded-[2rem] shadow-lg border border-gray-100 text-center relative overflow-hidden">
                        @if($transaksi->status_pembayaran == 'Lunas' || $transaksi->status_pembayaran == 'success' || $transaksi->status_pembayaran == 'settlement' || $transaksi->bayar >= $transaksi->total_harga)
                            <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
                            <div class="relative z-10">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600 shadow-inner ring-4 ring-white">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Pembayaran Lunas</h2>
                                <p class="text-sm font-bold text-green-600 mt-2 bg-green-50 inline-block px-4 py-1.5 rounded-full border border-green-100 shadow-sm uppercase tracking-wider">Completed</p>
                            </div>
                        @else
                            <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
                            <div class="relative z-10">
                                <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 text-[#FF6B35] shadow-inner ring-4 ring-white animate-pulse">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Menunggu Pembayaran</h2>
                                <p class="text-sm font-bold text-orange-600 mt-2 bg-orange-50 inline-block px-4 py-1.5 rounded-full border border-orange-100 shadow-sm uppercase tracking-wider">Pending</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4 mt-8 pt-8 border-t border-gray-100 text-left">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Metode</p>
                                <p class="text-sm font-black text-gray-800 mt-1">{{ $transaksi->bayar > 0 ? 'TUNAI (CASH)' : 'QRIS / ONLINE' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kasir</p>
                                <p class="text-sm font-black text-gray-800 mt-1">{{ $transaksi->user->nama ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="space-y-3">
                        @if($transaksi->status_pembayaran != 'Lunas')
                            <button onclick="triggerSnap()" class="w-full py-4 bg-gray-900 hover:bg-[#FF6B35] text-white rounded-2xl font-black text-sm uppercase tracking-[0.15em] transition-all shadow-xl hover:shadow-orange-200 hover:-translate-y-1">
                                Bayar Sekarang
                            </button>
                        @endif

                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="window.print()" class="flex items-center justify-center gap-2 py-4 bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 rounded-2xl font-bold text-sm transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak
                            </button>
                            <a href="{{ route('transaksi.cek_status', $transaksi->id) }}" class="flex items-center justify-center gap-2 py-4 bg-white border border-gray-200 hover:border-blue-200 hover:bg-blue-50 text-blue-600 rounded-2xl font-bold text-sm transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Cek Status
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Script Pembayaran Otomatis -->
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            @if($transaksi->status_pembayaran != 'Lunas' && $transaksi->bayar == 0 && session('success'))
                triggerSnap();
            @endif
        });

        function triggerSnap() {
            @if($transaksi->snap_token)
                window.snap.pay('{{ $transaksi->snap_token }}', {
                    onSuccess: function(result){
                        Swal.fire({
                            icon: 'success', title: 'Lunas!', text: 'Pembayaran berhasil.', confirmButtonColor: '#10B981',
                        }).then(() => { window.location.href = "{{ route('transaksi.pembayaran_sukses', $transaksi->id) }}"; });
                    },
                    onPending: function(result){ console.log("Pending"); },
                    onError: function(result){ Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pembayaran gagal.'}); }
                });
            @else
                Swal.fire({ icon: 'error', title: 'Error', text: 'Token pembayaran tidak ditemukan.' });
            @endif
        }
        
        @if(session('success'))
            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif
    </script>
</x-app-layout>