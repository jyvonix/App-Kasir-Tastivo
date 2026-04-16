<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Pegawai - Tastivo</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #FF6B35;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #FF6B35;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            color: #555;
            margin-top: 10px;
        }
        .meta-info {
            color: #888;
            font-size: 10px;
        }
        .summary-container {
            margin-bottom: 25px;
            width: 100%;
        }
        .summary-box {
            float: left;
            width: 23%;
            margin-right: 2%;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #eee;
            text-align: center;
        }
        .summary-box:last-child {
            margin-right: 0;
        }
        .summary-label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background-color: #f2f2f2;
            color: #444;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 10px;
            border-bottom: 2px solid #ddd;
            text-align: left;
        }
        .table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        .table tr:nth-child(even) {
            background-color: #fafafa;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-hadir { background: #dcfce7; color: #166534; }
        .status-telat { background: #fee2e2; color: #991b1b; }
        .status-izin { background: #dbeafe; color: #1e40af; }
        .status-sakit { background: #f3e8ff; color: #6b21a8; }
        
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .signature-section {
            float: right;
            width: 200px;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-weight: bold;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%;">
                    <div class="company-name">{{ $settings->nama_toko ?? 'TASTIVO MANAGER' }}</div>
                    <div class="meta-info">
                        {{ $settings->alamat_toko ?? 'Sistem Laporan Absensi Terpusat' }}<br>
                        Email: {{ auth()->user()->email }} | Telp: {{ $settings->no_hp ?? '-' }}
                    </div>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: top;">
                    <div class="report-title">LAPORAN REKAP ABSENSI</div>
                    <div class="meta-info">
                        Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}<br>
                        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="summary-container clearfix">
        <div class="summary-box">
            <div class="summary-label">Total Kehadiran</div>
            <div class="summary-value">{{ $stats['total'] }}</div>
        </div>
        <div class="summary-box" style="border-top: 3px solid #22c55e;">
            <div class="summary-label">Tepat Waktu</div>
            <div class="summary-value">{{ $stats['hadir'] }}</div>
        </div>
        <div class="summary-box" style="border-top: 3px solid #ef4444;">
            <div class="summary-label">Terlambat</div>
            <div class="summary-value">{{ $stats['telat'] }}</div>
        </div>
        <div class="summary-box" style="border-top: 3px solid #f97316;">
            <div class="summary-label">Izin / Sakit</div>
            <div class="summary-value">{{ $stats['izin'] }}</div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">Pegawai</th>
                <th style="width: 10%;">Shift</th>
                <th style="width: 10%;">Masuk</th>
                <th style="width: 10%;">Pulang</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>
                        <strong>{{ $log->user->nama }}</strong><br>
                        <span style="color: #888; font-size: 8px;">{{ strtoupper($log->user->jabatan ?? $log->user->role) }}</span>
                    </td>
                    <td>{{ $log->shift->nama_shift ?? '-' }}</td>
                    <td>{{ $log->waktu_masuk ? \Carbon\Carbon::parse($log->waktu_masuk)->format('H:i') : '--:--' }}</td>
                    <td>{{ $log->waktu_pulang ? \Carbon\Carbon::parse($log->waktu_pulang)->format('H:i') : '--:--' }}</td>
                    <td>
                        <span class="status-badge status-{{ $log->status }}">
                            {{ $log->status == 'hadir' ? 'TEPAT' : $log->status }}
                        </span>
                    </td>
                    <td>{{ $log->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #999;">Tidak ada data absensi ditemukan untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table style="width: 100%;">
            <tr>
                <td style="width: 70%; color: #999; font-style: italic; vertical-align: bottom;">
                    * Dokumen ini dihasilkan secara otomatis oleh Sistem Tastivo Manager.
                </td>
                <td style="width: 30%;">
                    <div class="signature-section">
                        <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <div class="signature-line">
                            {{ auth()->user()->nama }}<br>
                            <span style="font-size: 9px; color: #888;">Owner / Administrator</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>