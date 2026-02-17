<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Contoh data template
        return [
            [
                '2024-01-15', // tanggal
                'TAS001',     // kode_tas
                'Shopee',     // platform
                5,            // jumlah_terjual
                45,           // sisa_stok
                'Penjualan normal' // keterangan
            ],
            [
                '2024-01-15',
                'TAS002',
                'TikTok',
                3,
                22,
                'Promo tiktok'
            ],
        ];
    }
    
    public function headings(): array
    {
        return [
            'tanggal (YYYY-MM-DD)',
            'kode_tas',
            'platform (shopee/tiktok/offline/lainnya)',
            'jumlah_terjual',
            'sisa_stok',
            'keterangan'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Highlight header
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 
                      'startColor' => ['rgb' => '3498DB']]
        ]);
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(25);
        
        // Add instructions
        $sheet->insertNewRowBefore(1, 4);
        
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT LAPORAN PENJUALAN');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $sheet->setCellValue('A2', 'Petunjuk:');
        $sheet->setCellValue('A3', '1. Isi data sesuai format di bawah');
        $sheet->setCellValue('A4', '2. Jangan ubah nama kolom header');
        
        return $sheet;
    }
}