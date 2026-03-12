<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Code Absensi - TASTIVO</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f3f4f6; }
        
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .print-area { 
                position: absolute; 
                top: 50%; left: 50%; 
                transform: translate(-50%, -50%); 
                width: 100%; text-align: center; 
            }
            .shadow-xl { shadow: none !important; box-shadow: none !important; }
            .border { border: 2px solid black !important; }
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-4">

    <!-- Tombol Navigasi (Tidak ikut diprint) -->
    <div class="fixed top-6 left-6 no-print z-50">
        <a href="{{ route('attendance.monitoring') }}" class="flex items-center gap-2 px-5 py-3 bg-white text-gray-700 rounded-full font-bold shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all border border-gray-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="fixed top-6 right-6 no-print z-50">
        <button onclick="window.print()" class="flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-full font-black shadow-lg hover:shadow-xl hover:bg-black hover:-translate-y-1 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            CETAK POSTER
        </button>
    </div>

    <!-- POSTER AREA (Akan dicetak) -->
    <div class="print-area relative bg-white w-full max-w-[500px] p-10 rounded-[3rem] shadow-2xl border-8 border-gray-900 text-center flex flex-col items-center">
        
        <!-- Logo / Header -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-[#FF6B35] rounded-3xl text-white shadow-lg mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 10h16M4 14h16M6 6a6 6 0 0112 0v4H6V6zM6 18h12"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none">TASTIVO</h1>
            <p class="text-sm font-bold text-gray-400 tracking-[0.4em] uppercase mt-2">POINT OF SALE</p>
        </div>

        <div class="w-full h-0.5 bg-gray-100 mb-8"></div>

        <!-- Instruksi -->
        <h2 class="text-2xl font-black text-gray-800 mb-2">ABSENSI PEGAWAI</h2>
        <p class="text-gray-500 font-medium mb-8">Scan QR Code di bawah ini untuk Masuk/Pulang</p>

        <!-- Container QR -->
        <div class="p-6 bg-white border-4 border-dashed border-gray-300 rounded-3xl mb-8 relative">
             <div class="absolute -top-3 -right-3 w-8 h-8 bg-gray-200 rounded-full border-4 border-white"></div>
             <div class="absolute -top-3 -left-3 w-8 h-8 bg-gray-200 rounded-full border-4 border-white"></div>
             <div class="absolute -bottom-3 -right-3 w-8 h-8 bg-gray-200 rounded-full border-4 border-white"></div>
             <div class="absolute -bottom-3 -left-3 w-8 h-8 bg-gray-200 rounded-full border-4 border-white"></div>
             
             <div id="qrcode" class="mix-blend-multiply"></div>
        </div>

        <!-- Tanggal -->
        <div class="px-6 py-3 bg-gray-900 text-white rounded-full font-bold text-sm tracking-wider uppercase mb-8">
            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </div>

        <p class="text-xs font-bold text-gray-300 uppercase tracking-widest">
            CODE VALID FOR TODAY ONLY
        </p>

    </div>

    <!-- Script QR Generator -->
    <script>
        var token = "{{ $token }}"; 
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: token,
            width: 250,
            height: 250,
            colorDark : "#111827", // Gray-900
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>

</body>
</html>