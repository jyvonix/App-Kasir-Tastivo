<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $pegawai->nama }}</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .id-card {
            width: 350px;
            height: 550px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            text-align: center;
        }
        .id-card-header {
            background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%);
            height: 140px;
            position: relative;
        }
        .id-card-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            object-fit: cover;
            position: absolute;
            left: 50%;
            bottom: -60px;
            transform: translateX(-50%);
            background-color: #f9fafb;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .id-card-body {
            padding-top: 70px;
            padding-bottom: 20px;
            padding-left: 20px;
            padding-right: 20px;
        }
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
            .id-card { box-shadow: none; border: 1px solid #ddd; }
        }
    </style>
</head>
<body>

    <div class="flex flex-col items-center gap-6">
        <div class="id-card" id="printableArea">
            <div class="id-card-header">
                <div class="absolute top-4 left-0 w-full text-center">
                    <h1 class="text-white text-2xl font-black tracking-widest uppercase">TASTIVO</h1>
                    <p class="text-orange-100 text-xs font-medium tracking-[0.2em] uppercase">Employee Card</p>
                </div>
                <img src="{{ $pegawai->foto_url }}" alt="Foto Pegawai" class="id-card-avatar">
            </div>
            
            <div class="id-card-body">
                <h2 class="text-2xl font-bold text-gray-800 leading-tight">{{ $pegawai->nama }}</h2>
                <p class="text-sm font-semibold text-orange-500 uppercase tracking-widest mt-1 mb-6">{{ $pegawai->role }}</p>

                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 inline-block mb-4">
                    <canvas id="qrcode"></canvas>
                </div>
                
                <p class="text-xs text-gray-400 font-mono">{{ $pegawai->username }}</p>
            </div>

            <div class="absolute bottom-0 w-full py-3 bg-gray-900 text-white text-[10px] font-bold tracking-widest uppercase">
                Staff Official Identity
            </div>
        </div>

        <button onclick="window.print()" class="no-print px-6 py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-black transition-all shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Kartu
        </button>
    </div>

    <script>
        // Generate QR Code containing the User ID
        // Format: USER-ID-{id}
        const userId = "USER-ID-{{ $pegawai->id }}";
        
        QRCode.toCanvas(document.getElementById('qrcode'), userId, {
            width: 150,
            margin: 1,
            color: {
                dark: '#1f2937', // Gray-800
                light: '#f9fafb'  // Gray-50
            }
        }, function (error) {
            if (error) console.error(error)
            console.log('QR Code generated!')
        })
    </script>
</body>
</html>
