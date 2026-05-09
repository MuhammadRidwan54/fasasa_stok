{{-- resources/views/exports/export-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - {{ date('d/m/Y') }}</title>
    <style>
        /* ===== PAGE CONFIGURATION ===== */
        @page {
            margin: 20px;
            size: A4 landscape;
        }
        
        /* ===== BASE STYLES ===== */
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        
        /* ===== HEADER STYLES ===== */
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
        
        /* ===== INFO & SUMMARY STYLES ===== */
        .info-box {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 10px;
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
        
        /* ===== TABLE STYLES ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        table th {
            background-color: #3498db;
            color: white;
            padding: 8px 3px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
            white-space: nowrap;
        }
        
        table td {
            padding: 6px 3px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #2c3e50 !important;
            color: white;
        }
        
        .zebra-stripe {
            background-color: #f9f9f9;
        }
        
        .hover-row:hover {
            background-color: #e3f2fd;
        }
        
        /* ===== COLUMN WIDTHS ===== */
        .col-no { width: 2%; }
        .col-tanggal { width: 5%; }
        .col-kode { width: 5%; }
        .col-nama { width: 8%; }
        .col-warna { width: 5%; }
        .col-terjual { width: 3%; }
        .col-platform { width: 5%; }
        .col-harga { width: 6%; }
        .col-total { width: 7%; }
        .col-stok-warna { width: 5%; }
        .col-stok-total { width: 4%; }
        .col-status { width: 4%; }
        .col-keterangan { width: 12%; }
        
        /* ===== TEXT ALIGNMENT ===== */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* ===== CURRENCY STYLES ===== */
        .currency {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }
        
        /* ===== BADGE STYLES ===== */
        .badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 7px;
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
        
        .badge-info {
            background-color: #e3f2fd;
            color: #0d47a1;
        }
        
        .badge-warna {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
            border: 1px solid #ddd;
            white-space: nowrap;
        }
        
        /* ===== STOK WARNA STYLES ===== */
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
        
        /* ===== KETERANGAN STYLES ===== */
        .keterangan-cell {
            font-size: 7px;
            line-height: 1.3;
            max-width: 150px;
            word-wrap: break-word;
            white-space: normal;
        }
        
        .keterangan-content {
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
            border-left: 2px solid #3498db;
            color: #495057;
        }
        
        .keterangan-empty {
            color: #adb5bd;
            font-style: italic;
            font-size: 7px;
        }
        
        /* ===== TOOLTIP STYLES ===== */
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
        
        /* ===== FOOTER STYLES ===== */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #7f8c8d;
            font-size: 9px;
        }
        
        /* ===== UTILITY STYLES ===== */
        .avoid-break {
            page-break-inside: avoid;
        }
        
        /* ===== EXPORT BUTTON STYLES ===== */
        .btn-export {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
        
        /* ===== RESPONSIVE STYLES ===== */
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
        date_default_timezone_set('Asia/Jakarta');
        $waktuCetak = date('d/m/Y H:i');
        
        $periodeText = 'Semua Periode';
        if (request('periode')) {
            $periodeText = ucfirst(str_replace('_', ' ', request('periode')));
        } elseif (request('dari_tanggal') && request('ke_tanggal')) {
            $dari = date('d/m/Y', strtotime(request('dari_tanggal')));
            $ke = date('d/m/Y', strtotime(request('ke_tanggal')));
            $periodeText = $dari . ' s/d ' . $ke;
        }
    @endphp
    
    {{-- HEADER SECTION --}}
    <div class="header avoid-break">
        <h1>LAPORAN PENJUALAN TAS FASASA</h1>
        <p>Periode: {{ $periodeText }}</p>
        <p>Tanggal Cetak: {{ $waktuCetak }} WIB</p>
    </div>
    
    {{-- SUMMARY SECTION --}}
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
    
    {{-- MAIN TABLE SECTION --}}
    <table class="avoid-break">
        <thead>
            <tr>
                <th class="col-no text-center">No</th>
                <th class="col-tanggal">Tanggal</th>
                <th class="col-kode">Kode</th>
                <th class="col-nama">Nama Tas</th>
                <th class="col-warna">Warna</th>
                <th class="col-terjual text-center">Qty</th>
                <th class="col-platform">Platform</th>
                <th class="col-harga text-right">Harga</th>
                <th class="col-total text-right">Total</th>
                <th class="col-stok-warna text-center">Stok Warna</th>
                <th class="col-stok-total text-center">Sisa Total</th>
                <th class="col-status text-center">Status</th>
                <th class="col-keterangan text-center">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
            @php
                $stokWarna = $laporan->tas->stokMasuk
                    ->where('warna', $laporan->warna ?? $laporan->tas->warna_tas)
                    ->sum('jumlah');
                
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
                $stokClass = $stokWarna > 10 ? 'badge-success' : ($stokWarna > 0 ? 'badge-warning' : 'badge-danger');
                
                $keterangan = $laporan->keterangan ?? '-';
                $keteranganSingkat = strlen($keterangan) > 50 ? substr($keterangan, 0, 50) . '...' : $keterangan;
                
                $platformNames = [
                    'shopee' => 'Shopee',
                    'tiktok' => 'TikTok',
                    'offline' => 'Offline',
                    'affiliate' => 'Affiliate',
                    'lainnya' => 'Lainnya'
                ];
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
                <td>{{ $platformNames[$laporan->platform] ?? $laporan->platform }}</td>
                <td class="text-right currency">Rp {{ number_format($laporan->tas->harga, 0, ',', '.') }}</td>
                <td class="text-right currency">Rp {{ number_format($laporan->jumlah_terjual * $laporan->tas->harga, 0, ',', '.') }}</td>
                <td class="text-center">
                    <div class="stok-warna-container">
                        <span class="badge {{ $stokClass }}" 
                              style="min-width: 35px; display: inline-block;"
                              title="Stok tersedia untuk warna {{ $warna }}">
                            {{ $stokWarna }}
                        </span>
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge badge-info" 
                          style="min-width: 35px;"
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
                <td class="keterangan-cell">
                    @if($keterangan && $keterangan != '-')
                        <div class="keterangan-content" title="{{ $keterangan }}">
                            {{ $keteranganSingkat }}
                        </div>
                    @else
                        <span class="keterangan-empty">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
            
            {{-- TOTAL ROW --}}
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-center"><strong>{{ $totalTerjual }}</strong></td>
                <td colspan="2"></td>
                <td class="text-right currency"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>
    
    {{-- DETAIL STOK PER WARNA SECTION --}}
    @if($laporans->count() > 0)
    <div style="page-break-before: avoid; margin-top: 20px;">
        <h4 style="color: #2c3e50; border-bottom: 1px solid #3498db; padding-bottom: 5px;">Detail Stok per Warna</h4>
        <table style="width: 70%; margin-top: 10px; font-size: 8px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tas</th>
                    <th>Kode</th>
                    <th>Warna</th>
                    <th class="text-center">Stok Tersedia</th>
                    <th class="text-center">Keterangan Stok</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
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
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $tas->nama_tas }}</td>
                            <td>{{ $tas->kode_tas }}#</td>
                            <td>{{ $warna }}</td>
                            <td class="text-center">{{ $stok }} pcs</td>
                            <td class="text-center">
                                @if($stok > 10)
                                    <span class="badge badge-success">Stok Aman</span>
                                @elseif($stok > 0)
                                    <span class="badge badge-warning">Stok Menipis</span>
                                @else
                                    <span class="badge badge-danger">Stok Habis</span>
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{-- RINGKASAN KETERANGAN SECTION --}}
    @php
        $laporanDenganKeterangan = $laporans->filter(function($l) {
            return !empty($l->keterangan) && $l->keterangan != '-';
        });
    @endphp
    
    @if($laporanDenganKeterangan->count() > 0)
    <div style="page-break-before: avoid; margin-top: 20px;">
        <h4 style="color: #2c3e50; border-bottom: 1px solid #3498db; padding-bottom: 5px;">Ringkasan Keterangan</h4>
        <table style="width: 100%; margin-top: 10px; font-size: 8px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode Tas</th>
                    <th>Nama Tas</th>
                    <th>Warna</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporanDenganKeterangan as $index => $laporan)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $laporan->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $laporan->tas->kode_tas }}#</td>
                    <td>{{ $laporan->tas->nama_tas }}</td>
                    <td>{{ $laporan->warna ?? $laporan->tas->warna_tas }}</td>
                    <td>{{ $laporan->keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endif
    
    {{-- FOOTER SECTION --}}
    <div class="footer avoid-break">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Stok Fasasa</p>
        <p>Alamat: Jl. Ciater Tengah No.68, RT./RW/RW.09/07, Ciater, Kec. Serpong, Kota Tanggerang Selatan | Telp: (+62) 85213452805 | Email: fasasaindo@gmail.com</p>
        <p>&copy; {{ date('Y') }} Fasasa - All rights reserved</p>
    </div>
</body>
</html>