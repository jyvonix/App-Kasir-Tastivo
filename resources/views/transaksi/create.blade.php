<x-app-layout>
    {{-- Ini adalah slot 'header' (judul halaman) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            TRANSAKSI
        </h2>
    </x-slot>

    {{-- Di sinilah kita memanggil komponen Livewire --}}
    <div>
        <livewire:transaksi-form />
    </div>

</x-app-layout>