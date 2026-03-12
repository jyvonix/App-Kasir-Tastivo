<x-app-layout>
    <!-- Background Gradient & Decor -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden bg-gray-50">
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-orange-600 to-orange-400 rounded-b-[3rem] shadow-xl"></div>
        <div class="absolute -top-[10%] -left-[10%] w-[40rem] h-[40rem] bg-orange-400 rounded-full blur-[100px] opacity-30 mix-blend-overlay"></div>
    </div>

    <div class="max-w-md mx-auto relative z-10 px-4 py-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-white tracking-tight drop-shadow-md">Tastivo Absensi</h1>
            <p class="text-orange-100 font-medium text-sm mt-1 opacity-90">Scan QR Code Pegawai</p>
        </div>

        <!-- MAIN CARD -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
            
            <!-- SCANNER AREA -->
            <div class="p-6">
                <div class="relative w-full aspect-square rounded-3xl overflow-hidden bg-black shadow-inner border-[6px] border-white ring-1 ring-gray-200 group">
                    <!-- Scan Animation Line -->
                    <div id="scan-line" class="hidden absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent z-10 shadow-[0_0_15px_rgba(249,115,22,0.8)] animate-scan"></div>
                    
                    <div id="reader" class="w-full h-full object-cover"></div>
                    
                    <!-- Overlay Start/Error -->
                    <div id="video-overlay" class="absolute inset-0 z-20 bg-gray-900/90 flex flex-col items-center justify-center p-8 text-center transition-all duration-500">
                        <div id="camera-loading" class="hidden mb-6">
                            <div class="animate-spin rounded-full h-14 w-14 border-4 border-orange-500 border-t-transparent shadow-lg"></div>
                        </div>
                        
                        <div id="start-prompt">
                            <div class="w-20 h-20 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                            <h2 class="text-white text-xl font-black mb-2">Siap Melakukan Scan?</h2>
                            <p class="text-gray-400 text-sm mb-8">Pastikan QR Code berada di dalam kotak scanner.</p>
                            <button onclick="startLiveCamera()" class="w-full py-4 bg-orange-500 text-white rounded-2xl font-black shadow-lg shadow-orange-500/30 hover:bg-orange-600 transition-all active:scale-95 flex items-center justify-center gap-3">
                                <span>Mulai Kamera</span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>

                        <!-- Error Message Container -->
                        <div id="error-prompt" class="hidden animate-fade-in">
                            <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-500/30">
                                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h2 class="text-white text-xl font-black mb-4">Kamera Terblokir</h2>
                            <div class="bg-gray-800 rounded-2xl p-4 mb-6 text-left">
                                <p id="error-text" class="text-gray-300 text-xs leading-relaxed font-medium"></p>
                            </div>
                            <div class="flex flex-col gap-3">
                                <button onclick="location.reload()" class="w-full py-3 bg-gray-700 text-white rounded-xl font-bold hover:bg-gray-600 transition">Coba Lagi</button>
                                <button onclick="toggleManual()" class="text-orange-400 text-xs font-black uppercase tracking-widest underline underline-offset-4">Gunakan Input Manual</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Input Section -->
            <div class="bg-white px-6 pb-6 pt-2 border-t border-gray-100">
                <div id="manual-form-container" class="hidden mb-6 animate-fade-in-up">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Kode Pegawai Manual</label>
                    <form id="manual-form" class="flex gap-2">
                        <input type="text" id="manual_code" class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl px-5 text-center font-bold text-gray-800 uppercase focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all py-3.5" placeholder="KODE-XXX">
                        <button type="submit" class="bg-gray-900 text-white px-6 rounded-2xl font-bold hover:bg-orange-600 transition-all active:scale-95 shadow-lg">OK</button>
                    </form>
                </div>

                <div class="flex items-center justify-between">
                    <button onclick="toggleManual()" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-orange-600 transition flex items-center gap-2 px-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Input Manual
                    </button>
                    <p id="scan-status" class="text-[10px] font-black text-gray-300 uppercase tracking-widest text-right">Menunggu Kamera...</p>
                </div>
            </div>
        </div>

        <!-- Camera Tip (Only shows when error) -->
        <div id="camera-tip" class="hidden mt-6 bg-orange-100 rounded-3xl p-5 border border-orange-200 shadow-sm animate-fade-in">
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">!</div>
                <div>
                    <h4 class="text-orange-900 font-black text-sm uppercase mb-1">Tips Mengaktifkan Kamera</h4>
                    <p class="text-orange-700 text-xs leading-relaxed">
                        Jika Anda menggunakan IP Address (misal: 192.168.x.x), Chrome memblokir kamera. Gunakan <b>localhost</b> atau aktifkan <b>HTTPS</b>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL HASIL -->
    <div id="result-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-8 text-center animate-bounce-in transform scale-95 opacity-0 transition-all duration-300" id="modal-content">
            <div id="modal-icon" class="text-7xl mb-6">👋</div>
            <h2 id="modal-title" class="text-3xl font-black text-gray-800 mb-2 tracking-tight">BERHASIL</h2>
            <div id="modal-message" class="text-gray-500 font-medium mb-8 leading-relaxed"></div>
            <button onclick="closeModal()" class="w-full py-4 bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-2xl font-black shadow-xl shadow-orange-500/30 hover:shadow-orange-500/50 transition-all active:scale-95 text-lg">LANJUTKAN</button>
        </div>
    </div>

    <style>
        .animate-scan { animation: scan 2s linear infinite; }
        @keyframes scan { 0% { top: 0%; } 100% { top: 100%; } }
        
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        
        .modal-active { display: flex !important; }
        .modal-show { transform: scale(1) !important; opacity: 1 !important; }
        
        #reader video { object-fit: cover !important; width: 100% !important; height: 100% !important; border-radius: 1.5rem; }
        #reader { background: #000; }
    </style>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const audioSuccess = new Audio('https://assets.mixkit.co/active_storage/sfx/2578/2578-preview.mp3');
        const audioError = new Audio('https://assets.mixkit.co/active_storage/sfx/950/950-preview.mp3');
        let isProcessing = false;

        async function startLiveCamera() {
            const startPrompt = document.getElementById('start-prompt');
            const errorPrompt = document.getElementById('error-prompt');
            const loading = document.getElementById('camera-loading');
            const overlay = document.getElementById('video-overlay');
            const scanLine = document.getElementById('scan-line');

            startPrompt.classList.add('hidden');
            errorPrompt.classList.add('hidden');
            loading.classList.remove('hidden');

            // Requirement check
            if (!window.isSecureContext && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                showCameraError("Browser memblokir kamera karena koneksi tidak aman (HTTP). Silakan gunakan HTTPS atau akses melalui localhost.");
                loading.classList.add('hidden');
                return;
            }

            try {
                const config = { 
                    fps: 25, 
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                    disableFlip: false 
                };
                
                await html5QrCode.start(
                    { facingMode: "environment" }, 
                    config, 
                    (decodedText) => {
                        if (!isProcessing) {
                            sendScan(decodedText);
                            // Visual feedback on scan
                            scanLine.classList.add('bg-green-500');
                            setTimeout(() => scanLine.classList.remove('bg-green-500'), 500);
                        }
                    }
                );

                // Success Start
                overlay.classList.add('opacity-0', 'pointer-events-none');
                scanLine.classList.remove('hidden');
                setStatus("Scanner Aktif", "text-green-500");
                
            } catch (err) {
                console.error("Camera Start Failed:", err);
                let msg = "Kamera tidak dapat diaktifkan. ";
                
                if (err.includes("NotAllowedError") || err.includes("Permission denied")) {
                    msg += "Izin kamera ditolak. Silakan aktifkan izin kamera di pengaturan browser Anda.";
                } else if (err.includes("NotFoundError")) {
                    msg += "Kamera tidak ditemukan di perangkat ini.";
                } else {
                    msg += "Pastikan tidak ada aplikasi lain yang menggunakan kamera.";
                }
                
                showCameraError(msg);
            } finally {
                loading.classList.add('hidden');
            }
        }

        function showCameraError(msg) {
            document.getElementById('error-text').innerText = msg;
            document.getElementById('error-prompt').classList.remove('hidden');
            document.getElementById('start-prompt').classList.add('hidden');
            document.getElementById('video-overlay').classList.remove('opacity-0', 'pointer-events-none');
            document.getElementById('camera-tip').classList.remove('hidden');
            setStatus("Kamera Error", "text-red-500");
        }

        function sendScan(code) {
            if (isProcessing) return;
            isProcessing = true;
            
            setStatus("Memproses QR...", "text-orange-500 font-black");

            $.ajax({
                url: "{{ route('attendance.process_scan') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", kode_pegawai: code },
                success: function(resp) {
                    audioSuccess.play();
                    showModal('success', resp);
                    setStatus("Berhasil: " + resp.nama, "text-green-600 font-black");
                    // Stop camera temporarily on success to show modal clearly
                    html5QrCode.pause();
                },
                error: function(xhr) {
                    audioError.play();
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : "Sistem Sibuk";
                    showModal('error', { message: msg });
                    setStatus("Gagal: " + msg, "text-red-600 font-black");
                    html5QrCode.pause();
                },
                complete: () => {
                    isProcessing = false;
                }
            });
        }

        function toggleManual() { 
            $('#manual-form-container').toggleClass('hidden');
            if(!$('#manual-form-container').hasClass('hidden')) {
                $('#manual_code').focus();
            }
        }
        
        $('#manual-form').on('submit', function(e) {
            e.preventDefault();
            const code = $('#manual_code').val().trim();
            if(code) {
                sendScan(code);
                $('#manual_code').val('');
            }
        });

        function setStatus(msg, colorClass) {
            const el = document.getElementById('scan-status');
            el.innerText = msg;
            el.className = "text-[10px] font-black uppercase tracking-widest transition-all " + colorClass;
        }

        function showModal(type, data) {
            const modal = document.getElementById('result-modal');
            const content = document.getElementById('modal-content');
            modal.classList.add('modal-active');
            
            setTimeout(() => {
                content.classList.add('modal-show');
                content.classList.remove('scale-95', 'opacity-0');
            }, 50);

            if (type === 'success') {
                document.getElementById('modal-icon').innerText = data.mode === 'in' ? "👋" : "🏠";
                document.getElementById('modal-title').innerText = "BERHASIL";
                document.getElementById('modal-title').className = "text-4xl font-black text-green-600 mb-2";
                document.getElementById('modal-message').innerHTML = `Halo <b>${data.nama}</b><br>${data.message}<br><span class="text-sm text-gray-400 mt-4 block font-bold">${data.time} | ${data.detail || ''}</span>`;
            } else {
                document.getElementById('modal-icon').innerText = "⚠️";
                document.getElementById('modal-title').innerText = "GAGAL";
                document.getElementById('modal-title').className = "text-4xl font-black text-red-600 mb-2";
                document.getElementById('modal-message').innerHTML = `<p class="font-bold text-gray-700 text-lg">${data.message}</p>`;
            }
        }

        function closeModal() {
            const modal = document.getElementById('result-modal');
            const content = document.getElementById('modal-content');
            
            content.classList.remove('modal-show');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.remove('modal-active');
                html5QrCode.resume();
            }, 300);
        }
    </script>
</x-app-layout>