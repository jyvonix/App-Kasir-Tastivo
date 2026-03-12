<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card {{ $user->nama }} - Tastivo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            @page { size: portrait; margin: 0; }
            body { background: white; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .no-print { display: none !important; }
            .card-container { margin: 20px auto; box-shadow: none; border: 1px solid #eee; page-break-inside: avoid; }
        }
        .bg-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6">

    <!-- Tombol Navigasi -->
    <div class="no-print flex gap-3 mb-8">
        <a href="{{ route('pegawai.index') }}" class="px-5 py-2.5 bg-white text-gray-700 rounded-full font-bold shadow-sm hover:bg-gray-50 border border-gray-200 transition">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="px-6 py-2.5 bg-gray-900 text-white rounded-full font-bold shadow-lg hover:bg-black transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Kartu
        </button>
    </div>

    <!-- ID CARD VERTIKAL -->
    <div class="card-container relative w-[350px] h-[550px] bg-white rounded-[2.5rem] overflow-hidden shadow-2xl flex flex-col border border-white">
        
        <!-- BAGIAN ATAS: HEADER & FOTO (Gradasi Oranye) -->
        <div class="relative h-3/5 bg-gradient-to-br from-orange-500 via-orange-600 to-red-600 p-8 flex flex-col items-center text-white overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 bg-pattern opacity-20"></div>
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            
            <!-- Logo -->
            <div class="relative z-10 font-black tracking-[0.3em] text-sm mb-8 opacity-90">TASTIVO</div>

            <!-- Foto Profile -->
            <div class="relative z-10 group">
                <div class="w-36 h-36 rounded-full p-1.5 bg-white/20 backdrop-blur-md shadow-2xl relative overflow-hidden">
                    <img src="{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=fff&color=ea580c&size=300' }}" 
                         class="w-full h-full rounded-full object-cover border-4 border-white bg-white">
                </div>
                <!-- Verified Badge -->
                <div class="absolute bottom-1 right-1 w-10 h-10 bg-blue-500 border-4 border-white rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                </div>
            </div>

            <!-- Nama & Jabatan -->
            <div class="mt-6 text-center z-10">
                <h1 class="text-2xl font-black uppercase tracking-tight leading-tight">{{ $user->nama }}</h1>
                <div class="inline-block mt-2 px-4 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/20">
                    <span class="text-[10px] font-bold uppercase tracking-widest">{{ $user->jabatan }}</span>
                </div>
            </div>
        </div>

        <!-- BAGIAN BAWAH: DATA & QR (Putih Bersih) -->
        <div class="relative h-2/5 bg-white p-8 flex flex-col items-center justify-between">
            <!-- Wave Divider (Hiasan) -->
            <div class="absolute -top-6 left-0 w-full h-8 bg-white rounded-t-[3rem]"></div>

            <div class="text-center w-full">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mb-1">Employee Identity</p>
                <p class="text-lg font-mono font-black text-gray-800 tracking-widest">{{ $user->kode_pegawai }}</p>
            </div>

            <!-- QR Code Section -->
            <div class="flex flex-col items-center mb-2">
                <div id="qrcode" class="p-3 bg-white border-2 border-orange-50 rounded-2xl shadow-sm"></div>
                <p class="mt-3 text-[9px] font-black text-orange-500 uppercase tracking-widest animate-pulse">Scan for Attendance</p>
            </div>

            <!-- Footer Line -->
            <div class="w-12 h-1.5 bg-gray-100 rounded-full"></div>
        </div>

    </div>

    <!-- Script Generate QR -->
    <script type="text/javascript">
        @if($user->kode_pegawai)
        window.onload = function() {
            new QRCode(document.getElementById("qrcode"), {
                text: "{{ $user->kode_pegawai }}",
                width: 100,
                height: 100,
                colorDark : "#111827",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        };
        @endif
    </script>
</body>
</html>