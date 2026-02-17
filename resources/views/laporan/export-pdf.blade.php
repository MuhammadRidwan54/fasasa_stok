<!-- resources/views/laporan/export-pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
        }
        .header p {
            color: #7f8c8d;
            margin: 5px 0;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-align: left;
        }
        table td {
            padding: 8px;
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
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #7f8c8d;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN TAS FASASA</h1>
        <p>Periode: {{ request('periode') ? ucfirst(str_replace('_', ' ', request('periode'))) : 'Semua Periode' }}</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>
    
    <div class="info-box">
        <strong>Ringkasan:</strong><br>
        • Total Transaksi: {{ $laporans->count() }}<br>
        • Total Terjual: {{ $totalTerjual }} pcs<br>
        • Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Tas</th>
                <th>Nama Tas</th>
                <th>Warna</th>
                <th>Terjual</th>
                <th>Platform</th>
                <th>Harga</th>
                <th class="text-right">Total</th>
                <th>Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $laporan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $laporan->tanggal->format('d/m/Y') }}</td>
                <td>{{ $laporan->tas->kode_tas }}</td>
                <td>{{ $laporan->tas->nama_tas }}</td>
                <td>{{ $laporan->tas->warna_tas }}</td>
                <td class="text-center">{{ $laporan->jumlah_terjual }}</td>
                <td>
                    @php
                        $platformNames = [
                            'shopee' => 'Shopee',
                            'tiktok' => 'TikTok',
                            // 'offline' => 'Offline',
                            'lainnya' => 'Lainnya'
                        ];
                    @endphp
                    {{ $platformNames[$laporan->platform] ?? $laporan->platform }}
                </td>
                <td>Rp {{ number_format($laporan->tas->harga, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($laporan->jumlah_terjual * $laporan->tas->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ $laporan->sisa_stok }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-center"><strong>{{ $totalTerjual }}</strong></td>
                <td></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Stok Tas Fasasa</p>
        <p>&copy; {{ date('Y') }} Fasasa - All rights reserved</p>
    </div>
</body>
</html>