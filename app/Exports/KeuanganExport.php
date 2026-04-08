<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class KeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $validStatus = ['Lunas', 'success', 'paid', 'settlement'];

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate)->startOfDay();
        $this->endDate = Carbon::parse($endDate)->endOfDay();
    }

    public function collection()
    {
        return Transaksi::with('user')
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->whereIn('status_pembayaran', $this->validStatus)
            ->latest('tanggal')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['LAPORAN KEUANGAN TASTIVO'],
            ['Periode: ' . $this->startDate->format('d M Y') . ' - ' . $this->endDate->format('d M Y')],
            [''],
            [
                'No',
                'Tanggal',
                'Kode Transaksi',
                'Pelanggan',
                'Metode',
                'Status',
                'Kasir',
                'Total Harga',
                'Diskon',
                'Pajak',
                'Total Bayar'
            ]
        ];
    }

    public function map($transaksi): array
    {
        static $no = 1;
        return [
            $no++,
            $transaksi->tanggal->format('d/m/Y H:i'),
            $transaksi->kode_transaksi,
            $transaksi->nama_pelanggan ?? '-',
            $transaksi->metode_pembayaran,
            $transaksi->status_pembayaran,
            $transaksi->user->name ?? '-',
            $transaksi->total_harga,
            $transaksi->diskon ?? 0,
            $transaksi->pajak ?? 0,
            $transaksi->total_harga - ($transaksi->diskon ?? 0) + ($transaksi->pajak ?? 0)
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');

        return [
            1    => ['font' => ['bold' => true, 'size' => 16], 'alignment' => ['horizontal' => 'center']],
            2    => ['font' => ['italic' => true], 'alignment' => ['horizontal' => 'center']],
            4    => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FF4500'] // Lava Orange
                ]
            ],
        ];
    }
}
