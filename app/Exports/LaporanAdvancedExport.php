<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Layout;

class LaporanAdvancedExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents, WithCharts
{
    private $data;
    private $summaryData;
    
    public function __construct()
    {
        $this->prepareData();
    }
    
    private function prepareData()
    {
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
        
        $this->data = $query->orderBy('tanggal', 'desc')->get();
        $this->calculateSummary();
    }
    
    private function calculateSummary()
    {
        if (!$this->data) {
            $this->summaryData = [
                'platform' => [],
                'monthly' => [],
                'products' => []
            ];
            return;
        }
        
        $platforms = ['shopee', 'tiktok', 'offline', 'lainnya'];
        foreach ($platforms as $platform) {
            $platformData = $this->data->where('platform', $platform);
            $this->summaryData['platform'][$platform] = [
                'count' => $platformData->count(),
                'total_sold' => $platformData->sum('jumlah_terjual'),
                'total_revenue' => $platformData->sum(function($item) {
                    return $item->jumlah_terjual * $item->tas->harga;
                })
            ];
        }
        
        $monthlyData = [];
        foreach ($this->data as $item) {
            $month = $item->tanggal->format('Y-m');
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = [
                    'month' => $item->tanggal->format('M Y'),
                    'total_sold' => 0,
                    'total_revenue' => 0
                ];
            }
            $monthlyData[$month]['total_sold'] += $item->jumlah_terjual;
            $monthlyData[$month]['total_revenue'] += ($item->jumlah_terjual * $item->tas->harga);
        }
        $this->summaryData['monthly'] = array_values($monthlyData);
        
        $productData = [];
        foreach ($this->data as $item) {
            $productId = $item->tas_id;
            if (!isset($productData[$productId])) {
                $productData[$productId] = [
                    'name' => $item->tas->nama_tas,
                    'code' => $item->tas->kode_tas,
                    'total_sold' => 0
                ];
            }
            $productData[$productId]['total_sold'] += $item->jumlah_terjual;
        }
        
        usort($productData, function($a, $b) {
            return $b['total_sold'] <=> $a['total_sold'];
        });
        
