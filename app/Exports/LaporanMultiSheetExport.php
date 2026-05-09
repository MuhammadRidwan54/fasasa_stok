<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanMultiSheetExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        
        $sheets[] = new LaporanDetailSheet('Ringkasan');
        $sheets[] = new LaporanPerPlatformSheet('Per Platform');
        $sheets[] = new LaporanPerProductSheet('Per Produk');
        
        return $sheets;
    }
}

class LaporanDetailSheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    private $sheetTitle;
    
    public function __construct($sheetTitle)
    {
        $this->sheetTitle = $sheetTitle;
    }
    
    public function title(): string
    {
        return $this->sheetTitle;
    }
    
    public function collection()
    {
        // Ambil data ringkasan dengan filter
        $query = Laporan::with('tas');
        
        // Filter dari session
        if (session('export_dari_tanggal')) {
            $query->whereDate('tanggal', '>=', session('export_dari_tanggal'));
        }
        
        if (session('export_ke_tanggal')) {
            $query->whereDate('tanggal', '<=', session('export_ke_tanggal'));
        }
        
        // Filter platform - MULTIPLE SELECT
        if (session('export_platform')) {
            $platforms = session('export_platform');
            
            if (is_array($platforms)) {
                $platforms = array_filter($platforms);
                if (!empty($platforms)) {
                    $query->whereIn('platform', $platforms);
                }
            } else {
                $query->where('platform', $platforms);
            }
        }
        
        // Filter periode
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
        
        // Ringkasan total
        $totalTerjual = $laporans->sum('jumlah_terjual');
        $totalPendapatan = $laporans->sum(function($item) {
            return $item->jumlah_terjual * $item->tas->harga;
        });
        $totalTransaksi = $laporans->count();
        
        // Info filter untuk ditampilkan
        $platformText = '';
        if (session('export_platform')) {
            $platforms = session('export_platform');
            $platformNames = [
                'shopee' => 'Shopee',
                'tiktok' => 'TikTok',
                'offline' => 'Offline',
                'affiliate' => 'Affiliate',
                'lainnya' => 'Lainnya'
            ];
            
            if (is_array($platforms)) {
                $selectedPlatforms = array_map(function($p) use ($platformNames) {
                    return $platformNames[$p] ?? $p;
                }, array_filter($platforms));
                if (!empty($selectedPlatforms)) {
                    $platformText = 'Platform: ' . implode(', ', $selectedPlatforms);
                }
            } else if (!empty($platforms)) {
                $platformText = 'Platform: ' . ($platformNames[$platforms] ?? $platforms);
            }
        }
        
        $periodeText = session('export_periode') ? ucfirst(str_replace('_', ' ', session('export_periode'))).' | ' : '';
        
        return collect([
            ['RINGKASAN PENJUALAN TAS FASASA', ''],
            ['Periode', $periodeText . $platformText],
            ['Tanggal Cetak', now()->format('d/m/Y H:i:s')],
            ['', ''],
            ['INFORMASI RINGKASAN', ''],
            ['Total Transaksi', $totalTransaksi],
            ['Total Terjual (pcs)', $totalTerjual],
            ['Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.')],
            ['Rata-rata per Transaksi', $totalTransaksi > 0 ? 'Rp ' . number_format($totalPendapatan / $totalTransaksi, 0, ',', '.') : '0'],
            ['', ''],
            ['DETAIL PER PLATFORM', ''],
        ]);
    }
    
    public function headings(): array
    {
        return ['Item', 'Nilai'];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            5 => ['font' => ['bold' => true]],
            11 => ['font' => ['bold' => true]],
        ];
    }
}

class LaporanPerPlatformSheet implements FromCollection, WithTitle, WithHeadings
{
    public function title(): string
    {
        return 'Per Platform';
    }
    
    public function headings(): array
    {
        return ['Platform', 'Jumlah Transaksi', 'Total Terjual', 'Total Pendapatan'];
    }
    
