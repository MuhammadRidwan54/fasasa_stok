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
        
        // ✅ DIPERBAIKI: Menggunakan kelas yang benar
        $sheets[] = new LaporanDetailSheet('Ringkasan');
        $sheets[] = new LaporanPerPlatformSheet('Per Platform');
        $sheets[] = new LaporanPerProductSheet('Per Produk');
        
        return $sheets;
    }
}

// ✅ DIPERBAIKI: Kelas Sheet yang benar
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
        // Ambil data ringkasan
        $query = Laporan::with('tas');
        
        // Filter dari session
        if (session('export_dari_tanggal')) {
            $query->whereDate('tanggal', '>=', session('export_dari_tanggal'));
        }
        
        if (session('export_ke_tanggal')) {
            $query->whereDate('tanggal', '<=', session('export_ke_tanggal'));
        }
        
        if (session('export_platform')) {
            $query->where('platform', session('export_platform'));
        }
        
        $laporans = $query->orderBy('tanggal', 'desc')->get();
        
        // Ringkasan total
        $totalTerjual = $laporans->sum('jumlah_terjual');
        $totalPendapatan = $laporans->sum(function($item) {
            return $item->jumlah_terjual * $item->tas->harga;
        });
        $totalTransaksi = $laporans->count();
        
        return collect([
            ['Ringkasan Penjualan', ''],
            ['Total Transaksi', $totalTransaksi],
            ['Total Terjual (pcs)', $totalTerjual],
            ['Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.')],
            ['Rata-rata per Transaksi', $totalTransaksi > 0 ? 'Rp ' . number_format($totalPendapatan / $totalTransaksi, 0, ',', '.') : '0'],
            ['', ''],
            ['Detail per Platform', ''],
            ['Shopee', $laporans->where('platform', 'shopee')->count()],
            ['TikTok', $laporans->where('platform', 'tiktok')->count()],
            ['Offline', $laporans->where('platform', 'offline')->count()],
            ['Lainnya', $laporans->where('platform', 'lainnya')->count()],
        ]);
    }
    
    public function headings(): array
    {
        return ['Item', 'Nilai'];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            7 => ['font' => ['bold' => true]],
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
        
        $laporans = $query->get();
        
        $platforms = ['shopee', 'tiktok', 'offline', 'lainnya'];
        $platformNames = [
            'shopee' => 'Shopee',
            'tiktok' => 'TikTok',
            'offline' => 'Offline',
            'lainnya' => 'Lainnya'
        ];
        
        $result = collect();
        
        foreach ($platforms as $platform) {
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
        
        if (session('export_platform')) {
            $query->where('platform', session('export_platform'));
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
                'Warna' => $tas->warna_tas,
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