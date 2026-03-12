<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Persetujuan Izin & Sakit') }}
            </h2>
            <div class="mt-2 md:mt-0">
                <span class="px-4 py-1.5 rounded-full bg-orange-100 text-orange-600 text-sm font-bold shadow-sm">
                    {{ $pengajuan->count() }} Permintaan Menunggu
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            <!-- SECTION: PENDING APPROVALS -->
            <div class="space-y-6">
                <div class="flex items-center space-x-2">
                    <div class="h-8 w-1.5 bg-gradient-to-b from-orange-500 to-red-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-gray-800 tracking-tight">Menunggu Persetujuan</h3>
                </div>

                @if($pengajuan->isEmpty())
                    <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-dashed border-gray-300">
                        <div class="flex justify-center mb-4">
                            <div class="p-4 bg-gray-50 rounded-full">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-lg font-medium text-gray-600">Semua Beres!</h4>
                        <p class="text-gray-400 max-w-xs mx-auto mt-2">Tidak ada pengajuan izin atau sakit yang perlu diproses saat ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($pengajuan as $item)
                        <div class="group bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col">
                            <!-- Top Accent Decor -->
                            <div class="h-2 bg-gradient-to-r {{ $item->jenis == 'sakit' ? 'from-red-500 to-orange-500' : 'from-orange-400 to-yellow-500' }}"></div>
                            
                            <div class="p-6 flex-1">
                                <!-- User Info -->
                                <div class="flex items-center space-x-4 mb-6">
                                    <div class="relative">
                                        @if($item->user->foto)
                                            <img class="h-14 w-14 rounded-2xl object-cover ring-2 ring-gray-100" src="{{ asset('storage/' . $item->user->foto) }}" alt="">
                                        @else
                                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-orange-100 to-red-50 font-bold text-orange-600 flex items-center justify-center text-xl shadow-inner">
                                                {{ substr($item->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-1 -right-1 bg-white p-1 rounded-lg shadow-sm">
                                            @if($item->jenis == 'sakit')
                                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h.01a1 1 0 100-2H10zm3 0a1 1 0 000 2h.01a1 1 0 100-2H13zM7 13a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h.01a1 1 0 100-2H10zm3 0a1 1 0 000 2h.01a1 1 0 100-2H13z" clip-rule="evenodd"></path></svg>
                                            @else
                                                <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 leading-tight">{{ $item->nama_pegawai ?? $item->user->name }}</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</p>
                                    </div>
                                </div>

                                <!-- Detail -->
                                <div class="space-y-4">
                                    <div class="bg-gray-50 rounded-2xl p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Jenis</span>
                                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase {{ $item->jenis == 'sakit' ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600' }}">
                                                {{ $item->jenis }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 italic">"{{ $item->keterangan }}"</p>
                                    </div>

                                    @if($item->bukti_foto)
                                    <div class="relative group/photo cursor-pointer overflow-hidden rounded-2xl aspect-video bg-gray-100">
                                        <img class="w-full h-full object-cover transform group-hover/photo:scale-110 transition duration-500" src="{{ asset('storage/' . $item->bukti_foto) }}" alt="Bukti">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/photo:opacity-100 transition-opacity flex items-center justify-center">
                                            <a href="{{ asset('storage/' . $item->bukti_foto) }}" target="_blank" class="p-2 bg-white rounded-full shadow-lg">
                                                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="p-6 pt-0 flex space-x-3">
                                <form action="{{ route('owner.approval.update', $item->id) }}" method="POST" class="flex-1 form-reject-{{ $item->id }}">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="button" onclick="confirmAction('reject', {{ $item->id }})" class="w-full py-2.5 rounded-xl border-2 border-red-50 text-red-500 font-bold text-sm hover:bg-red-50 hover:border-red-100 transition-all duration-200">
                                        Tolak
                                    </button>
                                </form>
                                <form action="{{ route('owner.approval.update', $item->id) }}" method="POST" class="flex-1 form-approve-{{ $item->id }}">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="button" onclick="confirmAction('approve', {{ $item->id }})" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-red-600 text-white font-bold text-sm shadow-orange-200 shadow-lg hover:shadow-orange-400 transform hover:-translate-y-0.5 transition-all duration-200">
                                        Approve
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- SECTION: HISTORY -->
            <div class="pt-10" x-data="{ 
                modalOpen: false, 
                detail: { name: '', date: '', type: '', reason: '', photo: '', status: '', role: '' },
                openModal(data) {
                    this.detail = data;
                    this.modalOpen = true;
                }
            }">
                <div class="flex items-center space-x-2 mb-6">
                    <div class="h-8 w-1.5 bg-gray-300 rounded-full"></div>
                    <h3 class="text-xl font-bold text-gray-800 tracking-tight">Riwayat Pengajuan</h3>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Pegawai</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Tipe</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Alasan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @foreach($riwayat as $history)
                                <tr class="hover:bg-gray-50/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                @if($history->user && $history->user->foto)
                                                    <img class="h-10 w-10 rounded-xl object-cover shadow-sm ring-1 ring-gray-100" src="{{ asset('storage/' . $history->user->foto) }}" alt="">
                                                @else
                                                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center text-orange-700 font-bold text-sm shadow-sm">
                                                        {{ substr($history->nama_pegawai ?? ($history->user->name ?? 'User'), 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-extrabold text-gray-900 tracking-tight">{{ $history->nama_pegawai ?? ($history->user->name ?? 'User Dihapus') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($history->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs font-semibold px-2 py-1 rounded-lg {{ $history->jenis == 'sakit' ? 'text-red-500 bg-red-50' : 'text-blue-500 bg-blue-50' }}">
                                            {{ ucfirst($history->jenis) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-600 truncate max-w-[150px]">{{ $history->keterangan }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($history->status_approval == 'disetujui')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button @click="openModal({
                                            name: '{{ $history->nama_pegawai ?? ($history->user->name ?? 'User Dihapus') }}',
                                            date: '{{ \Carbon\Carbon::parse($history->tanggal)->format('d F Y') }}',
                                            type: '{{ ucfirst($history->jenis) }}',
                                            reason: '{{ addslashes($history->keterangan) }}',
                                            photo: '{{ $history->bukti_foto ? asset('storage/' . $history->bukti_foto) : '' }}',
                                            status: '{{ $history->status_approval }}',
                                            role: '{{ $history->user->role ?? 'N/A' }}'
                                        })" class="group relative p-2 text-gray-400 hover:text-orange-600 bg-transparent hover:bg-orange-50 rounded-xl transition-all duration-300">
                                            <div class="absolute inset-0 bg-orange-100 rounded-xl scale-0 group-hover:scale-100 transition-transform duration-300"></div>
                                            <svg class="relative z-10 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($riwayat->isEmpty())
                        <div class="py-8 text-center text-gray-400 text-sm">
                            Belum ada riwayat pengajuan.
                        </div>
                    @endif
                </div>

                <!-- SINGLE GLOBAL MODAL (MODERN DESIGN) -->
                <template x-teleport="body">
                    <div x-show="modalOpen" 
                         class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                         x-cloak>
                        
                        <!-- Backdrop -->
                        <div x-show="modalOpen"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="modalOpen = false"
                             class="absolute inset-0 bg-gray-900/60 backdrop-blur-md"></div>

                        <!-- Modal Card -->
                        <div x-show="modalOpen"
                             x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-500"
                             x-transition:enter-start="opacity-0 scale-90 translate-y-10"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-90"
                             class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-[0_20px_50px_rgba(234,88,12,0.15)] border border-white/40 overflow-hidden flex flex-col max-h-[90vh]">
                            
                            <!-- Header Gradient -->
                            <div class="relative bg-gradient-to-br from-orange-500 via-orange-600 to-red-600 p-8 pt-10 pb-16">
                                <!-- Background Patterns -->
                                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                                <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-yellow-400/20 rounded-full blur-2xl"></div>
                                
                                <div class="relative z-10 flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-widest border border-white/10" x-text="detail.role"></span>
                                            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-widest border border-white/10" x-text="detail.date"></span>
                                        </div>
                                        <h2 class="text-3xl font-extrabold text-white tracking-tight" x-text="detail.name"></h2>
                                    </div>
                                    <button @click="modalOpen = false" class="group p-2 rounded-full bg-white/10 hover:bg-white/20 transition-all border border-white/10">
                                        <svg class="w-6 h-6 text-white group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Content Body (Floating Up) -->
                            <div class="relative px-8 pb-8 -mt-8 flex-1 overflow-y-auto no-scrollbar">
                                <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 space-y-6">
                                    
                                    <!-- Status & Tipe Grid -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 text-center group hover:border-orange-200 transition-colors">
                                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Status</p>
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                                                 :class="detail.status == 'disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                                 <span x-text="detail.status == 'disetujui' ? 'Disetujui' : 'Ditolak'"></span>
                                            </div>
                                        </div>
                                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 text-center group hover:border-orange-200 transition-colors">
                                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Tipe</p>
                                            <span class="text-lg font-bold text-gray-800" x-text="detail.type"></span>
                                        </div>
                                    </div>

                                    <!-- Alasan -->
                                    <div>
                                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-3 pl-1">Alasan Pengajuan</p>
                                        <div class="p-5 rounded-2xl bg-gradient-to-br from-orange-50 to-white border border-orange-100 text-gray-700 italic leading-relaxed text-sm shadow-sm relative">
                                            <svg class="absolute top-4 left-4 w-4 h-4 text-orange-200" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 13.1216 16 12.017 16H9C9.00001 15 9.00001 14 9.00001 13C9.00001 10.0009 13.1136 9.81057 14.017 6L14.017 3C10.6698 3.55182 6 7.00084 6 13C6 16.9992 9.00001 17 9.00001 18L9 21H14.017ZM20.017 21L20.017 18C20.017 16.8954 19.1216 16 18.017 16H15C15 15 15 14 15 13C15 10.0009 19.1136 9.81057 20.017 6L20.017 3C16.6698 3.55182 12 7.00084 12 13C12 16.9992 15 17 15 18L15 21H20.017Z"/></svg>
                                            <span class="relative z-10 pl-6 block" x-text="detail.reason"></span>
                                        </div>
                                    </div>

                                    <!-- Foto Bukti -->
                                    <div x-show="detail.photo" class="pt-2">
                                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-3 pl-1">Bukti Lampiran</p>
                                        <div class="group relative rounded-3xl overflow-hidden shadow-lg border-2 border-white ring-1 ring-gray-100 cursor-pointer">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors z-10 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transform scale-50 group-hover:scale-100 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            </div>
                                            <img :src="detail.photo" class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-500">
                                            <a :href="detail.photo" target="_blank" class="absolute inset-0 z-20"></a>
                                        </div>
                                    </div>
                                    
                                    <div x-show="!detail.photo" class="py-4 text-center">
                                         <p class="text-xs text-gray-400 italic">Tidak ada bukti foto yang dilampirkan.</p>
                                    </div>

                                    <!-- Footer Action -->
                                    <div class="pt-2">
                                        <button @click="modalOpen = false" class="w-full py-4 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm tracking-wide transition-colors">
                                            Tutup Jendela
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
                        </table>
                    </div>
                    @if($riwayat->isEmpty())
                        <div class="py-8 text-center text-gray-400 text-sm">
                            Belum ada riwayat pengajuan.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function confirmAction(type, id) {
            let titleText = type === 'approve' ? 'Setujui Pengajuan?' : 'Tolak Pengajuan?';
            let descText = type === 'approve' 
                ? 'Pegawai akan tercatat izin/sakit di laporan absensi.' 
                : 'Pengajuan akan ditandai sebagai ditolak.';
            let confirmBtnColor = type === 'approve' ? '#f97316' : '#ef4444';
            let iconType = type === 'approve' ? 'question' : 'warning';

            Swal.fire({
                title: titleText,
                text: descText,
                icon: iconType,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#9ca3af',
                confirmButtonText: type === 'approve' ? 'Ya, Setujui' : 'Ya, Tolak',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-[2rem] shadow-2xl border border-gray-100',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-bold text-gray-600 bg-gray-100 hover:bg-gray-200'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form yang sesuai
                    document.querySelector(`.form-${type}-${id}`).submit();
                }
            });
        }
    </script>
</x-app-layout>