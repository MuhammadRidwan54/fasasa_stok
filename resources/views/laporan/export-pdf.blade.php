<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - {{ date('d/m/Y') }}</title>
    <style>
        @page {
            margin: 20px;
            size: A4 landscape;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 18px;
        }
        .header p {
            color: #7f8c8d;
            margin: 3px 0;
            font-size: 11px;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        table th {
            background-color: #3498db;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
        }
        table td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
            background-color: #2c3e50 !important;
            color: white;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #7f8c8d;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .summary-item {
            text-align: center;
            flex: 1;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }
        .summary-label {
            font-size: 10px;
            color: #7f8c8d;
        }
        
        /* Zebra stripes untuk table */
        .zebra-stripe {
            background-color: #f9f9f9;
        }
        
        /* Hover effect untuk table rows */
        .hover-row:hover {
            background-color: #e3f2fd;
        }
        
        /* Badge styles untuk status */
        .badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        /* Badge untuk warna */
        .badge-warna {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
            border: 1px solid #ddd;
        }
        
        /* Page break avoidance */
        .avoid-break {
            page-break-inside: avoid;
        }
        
        /* Column widths - disesuaikan */
        .col-no { width: 3%; }
        .col-tanggal { width: 7%; }
        .col-kode { width: 8%; }
        .col-nama { width: 12%; }
        .col-warna { width: 8%; }
        .col-terjual { width: 5%; }
        .col-platform { width: 7%; }
        .col-harga { width: 9%; }
        .col-total { width: 10%; }
        .col-stok-warna { width: 8%; }
        .col-stok-total { width: 6%; }
        .col-status { width: 6%; }
        
        /* Currency formatting */
        .currency {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }
        
        /* Stok per warna styling */
        .stok-warna-container {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .stok-warna-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2px 0;
        }
        .stok-warna-label {
            font-size: 7px;
            color: #666;
        }
        .stok-warna-value {
            font-weight: bold;
            font-size: 8px;
            padding: 1px 5px;
            border-radius: 10px;
            min-width: 25px;
            text-align: center;
        }
        
        /* Tooltip styling */
        .tooltip-info {
            position: relative;
            cursor: help;
            border-bottom: 1px dotted #999;
        }
        .tooltip-info:hover:after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 8px;
            white-space: nowrap;
            z-index: 1000;
        }

        /* tambahan */
        /* Export Button Styles */
        .btn-export {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-export i {
            transition: transform 0.3s ease;
        }

        .btn-export:hover i {
            transform: translateY(-2px);
        }

        .dropdown-menu {
            min-width: 200px;
        }

        .dropdown-item {
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        /* Mobile responsive for export buttons */
        @media (max-width: 768px) {
            .btn-group .btn {
                padding: 6px 10px;
                font-size: 11px;
            }
            
            .dropdown-menu {
                min-width: 180px;
            }
        }
    </style>
</head>
<body>
    @php
        // 1. SET TIMEZONE INDONESIA
        date_default_timezone_set('Asia/Jakarta');
        $waktuCetak = date('d/m/Y H:i');
        
        // 2. FORMAT PERIODE
        $periodeText = 'Semua Periode';
        if (request('periode')) {
            $periodeText = ucfirst(str_replace('_', ' ', request('periode')));
        } elseif (request('dari_tanggal') && request('ke_tanggal')) {
            // Format tanggal Indonesia
            $dari = date('d/m/Y', strtotime(request('dari_tanggal')));
            $ke = date('d/m/Y', strtotime(request('ke_tanggal')));
            $periodeText = $dari . ' s/d ' . $ke;
        }
    @endphp
    
    <div class="header avoid-break">
        <h1>LAPORAN PENJUALAN TAS FASASA</h1>
        <p>Periode: {{ $periodeText }}</p>
        <p>Tanggal Cetak: {{ $waktuCetak }} WIB</p>
    </div>
    
    <div class="summary-box avoid-break">
        <div class="summary-item">
            <div class="summary-value">{{ $laporans->count() }}</div>
            <div class="summary-label">Total Transaksi</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ $totalTerjual }} pcs</div>
            <div class="summary-label">Total Terjual</div>
        </div>
        <div class="summary-item">
            <div class="summary-value currency">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="summary-label">Total Pendapatan</div>
        </div>
        <div class="summary-item">
            <div class="summary-value">{{ $laporans->unique('platform')->count() }}</div>
            <div class="summary-label">Platform Aktif</div>
        </div>
    </div>
    
    <table class="avoid-break">
        <thead>
            <tr>
                <th class="col-no text-center">No</th>
                <th class="col-tanggal">Tanggal</th>
                <th class="col-kode">Kode Tas</th>
                <th class="col-nama">Nama Tas</th>
                <th class="col-warna">Warna</th>
                <th class="col-terjual text-center">Terjual</th>
                <th class="col-platform">Platform</th>
                <th class="col-harga text-right">Harga</th>
                <th class="col-total text-right">Total</th>
                <th class="col-stok-warna text-center">Stok Warna</th>
                <th class="col-stok-total text-center">Sisa Total</th>
                <th class="col-status text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
            @php
                // Hitung stok untuk warna tertentu dari stok_masuk
                $stokWarna = $laporan->tas->stokMasuk
                    ->where('warna', $laporan->warna ?? $laporan->tas->warna_tas)
                    ->sum('jumlah');
                
                // Mapping warna untuk badge styling
                $warnaColors = [
                    'Hitam' => ['bg' => '#333333', 'text' => '#ffffff'],
                    'Putih' => ['bg' => '#ffffff', 'text' => '#000000', 'border' => '#ddd'],
                    'Merah' => ['bg' => '#e74c3c', 'text' => '#ffffff'],
                    'Biru' => ['bg' => '#3498db', 'text' => '#ffffff'],
                    'Hijau' => ['bg' => '#27ae60', 'text' => '#ffffff'],
                    'Kuning' => ['bg' => '#f39c12', 'text' => '#000000'],
                    'Ungu' => ['bg' => '#8e44ad', 'text' => '#ffffff'],
                    'Pink' => ['bg' => '#e91e63', 'text' => '#ffffff'],
                    'Navy' => ['bg' => '#1a3a52', 'text' => '#ffffff'],
                    'Maroon' => ['bg' => '#85144b', 'text' => '#ffffff'],
                    'Abu-abu' => ['bg' => '#95a5a6', 'text' => '#000000'],
                    'Coklat' => ['bg' => '#A0522D', 'text' => '#ffffff'],
                    'Khaki' => ['bg' => '#f0e68c', 'text' => '#000000'],
                    'Caramel' => ['bg' => '#AF6E4D', 'text' => '#ffffff'],
                    'Cream' => ['bg' => '#FFFDD0', 'text' => '#000000'],
                    'Coffee' => ['bg' => '#D2691E', 'text' => '#ffffff'],
                    'Brown' => ['bg' => '#8B4513', 'text' => '#ffffff'],
                    'Black' => ['bg' => '#000000', 'text' => '#ffffff']
                ];
                
                $warna = $laporan->warna ?? $laporan->tas->warna_tas;
                $color = $warnaColors[$warna] ?? ['bg' => '#f8f9fa', 'text' => '#000000'];
                
                // Tentukan class badge berdasarkan stok
                $stokClass = $stokWarna > 10 ? 'badge-success' : ($stokWarna > 0 ? 'badge-warning' : 'badge-danger');
            @endphp
            <tr class="{{ $loop->even ? 'zebra-stripe' : '' }} hover-row">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $laporan->tanggal->format('d/m/Y') }}</td>
                <td><strong>{{ $laporan->tas->kode_tas }}#</strong></td>
                <td>{{ $laporan->tas->nama_tas }}</td>
                <td>
                    <span class="badge-warna" style="
                        background-color: {{ $color['bg'] }};
                        color: {{ $color['text'] }};
                        border: 1px solid {{ $color['border'] ?? '#dee2e6' }};
                    ">
                        {{ $warna }}
                    </span>
                </td>
                <td class="text-center">{{ $laporan->jumlah_terjual }}</td>
                <td>
                    @php
                        $platformNames = [
                            'shopee' => 'Shopee',
                            'tiktok' => 'TikTok',
                            'offline' => 'Offline',
                            'lainnya' => 'Lainnya'
                        ];
                        $platformIcons = [
                            'shopee' => '',
                            'tiktok' => '',
                            'offline' => '',
                            'lainnya' => ''
                        ];
                    @endphp
                    {{ $platformIcons[$laporan->platform] ?? '📱' }} {{ $platformNames[$laporan->platform] ?? $laporan->platform }}
                </td>
                <td class="text-right currency">Rp {{ number_format($laporan->tas->harga, 0, ',', '.') }}</td>
                <td class="text-right currency">Rp {{ number_format($laporan->jumlah_terjual * $laporan->tas->harga, 0, ',', '.') }}</td>
                <td class="text-center">
                    <div class="stok-warna-container">
                        <span class="badge {{ $stokClass }}" 
                              style="min-width: 35px; display: inline-block;"
                              title="Stok tersedia untuk warna {{ $warna }}">
                            {{ $stokWarna }} pcs
                        </span>
                        
                        @php
                            // Ambil semua warna yang tersedia untuk tas ini beserta stoknya
                            $stokPerWarna = $laporan->tas->stokMasuk
                                ->groupBy('warna')
                                ->map(function($items) {
                                    return $items->sum('jumlah');
                                });
                        @endphp
                        
                        @if($stokPerWarna->count() > 1)
                            <div class="stok-warna-item tooltip-info" 
                                 data-tooltip="Klik untuk detail stok per warna">
                                <span class="stok-warna-label">+{{ $stokPerWarna->count() - 1 }} warna lain</span>
                            </div>
                        @endif
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge badge-info" 
                          style="background-color: #e3f2fd; color: #0d47a1; min-width: 35px;"
                          title="Total stok semua warna">
                        {{ $laporan->sisa_stok }}
                    </span>
                </td>
                <td class="text-center">
                    @if($laporan->sisa_stok > 10)
                        <span class="badge badge-success">Aman</span>
                    @elseif($laporan->sisa_stok > 0)
                        <span class="badge badge-warning">Menipis</span>
                    @else
                        <span class="badge badge-danger">Kritis</span>
                    @endif
                </td>
            </tr>
            @endforeach
            
            <!-- Baris Total -->
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-center"><strong>{{ $totalTerjual }}</strong></td>
                <td></td>
                <td></td>
                <td class="text-right currency"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
                <td colspan="3"></td>
            </tr>
            
            <!-- Ringkasan Stok per Warna (Opsional) -->
            @php
                // Hitung total stok per warna dari semua transaksi
                $ringkasanWarna = [];
                foreach($laporans as $laporan) {
                    $warna = $laporan->warna ?? $laporan->tas->warna_tas;
                    $stok = $laporan->tas->stokMasuk
                        ->where('warna', $warna)
                        ->sum('jumlah');
                    
                    if(!isset($ringkasanWarna[$warna])) {
                        $ringkasanWarna[$warna] = 0;
                    }
                    // Tidak perlu dijumlah, karena stok per warna sudah dihitung dari total
                }
            @endphp
            
            @if(!empty($ringkasanWarna))
            <tr style="background-color: #f8f9fa;">
                <td colspan="12" style="padding: 5px;">
                    <small><strong>Ringkasan Stok per Warna:</strong> 
                    @foreach($ringkasanWarna as $warna => $stok)
                        <span style="margin-right: 10px;">{{ $warna }}: {{ $stok }} pcs</span>
                    @endforeach
                    </small>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <!-- Halaman Detail Stok per Warna (jika diperlukan) -->
    @if($laporans->count() > 0)
    <div style="page-break-before: avoid; margin-top: 20px;">
        <h4 style="color: #2c3e50; border-bottom: 1px solid #3498db; padding-bottom: 5px;">Detail Stok per Warna</h4>
        <table style="width: 50%; margin-top: 10px;">
            <thead>
                <tr>
                    <th>Nama Tas</th>
                    <th>Warna</th>
                    <th class="text-center">Stok Tersedia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporans->groupBy('tas.id') as $tasId => $items)
                    @php
                        $tas = $items->first()->tas;
                        $stokPerWarna = $tas->stokMasuk
                            ->groupBy('warna')
                            ->map(function($items) {
                                return $items->sum('jumlah');
                            });
                    @endphp
                    @foreach($stokPerWarna as $warna => $stok)
                        @if($stok > 0)
                        <tr>
                            <td>{{ $tas->nama_tas }} ({{ $tas->kode_tas }})</td>
                            <td>{{ $warna }}</td>
                            <td class="text-center">{{ $stok }} pcs</td>
                        </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <div class="footer avoid-break">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Stok Fasasa</p>
        <p>Alamat: Jl. Ciater Tengah No.68, RT./RW/RW.09/07, Ciater, Kec. Serpong, Kota Tanggerang Selatan | Telp: (+62) 85213452805 | Email: fasasaindo@gmail.com</p>
        <p>&copy; {{ date('Y') }} Fasasa - All rights reserved</p>
    </div>
</body>
</html>