    public function collection()
    {
        $query = Laporan::with('tas');
        
        // Filter dari session
        if (session('export_dari_tanggal')) {
            $query->whereDate('tanggal', '>=', session('export_dari_tanggal'));
        }
        
        if (session('export_ke_tanggal')) {
            $query->whereDate('tanggal', '<=', session('export_ke_tanggal'));
        }
        
        // Filter platform - MULTIPLE SELECT
        if (session('export_platform')) {
            $platforms = session('export_platform');
            
            if (is_array($platforms)) {
                $platforms = array_filter($platforms);
                if (!empty($platforms)) {
                    $query->whereIn('platform', $platforms);
                }
            } else {
                $query->where('platform', $platforms);
            }
        }
        
        // Filter periode
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
        
        $laporans = $query->get();
        
        // Tentukan platform yang akan ditampilkan (filter atau semua)
        $platformsToShow = ['shopee', 'tiktok', 'offline', 'affiliate', 'lainnya'];
        
        // Jika ada filter platform, hanya tampilkan platform yang dipilih
        if (session('export_platform')) {
            $selectedPlatforms = session('export_platform');
            if (is_array($selectedPlatforms)) {
                $platformsToShow = array_filter($selectedPlatforms);
            } else {
                $platformsToShow = [$selectedPlatforms];
            }
        }
        
        $platformNames = [
            'shopee' => 'Shopee',
            'tiktok' => 'TikTok',
            'offline' => 'Offline',
            'affiliate' => 'Affiliate',
            'lainnya' => 'Lainnya'
        ];
        
        $result = collect();
        
        foreach ($platformsToShow as $platform) {
            if (empty($platform)) continue;
            
            $data = $laporans->where('platform', $platform);
            $totalTerjual = $data->sum('jumlah_terjual');
            $totalPendapatan = $data->sum(function($item) {
                return $item->jumlah_terjual * $item->tas->harga;
            });
            
            $result->push([
                'Platform' => $platformNames[$platform] ?? $platform,
                'Jumlah Transaksi' => $data->count(),
                'Total Terjual' => $totalTerjual,
                'Total Pendapatan' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.')
            ]);
        }
        
        // Total
        $totalTerjualAll = $laporans->sum('jumlah_terjual');
        $totalPendapatanAll = $laporans->sum(function($item) {
            return $item->jumlah_terjual * $item->tas->harga;
        });
        
        $result->push([
            'Platform' => 'TOTAL',
            'Jumlah Transaksi' => $laporans->count(),
            'Total Terjual' => $totalTerjualAll,
            'Total Pendapatan' => 'Rp ' . number_format($totalPendapatanAll, 0, ',', '.')
        ]);
        
        return $result;
    }
}

class LaporanPerProductSheet implements FromCollection, WithTitle, WithHeadings
{
    public function title(): string
    {
        return 'Per Produk';
    }
    
    public function headings(): array
    {
        return ['Kode Tas', 'Nama Tas', 'Warna', 'Jumlah Terjual', 'Total Pendapatan'];
    }
    
    public function collection()
    {
        $query = Laporan::with('tas');
        
        // Filter dari session
        if (session('export_dari_tanggal')) {
            $query->whereDate('tanggal', '>=', session('export_dari_tanggal'));
        }
        
        if (session('export_ke_tanggal')) {
            $query->whereDate('tanggal', '<=', session('export_ke_tanggal'));
        }
        
        // Filter platform - MULTIPLE SELECT
        if (session('export_platform')) {
            $platforms = session('export_platform');
            
            if (is_array($platforms)) {
                $platforms = array_filter($platforms);
                if (!empty($platforms)) {
                    $query->whereIn('platform', $platforms);
                }
            } else {
                $query->where('platform', $platforms);
            }
        }
        
        // Filter periode
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
        
        $laporans = $query->get();
        
        // Group by produk
        $grouped = $laporans->groupBy('tas_id');
        
        $result = collect();
        
        foreach ($grouped as $tasId => $items) {
            $tas = $items->first()->tas;
            $totalTerjual = $items->sum('jumlah_terjual');
            $totalPendapatan = $items->sum(function($item) {
                return $item->jumlah_terjual * $item->tas->harga;
            });
            
            $result->push([
                'Kode Tas' => $tas->kode_tas,
                'Nama Tas' => $tas->nama_tas,
                'Warna' => $items->first()->warna ?? $tas->warna_tas,
                'Jumlah Terjual' => $totalTerjual,
                'Total Pendapatan' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.')
            ]);
        }
        
        // Sort by jumlah terjual desc
        $result = $result->sortByDesc('Jumlah Terjual')->values();
        
        // Total
        $totalTerjualAll = $result->sum('Jumlah Terjual');
        $totalPendapatanAll = $laporans->sum(function($item) {
            return $item->jumlah_terjual * $item->tas->harga;
        });
        
        $result->push([
            'Kode Tas' => 'TOTAL',
            'Nama Tas' => '',
            'Warna' => '',
            'Jumlah Terjual' => $totalTerjualAll,
            'Total Pendapatan' => 'Rp ' . number_format($totalPendapatanAll, 0, ',', '.')
        ]);
        
        return $result;
    }
}