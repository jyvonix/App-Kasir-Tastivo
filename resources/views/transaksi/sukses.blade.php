<x-app-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 lg:pl-72">
        <div class="bg-white p-8 rounded-3xl shadow-xl text-center max-w-md w-full mx-4">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h2 class="text-2xl font-black text-gray-800 mb-2">Pembayaran Berhasil!</h2>
            <p class="text-gray-500 mb-8">Transaksi telah berhasil disimpan dan status pembayaran sudah LUNAS.</p>
            
            <div class="space-y-3">
                <a href="{{ route('transaksi.show', $id) }}" class="block w-full py-3 bg-gray-800 hover:bg-black text-white rounded-xl font-bold transition-colors">
                    Lihat Struk / Cetak
                </a>
                <a href="{{ route('transaksi.index') }}" class="block w-full py-3 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl font-bold transition-colors">
                    Kembali ke Kasir
                </a>
            </div>
        </div>
    </div>
</x-app-layout>