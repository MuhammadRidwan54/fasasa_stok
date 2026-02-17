<?php

namespace App\Exports;

use App\Models\Laporan;
use Illuminate\Http\Request;  // ✅ DITAMBAHKAN
use Maatwebsite\Excel\Facades\Excel;  // ✅ DITAMBAHKAN
use Barryvdh\DomPDF\Facade\Pdf;  // ✅ DITAMBAHKAN
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// ✅ DITAMBAHKAN - Import untuk kelas advanced export
use App\Exports\LaporanAdvancedExport;
use App\Exports\LaporanMultiSheetExport;

class LaporanExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    public function collection()
    {
        // Ambil filter dari session
        $query = Laporan::with('tas');
        
        if (session('export_dari_tanggal')) {
            $query->whereDate('tanggal', '>=', session('export_dari_tanggal'));
        }
        
        if (session('export_ke_tanggal')) {
            $query->whereDate('tanggal', '<=', session('export_ke_tanggal'));
        }
        
        if (session('export_platform')) {
            $query->where('platform', session('export_platform'));
        }
        
        // Tambahkan periode filter jika ada
        if (session('export_periode')) {
            $periode = session('export_periode');
            $now = now();
            
            switch ($periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal', $now->toDateString());
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', $now->subDay()->toDateString());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'bulan_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfMonth()->toDateString(),
                        $now->endOfMonth()->toDateString()
                    ]);
                    break;
                case 'tahun_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfYear()->toDateString(),
                        $now->endOfYear()->toDateString()
                    ]);
                    break;
            }
        }
        
        $laporans = $query->orderBy('tanggal', 'desc')->get();
        
        // Format data untuk Excel
        return $laporans->map(function($item, $index) {
            $platformNames = [
                'shopee' => 'Shopee',
                'tiktok' => 'TikTok',
                'offline' => 'Offline',
                'lainnya' => 'Lainnya'
            ];
            
            return [
                'No' => $index + 1,
                'Tanggal' => $item->tanggal->format('d/m/Y'),
                'Kode Tas' => $item->tas->kode_tas,
                'Nama Tas' => $item->tas->nama_tas,
                'Warna' => $item->tas->warna_tas,
                'Terjual (pcs)' => $item->jumlah_terjual,
                'Platform' => $platformNames[$item->platform] ?? $item->platform,
                'Harga Satuan' => $item->tas->harga,
                'Total' => $item->jumlah_terjual * $item->tas->harga,
                'Sisa Stok' => $item->sisa_stok,
                'Status Stok' => $item->sisa_stok > 10 ? 'Aman' : ($item->sisa_stok > 0 ? 'Menipis' : 'Kritis'),
                'Keterangan' => $item->keterangan ?? '-'
            ];
        });
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kode Tas',
            'Nama Tas',
            'Warna',
            'Terjual (pcs)',
            'Platform',
            'Harga Satuan',
            'Total',
            'Sisa Stok',
            'Status Stok',
            'Keterangan'
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Styling untuk header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3498DB']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
        
        // Auto size kolom
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Format angka untuk kolom harga dan total
        $lastRow = $sheet->getHighestRow();
        
        // Format kolom Harga Satuan dan Total sebagai currency
        $sheet->getStyle('H2:H' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('I2:I' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        
        // Alternating row colors
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA']
                    ]
                ]);
            }
        }
        
        // Total row
        $totalRow = $lastRow + 2;
        $sheet->setCellValue('E' . $totalRow, 'TOTAL');
        $sheet->setCellValue('F' . $totalRow, '=SUM(F2:F' . $lastRow . ')');
        $sheet->setCellValue('I' . $totalRow, '=SUM(I2:I' . $lastRow . ')');
        
        $sheet->getStyle('E' . $totalRow . ':I' . $totalRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50']
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
            ]
        ]);
        
        // Header laporan
        $sheet->insertNewRowBefore(1, 4);
        
        // Title
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN TAS FASASA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        // Periode
        $periode = session('export_periode') ? ucfirst(str_replace('_', ' ', session('export_periode'))) : 'Semua Periode';
        $sheet->mergeCells('A2:L2');
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        
        // Tanggal cetak
        $sheet->mergeCells('A3:L3');
        $sheet->setCellValue('A3', 'Tanggal Cetak: ' . date('d/m/Y H:i'));
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
        
        // Info ringkasan
        $totalTerjual = '=SUM(F6:F' . ($lastRow + 2) . ')';
        $totalPendapatan = '=SUM(I6:I' . ($lastRow + 2) . ')';
        
        $sheet->mergeCells('A4:L4');
        $sheet->setCellValue('A4', 'Ringkasan: Total Transaksi: ' . ($lastRow - 5) . ' | Total Terjual: ' . $totalTerjual . ' pcs | Total Pendapatan: Rp ' . $totalPendapatan);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F8F9FA');
        
        // Adjust column widths after adding header
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 8,  // No
            'B' => 12, // Tanggal
            'C' => 15, // Kode Tas
            'D' => 25, // Nama Tas
            'E' => 15, // Warna
            'F' => 12, // Terjual
            'G' => 15, // Platform
            'H' => 15, // Harga Satuan
            'I' => 15, // Total
            'J' => 12, // Sisa Stok
            'K' => 12, // Status Stok
            'L' => 20, // Keterangan
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Freeze header row
                $event->sheet->freezePane('A6');
                
                // Set print settings
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }


    public function exportPDF(Request $request)
    {
        // Query laporan dengan filter
        $query = $this->getFilteredLaporan($request);
        $laporans = $query->get();
        
        // Hitung total
        $totalTerjual = $laporans->sum('jumlah_terjual');
        $totalPendapatan = $laporans->sum(function($item) {
            return $item->jumlah_terjual * $item->tas->harga;
        });
        
        $data = [
            'laporans' => $laporans,
            'totalTerjual' => $totalTerjual,
            'totalPendapatan' => $totalPendapatan,
            'periode' => $request->periode ?? null,
        ];
        
        // Generate PDF
        $pdf = Pdf::loadView('laporan.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Export laporan ke Excel (basic)
     */
    public function exportExcel(Request $request)
    {
        // Simpan filter ke session
        $this->saveExportFilters($request);
        
        $filename = 'laporan-penjualan-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new LaporanExport(), $filename);
    }
    
    /**
     * Export laporan ke Excel (advanced dengan charts)
     */
    public function exportExcelAdvanced(Request $request)
    {
        // Simpan filter ke session
        $this->saveExportFilters($request);
        
        $filename = 'laporan-penjualan-advanced-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new LaporanAdvancedExport(), $filename);
    }
    
    /**
     * Export laporan ke Excel (multi-sheet)
     */
    public function exportExcelMultiSheet(Request $request)
    {
        // Simpan filter ke session
        $this->saveExportFilters($request);
        
        $filename = 'laporan-penjualan-detail-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new LaporanMultiSheetExport(), $filename);
    }
    
    /**
     * Export laporan ke CSV
     */
    public function exportCSV(Request $request)
    {
        // Query laporan dengan filter
        $query = $this->getFilteredLaporan($request);
        $laporans = $query->get();
        
        $filename = 'laporan-penjualan-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($laporans) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No', 'Tanggal', 'Kode Tas', 'Nama Tas', 'Warna', 
                'Terjual (pcs)', 'Platform', 'Harga Satuan', 'Total', 
                'Sisa Stok', 'Status Stok', 'Keterangan'
            ]);
            
            // Data
            foreach ($laporans as $index => $laporan) {
                $platformNames = [
                    'shopee' => 'Shopee',
                    'tiktok' => 'TikTok',
                    'offline' => 'Offline',
                    'lainnya' => 'Lainnya'
                ];
                
                $statusStok = $laporan->sisa_stok > 10 ? 'Aman' : 
                             ($laporan->sisa_stok > 0 ? 'Menipis' : 'Kritis');
                
                fputcsv($file, [
                    $index + 1,
                    $laporan->tanggal->format('d/m/Y'),
                    $laporan->tas->kode_tas,
                    $laporan->tas->nama_tas,
                    $laporan->tas->warna_tas,
                    $laporan->jumlah_terjual,
                    $platformNames[$laporan->platform] ?? $laporan->platform,
                    $laporan->tas->harga,
                    $laporan->jumlah_terjual * $laporan->tas->harga,
                    $laporan->sisa_stok,
                    $statusStok,
                    $laporan->keterangan ?? '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Helper method untuk query laporan dengan filter
     */
    private function getFilteredLaporan(Request $request)
    {
        $query = Laporan::with('tas');
        
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        
        if ($request->filled('ke_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->ke_tanggal);
        }
        
        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }
        
        if ($request->filled('periode')) {
            $periode = $request->periode;
            $now = now();
            
            switch ($periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal', $now->toDateString());
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', $now->subDay()->toDateString());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'bulan_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfMonth()->toDateString(),
                        $now->endOfMonth()->toDateString()
                    ]);
                    break;
                case 'tahun_ini':
                    $query->whereBetween('tanggal', [
                        $now->startOfYear()->toDateString(),
                        $now->endOfYear()->toDateString()
                    ]);
                    break;
            }
        }
        
        return $query->orderBy('tanggal', 'desc');
    }
    
    /**
     * Helper method untuk menyimpan filter ke session
     */
    private function saveExportFilters(Request $request)
    {
        session([
            'export_dari_tanggal' => $request->dari_tanggal,
            'export_ke_tanggal' => $request->ke_tanggal,
            'export_platform' => $request->platform,
            'export_periode' => $request->periode,
        ]);
    }
}