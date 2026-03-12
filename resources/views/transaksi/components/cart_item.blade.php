@if(count($cart) > 0)
    @foreach($cart as $id => $item)
        <div class="group relative flex gap-3 items-center p-2 bg-white rounded-2xl border border-gray-100 hover:border-orange-200 transition-all duration-300">
            <!-- INFO & CONTROLS -->
            <div class="flex-1 min-w-0 px-1">
                <div class="flex justify-between items-start mb-1">
                    <h4 class="text-xs font-black text-gray-800 truncate leading-tight group-hover:text-orange-600 transition-colors">{{ $item['nama'] }}</h4>
                    <button onclick="hapusItem({{ $id }})" class="p-0.5 text-gray-300 hover:text-red-500 transition-all">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="flex items-center justify-between mt-2">
                    <!-- QUANTITY CONTROLS (Ultra Compact) -->
                    <div class="flex items-center bg-gray-50 rounded-lg p-0.5 border border-gray-100">
                        <button onclick="updateQty({{ $id }}, 'minus')" class="w-5 h-5 flex items-center justify-center text-gray-400 hover:bg-white hover:text-red-500 rounded-md transition-all font-black text-[10px]">-</button>
                        <span class="text-[11px] font-black text-gray-900 px-2 min-w-[20px] text-center tabular-nums">{{ $item['qty'] }}</span>
                        <button onclick="updateQty({{ $id }}, 'plus')" class="w-5 h-5 flex items-center justify-center text-gray-400 hover:bg-white hover:text-[#FF6B35] rounded-md transition-all font-black text-[10px]">+</button>
                    </div>

                    <!-- PRICE -->
                    <p class="text-xs font-black text-gray-900 tracking-tighter">
                        <span class="text-[9px] text-gray-400 font-medium mr-0.5">@</span>{{ number_format($item['price'] / 1000, 0) }}k
                        <span class="mx-1 text-gray-200">|</span>
                        <span class="text-[#FF6B35] font-black">{{ number_format(($item['price'] * $item['qty']) / 1000, 0) }}k</span>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="flex flex-col items-center justify-center py-20 text-center opacity-40">
        <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Keranjang Kosong</p>
    </div>
@endif