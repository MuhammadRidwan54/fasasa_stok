<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $laporans;

    public function __construct($laporans)
    {
        $this->laporans = $laporans;
    }

    public function collection()
    {
        return $this->laporans;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kode Tas',
            'Nama Tas',
            'Model',
            'Warna',
            'Jumlah Terjual',
            'Harga Satuan',
            'Total',
            'Platform',
            'Sisa Stok',
            'Keterangan'
        ];
    }

    public function map($laporan): array
    {
        $platformNames = [
            'shopee' => 'Shopee',
            'tiktok' => 'TikTok Shop',
            // 'offline' => 'Offline Store',
            'lainnya' => 'Lainnya'
        ];

        return [
            $laporan->id,
            $laporan->tanggal->format('d/m/Y'),
            $laporan->tas->kode_tas,
            $laporan->tas->nama_tas,
            $laporan->tas->model_tas,
            $laporan->tas->warna_tas,
            $laporan->jumlah_terjual,
            $laporan->tas->harga,
            $laporan->jumlah_terjual * $laporan->tas->harga,
            $platformNames[$laporan->platform] ?? $laporan->platform, // Menampilkan nama platform
            $laporan->sisa_stok,
            $laporan->keterangan ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Style the header row
            'A1:K1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FF3498DB']
                ],
                'font' => [
                    'color' => ['argb' => 'FFFFFFFF']
                ]
            ],
        ];
    }
}