        $this->summaryData['products'] = array_slice($productData, 0, 10);
    }
    
    public function collection()
    {
        return $this->data->map(function($item, $index) {
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
        
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 1) {
            $sheet->getStyle('H2:H' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('I2:I' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        }
        
        $sheet->insertNewRowBefore(1, 3);
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN TAS FASASA - ADVANCED ANALYTICS');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        $sheet->mergeCells('A2:L2');
        $sheet->setCellValue('A2', 'Tanggal Cetak: ' . date('d/m/Y H:i:s'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        
        $sheet->setTitle('Data Transaksi');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        
        return [];
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 8, 'B' => 12, 'C' => 15, 'D' => 25, 'E' => 15,
            'F' => 12, 'G' => 15, 'H' => 15, 'I' => 15, 'J' => 12,
            'K' => 12, 'L' => 20
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $workbook = $event->sheet->getDelegate()->getParent();
                
                $dashboardSheet = $workbook->createSheet();
                $dashboardSheet->setTitle('Dashboard Analitik');
                
                $this->addDashboardContent($dashboardSheet);
                
                $workbook->setActiveSheetIndex(1);
            },
        ];
    }
    
    private function addDashboardContent($sheet)
    {
        // Header
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'DASHBOARD ANALITIK PENJUALAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        
        $periode = session('export_periode') ? ucfirst(str_replace('_', ' ', session('export_periode'))) : 'Semua Periode';
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        
        // Cards
        $totalTransaksi = $this->data->count();
        $totalTerjual = $this->data->sum('jumlah_terjual');
        $totalPendapatan = $this->data->sum(function($item) {
            return $item->jumlah_terjual * $item->tas->harga;
        });
        $average = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;
        
        // Card 1
        $sheet->mergeCells('A4:B5');
        $sheet->setCellValue('A4', 'TOTAL TRANSAKSI');
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle('A4:B5')->applyFromArray([
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        
        $sheet->mergeCells('A6:B7');
        $sheet->setCellValue('A6', $totalTransaksi);
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal('center')->setVertical('center');
        
        // Card 2
        $sheet->mergeCells('C4:D5');
        $sheet->setCellValue('C4', 'TOTAL TERJUAL');
        $sheet->getStyle('C4')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('C4')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle('C4:D5')->applyFromArray([
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E9']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        
        $sheet->mergeCells('C6:D7');
        $sheet->setCellValue('C6', number_format($totalTerjual) . ' pcs');
        $sheet->getStyle('C6')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('C6')->getAlignment()->setHorizontal('center')->setVertical('center');
        
        // Card 3
        $sheet->mergeCells('E4:F5');
        $sheet->setCellValue('E4', 'TOTAL PENDAPATAN');
        $sheet->getStyle('E4')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('E4')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle('E4:F5')->applyFromArray([
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF3E0']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        
        $sheet->mergeCells('E6:F7');
        $sheet->setCellValue('E6', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'));
        $sheet->getStyle('E6')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('E6')->getAlignment()->setHorizontal('center')->setVertical('center');
        
        // Card 4
        $sheet->mergeCells('G4:H5');
        $sheet->setCellValue('G4', 'RATA-RATA/TRANSAKSI');
        $sheet->getStyle('G4')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('G4')->getAlignment()->setHorizontal('center')->setVertical('center');
        $sheet->getStyle('G4:H5')->applyFromArray([
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3E5F5']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        
        $sheet->mergeCells('G6:H7');
        $sheet->setCellValue('G6', 'Rp ' . number_format($average, 0, ',', '.'));
        $sheet->getStyle('G6')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('G6')->getAlignment()->setHorizontal('center')->setVertical('center');
        
        // Data untuk chart
        $this->prepareChartData($sheet);
    }
    
    private function prepareChartData($sheet)
    {
        // Platform data
        $sheet->setCellValue('A10', 'PLATFORM');
        $sheet->setCellValue('B10', 'JUMLAH TRANSAKSI');
        $sheet->setCellValue('C10', 'TOTAL TERJUAL');
        $sheet->setCellValue('D10', 'TOTAL PENDAPATAN');
        
        $row = 11;
        foreach ($this->summaryData['platform'] as $platform => $data) {
            $platformNames = [
                'shopee' => 'Shopee',
                'tiktok' => 'TikTok',
                'offline' => 'Offline',
                'lainnya' => 'Lainnya'
            ];
            
            $sheet->setCellValue('A' . $row, $platformNames[$platform] ?? $platform);
            $sheet->setCellValue('B' . $row, $data['count']);
            $sheet->setCellValue('C' . $row, $data['total_sold']);
            $sheet->setCellValue('D' . $row, $data['total_revenue']);
            $row++;
        }
        
        // Monthly data
        $sheet->setCellValue('F10', 'BULAN');
        $sheet->setCellValue('G10', 'TOTAL TERJUAL');
        $sheet->setCellValue('H10', 'TOTAL PENDAPATAN');
        
        $row = 11;
        foreach ($this->summaryData['monthly'] as $data) {
            $sheet->setCellValue('F' . $row, $data['month']);
            $sheet->setCellValue('G' . $row, $data['total_sold']);
            $sheet->setCellValue('H' . $row, $data['total_revenue']);
            $row++;
        }
        
        // Top products
        $sheet->setCellValue('A30', 'PRODUK TERLARIS (TOP 10)');
        $sheet->setCellValue('A31', 'NAMA PRODUK');
        $sheet->setCellValue('B31', 'KODE');
        $sheet->setCellValue('C31', 'TOTAL TERJUAL');
        
        $row = 32;
        foreach ($this->summaryData['products'] as $product) {
            $sheet->setCellValue('A' . $row, $product['name']);
            $sheet->setCellValue('B' . $row, $product['code']);
            $sheet->setCellValue('C' . $row, $product['total_sold']);
            $row++;
        }
        
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
    
    public function charts()
    {
        $charts = [];
        
        // Platform pie chart
        if (!empty($this->summaryData['platform'])) {
            $platformLabels = [];
            $platformValues = [];
            
            $platformNames = [
                'shopee' => 'Shopee',
                'tiktok' => 'TikTok',
                'offline' => 'Offline',
                'lainnya' => 'Lainnya'
            ];
            
            foreach ($this->summaryData['platform'] as $key => $data) {
                if ($data['count'] > 0) {
                    $platformLabels[] = $platformNames[$key] ?? $key;
                    $platformValues[] = $data['count'];
                }
            }
            
            if (count($platformValues) > 0) {
                $charts[] = $this->createPieChart(
                    'Distribusi Penjualan per Platform',
                    $platformLabels,
                    $platformValues,
                    'A20'
                );
            }
        }
        
        // Monthly line chart
        if (!empty($this->summaryData['monthly'])) {
            $monthLabels = [];
            $monthValues = [];
            
            foreach ($this->summaryData['monthly'] as $data) {
                $monthLabels[] = $data['month'];
                $monthValues[] = $data['total_sold'];
            }
            
            if (count($monthValues) > 0) {
                $charts[] = $this->createLineChart(
                    'Trend Penjualan per Bulan',
                    $monthLabels,
                    $monthValues,
                    'F20'
                );
            }
        }
        
        // Top products bar chart
        if (!empty($this->summaryData['products'])) {
            $productLabels = [];
            $productValues = [];
            
            foreach ($this->summaryData['products'] as $product) {
                $productLabels[] = substr($product['name'], 0, 15) . '...';
                $productValues[] = $product['total_sold'];
            }
            
            if (count($productValues) > 0) {
                $charts[] = $this->createBarChart(
                    '10 Produk Terlaris',
                    $productLabels,
                    $productValues,
                    'A50'
                );
            }
        }
        
        return $charts;
    }
    
    private function createPieChart($title, $labels, $values, $position)
    {
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Dashboard Analitik!$A$11:$A$' . (10 + count($labels)), null, count($labels)),
        ];
        
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Dashboard Analitik!$B$11:$B$' . (10 + count($values)), null, count($values)),
        ];
        
        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            [],
            $dataSeriesValues
        );
        
        $layout = new Layout();
        $layout->setShowVal(true);
        $layout->setShowPercent(true);
        
        $plotArea = new PlotArea($layout, [$series]);
        $legend = new Legend();
        $legend->setPosition(Legend::POSITION_RIGHT);
        
        // PERBAIKAN: Hilangkan parameter colors yang menyebabkan error
        $chart = new Chart(
            'chart1',
            new Title($title),
            $legend,
            $plotArea
        );
        
        $chart->setTopLeftPosition($position);
        $chart->setBottomRightPosition(chr(ord($position[0]) + 10) . (intval(substr($position, 1)) + 15));
        
        return $chart;
    }
    
    private function createLineChart($title, $labels, $values, $position)
    {
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Dashboard Analitik!$F$11:$F$' . (10 + count($labels)), null, count($labels)),
        ];
        
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Dashboard Analitik!$G$11:$G$' . (10 + count($values)), null, count($values)),
        ];
        
        $series = new DataSeries(
            DataSeries::TYPE_LINECHART,
            null,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            [],
            $dataSeriesValues
        );
        
        $plotArea = new PlotArea(null, [$series]);
        
        $chart = new Chart(
            'chart2',
            new Title($title),
            null,
            $plotArea
        );
        
        $chart->setTopLeftPosition($position);
        $chart->setBottomRightPosition(chr(ord($position[0]) + 10) . (intval(substr($position, 1)) + 15));
        
        return $chart;
    }
    
    private function createBarChart($title, $labels, $values, $position)
    {
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Dashboard Analitik!$A$32:$A$' . (31 + count($labels)), null, count($labels)),
        ];
        
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Dashboard Analitik!$C$32:$C$' . (31 + count($values)), null, count($values)),
        ];
        
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            [],
            $dataSeriesValues
        );
        
        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend();
        $legend->setPosition(Legend::POSITION_RIGHT);
        
        $chart = new Chart(
            'chart3',
            new Title($title),
            $legend,
            $plotArea
        );
        
        $chart->setTopLeftPosition($position);
        $chart->setBottomRightPosition(chr(ord($position[0]) + 10) . (intval(substr($position, 1)) + 15));
        
        return $chart;
    }
}