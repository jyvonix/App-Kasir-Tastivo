<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Tastivo</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 2px solid #FF4500; }
        .header h1 { color: #FF4500; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0; color: #666; font-size: 12px; }
        
        .summary-container { width: 100%; margin-bottom: 25px; }
        .summary-card { width: 31%; display: inline-block; padding: 12px; background: #f9f9f9; border-left: 4px solid #FF4500; margin-right: 1.5%; border-radius: 4px; vertical-align: top; }
        .summary-card:last-child { margin-right: 0; }
        .summary-card label { display: block; font-size: 9px; text-transform: uppercase; color: #777; margin-bottom: 5px; font-weight: bold; }
        .summary-card span { font-size: 14px; font-weight: bold; color: #333; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #FF4500; color: white; text-align: left; padding: 10px 8px; text-transform: uppercase; font-size: 10px; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background-color: #fafafa; }
        
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #777; }
        .footer-line { border-top: 1px solid #eee; margin-top: 50px; padding-top: 10px; }

        .badge { padding: 3px 7px; border-radius: 10px; font-size: 8px; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TASTIVO</h1>
        <p>Laporan Pendapatan Keuangan Bisnis</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
    </div>

    <div class="summary-container">
        <div class="summary-card">
            <label>Total Pendapatan</label>
            <span>Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}</span>
        </div>
        <div class="summary-card">
            <label>Jumlah Transaksi</label>
            <span>{{ $totalTransaksi }} Trx</span>
        </div>
        <div class="summary-card">
            <label>Rata-rata Transaksi</label>
            <span>Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">#</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Kode Trx</th>
                <th>Pelanggan</th>
                <th width="10%">Metode</th>
                <th width="10%">Status</th>
                <th width="15%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayatTransaksi as $index => $trx)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $trx->tanggal->format('d/m/Y H:i') }}</td>
                <td>{{ $trx->kode_transaksi }}</td>
                <td>{{ $trx->nama_pelanggan ?? '-' }}</td>
                <td>{{ $trx->metode_pembayaran }}</td>
                <td><span class="badge badge-success">{{ $trx->status_pembayaran }}</span></td>
                <td class="text-right">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f1f1f1; font-weight: bold;">
                <td colspan="6" class="text-right">GRAND TOTAL</td>
                <td class="text-right">Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
        <div class="footer-line">
            <p>Manajer Operasional Tastivo</p>
            <br><br><br>
            <p>( ........................................ )</p>
        </div>
    </div>
</body>
</html>
