<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Fasasa Local Brand</title>
    <link rel="icon" href="{{ asset('logo_fasasa.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #fafafa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 13px;
            color: #333;
        }
        
        /* Sidebar - Desktop */
        .sidebar {
            background-color: #fff;
            position: fixed;
            width: 220px;
            height: 100vh;
            border-right: 1px solid #e5e5e5;
            overflow-y: auto;
            padding-top: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        /* Sidebar - Mobile */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            .sidebar-overlay.active {
                display: block;
            }
        }
        
        /* Sidebar Toggle Button - Mobile */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: #000;
            margin-right: 15px;
            cursor: pointer;
        }
        
        @media (max-width: 992px) {
            .sidebar-toggle {
                display: block;
            }
        }
        
        .sidebar-header {
            padding: 16px;
            border-bottom: 1px solid #e5e5e5;
            text-align: center;
        }
        .sidebar-header-brand {
            font-size: 12px;
            font-weight: 600;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .sidebar-header-brand i {
            font-size: 16px;
        }
        .sidebar-nav {
            padding: 12px 0;
        }
        .nav-item {
            margin: 0;
        }
        .nav-link {
            padding: 10px 16px;
            color: #666;
            border-left: 3px solid transparent;
            font-size: 12px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-link i {
            font-size: 14px;
            width: 16px;
            text-align: center;
            color: #666;
        }
        .nav-link:hover {
            background-color: #f5f5f5;
            color: #000;
            border-left-color: #ddd;
        }
        .nav-link.active {
            background-color: #f0f0f0;
            color: #000;
            border-left-color: #000;
            font-weight: 500;
        }
        .nav-link.active i {
            color: #000;
        }
        .sidebar-stats {
            padding: 12px 16px;
            margin-top: 12px;
            border-top: 1px solid #e5e5e5;
            font-size: 11px;
        }
        .stat-item {
            margin-bottom: 12px;
        }
        .stat-label {
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 14px;
            font-weight: 600;
            color: #000;
        }
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 3px;
        }
        
        /* Main Content - Desktop */
        .main-content {
            margin-left: 220px;
            padding: 20px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        /* Main Content - Mobile */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
        
        /* Navbar - Desktop */
        .navbar-custom {
            background-color: #fff;
            border-bottom: 1px solid #e5e5e5;
            position: sticky;
            top: 0;
            z-index: 100;
            margin-left: 220px;
            transition: margin-left 0.3s ease;
        }
        
        /* Navbar - Mobile */
        @media (max-width: 992px) {
            .navbar-custom {
                margin-left: 0;
                padding: 10px 15px;
            }
        }
        
        .navbar-custom-brand {
            font-size: 13px;
            font-weight: 600;
            color: #000;
        }
        .navbar-custom-brand-muted {
            color: #999;
            font-weight: 400;
            font-size: 12px;
            margin-left: 8px;
        }
        
        /* Navbar Brand - Mobile */
        @media (max-width: 768px) {
            .navbar-custom-brand {
                font-size: 12px;
            }
            .navbar-custom-brand-muted {
                display: block;
                margin-left: 0;
                margin-top: 2px;
                font-size: 11px;
            }
        }
        
        .navbar-custom-date {
            font-size: 12px;
            color: #666;
            margin-right: 20px;
        }
        
        /* Navbar Date - Mobile */
        @media (max-width: 768px) {
            .navbar-custom-date {
                display: none;
            }
        }
        
        .navbar-custom-user {
            font-size: 12px;
            padding: 6px 12px !important;
            border: 1px solid #ddd !important;
            background-color: #fff !important;
            color: #000 !important;
            border-radius: 6px;
        }
        .navbar-custom-user:hover {
            background-color: #f5f5f5 !important;
        }
        .card {
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            box-shadow: none;
            background-color: #fff;
            margin-bottom: 16px;
        }
        .card-header {
            background-color: #f5f5f5;
            border-bottom: 1px solid #e5e5e5;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #000;
            border-radius: 0;
        }
        .card-body {
            padding: 16px;
            font-size: 12px;
        }
        
        /* Card Padding - Mobile */
        @media (max-width: 768px) {
            .card-body {
                padding: 12px;
            }
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        /* Page Header - Mobile */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 15px;
            }
            .page-header > div:last-child {
                align-self: stretch;
            }
        }
        
        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: #000;
            margin-bottom: 4px;
        }
        
        /* Page Title - Mobile */
        @media (max-width: 768px) {
            .page-title {
                font-size: 16px;
            }
        }
        
        .page-subtitle {
            font-size: 12px;
            color: #999;
        }
        .btn {
            font-size: 12px;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: 500;
            border: none;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        /* Button - Mobile */
        @media (max-width: 768px) {
            .btn {
                padding: 8px 12px;
                font-size: 11px;
            }
        }
        
        .btn-primary {
            background-color: #000;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #222;
            color: #fff;
        }
        .btn-secondary {
            background-color: #ddd;
            color: #000;
        }
        .btn-secondary:hover {
            background-color: #bbb;
            color: #000;
        }
        .btn-info {
            background-color: #e8e8e8;
            color: #000;
        }
        .btn-info:hover {
            background-color: #d0d0d0;
        }
        .btn-warning {
            background-color: #f5a623;
            color: #fff;
        }
        .btn-warning:hover {
            background-color: #d68910;
        }
        .btn-danger {
            background-color: #e74c3c;
            color: #fff;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .btn-outline-secondary {
            background-color: transparent;
            border: 1px solid #ddd;
            color: #666;
        }
        .btn-outline-secondary:hover {
            background-color: #f5f5f5;
            color: #000;
        }
        .btn-group .btn {
            margin: 0 2px;
            padding: 6px 10px;
        }
        .btn-group .btn:first-child {
            margin-left: 0;
        }
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        .table {
            font-size: 12px;
            margin-bottom: 0;
            width: 100%;
        }
        
        /* Table - Mobile */
        @media (max-width: 768px) {
            .table {
                font-size: 11px;
            }
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
        
        .table thead th {
            background-color: #f5f5f5;
            border-color: #e5e5e5;
            color: #000;
            font-weight: 600;
            padding: 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        /* Table Header - Mobile */
        @media (max-width: 768px) {
            .table thead th {
                padding: 8px 6px;
                font-size: 10px;
                white-space: nowrap;
            }
        }
        
        .table td {
            padding: 10px;
            border-color: #e5e5e5;
            vertical-align: middle;
        }
        
        /* Table Cells - Mobile */
        @media (max-width: 768px) {
            .table td {
                padding: 8px 6px;
                white-space: nowrap;
            }
        }
        
        .table tbody tr:hover {
            background-color: #f9f9f9;
        }
        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: #000;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .form-control, .form-select {
            font-size: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 8px;
            background-color: #fafafa;
        }
        .form-control:focus, .form-select:focus {
            border-color: #000;
            box-shadow: none;
            background-color: #fff;
        }
        .badge {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
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
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .alert {
            font-size: 12px;
            padding: 12px;
            border: 1px solid;
            border-radius: 6px;
            margin-bottom: 16px;
        }
        .alert-success {
            background-color: #f0fff4;
            border-color: #c6f6d5;
            color: #22543d;
        }
        .alert-danger {
            background-color: #fff5f5;
            border-color: #fed7d7;
            color: #742a2a;
        }
        .alert-info {
            background-color: #f0f9ff;
            border-color: #cce5ff;
            color: #003d99;
        }
        .dropdown-menu {
            font-size: 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .dropdown-item {
            padding: 8px 12px;
            color: #333;
        }
        .dropdown-item:hover {
            background-color: #f5f5f5;
            color: #000;
        }
        .dropdown-header {
            font-size: 11px;
            padding: 6px 12px;
        }
        .pagination {
            font-size: 11px;
        }
        .pagination .page-link {
            padding: 6px 10px;
            border: 1px solid #ddd;
            background-color: #fff;
            color: #333;
        }
        .pagination .page-link:hover {
            background-color: #f5f5f5;
        }
        .pagination .page-item.active .page-link {
            background-color: #d5d5d5;
            border-color: #ababab;
        }
        
        /* Pagination - Mobile */
        @media (max-width: 768px) {
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
            .pagination .page-link {
                padding: 4px 8px;
                font-size: 10px;
            }
        }
        
        .stat-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            padding: 16px;
            text-align: center;
            margin-bottom: 16px;
        }
        
        /* Stat Card - Mobile */
        @media (max-width: 768px) {
            .stat-card {
                padding: 12px;
                margin-bottom: 10px;
            }
        }
        
        .stat-card-number {
            font-size: 20px;
            font-weight: 600;
            color: #000;
            margin: 8px 0;
        }
        
        /* Stat Card Number - Mobile */
        @media (max-width: 768px) {
            .stat-card-number {
                font-size: 18px;
            }
        }
        
        .stat-card-label {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .stat-card-icon {
            font-size: 24px;
            color: #bbb;
        }
        
        /* Stat Card Icon - Mobile */
        @media (max-width: 768px) {
            .stat-card-icon {
                font-size: 20px;
            }
        }
        
        .breadcrumb {
            font-size: 11px;
            background-color: transparent;
            padding: 0 0 12px 0;
        }
        .breadcrumb-item a {
            color: #0066cc;
            text-decoration: none;
        }
        .breadcrumb-item.active {
            color: #666;
        }

        /* Table Hover Effects */
        .table tbody tr:hover {
            background-color: rgba(0, 102, 204, 0.05);
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        /* Modal styling */
        .modal-content {
            border-radius: 8px;
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }

        .modal-header {
            border-radius: 8px 8px 0 0;
            padding: 1rem 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Responsive Modal */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 10px;
            }
            .modal-body {
                padding: 1rem;
                max-height: 60vh;
            }
        }

        /* Responsive table in modal */
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Badge styling */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Grid System Responsive */
        @media (max-width: 768px) {
            .row {
                margin-left: -8px;
                margin-right: -8px;
            }
            .col-md-3, .col-md-6, .col-md-12 {
                padding-left: 8px;
                padding-right: 8px;
            }
        }

        /* Filter Form Responsive */
        @media (max-width: 768px) {
            .row.g-2 {
                margin-left: -5px;
                margin-right: -5px;
            }
            .row.g-2 > [class*="col-"] {
                padding-left: 5px;
                padding-right: 5px;
                margin-bottom: 10px;
            }
        }

        /* Mobile Action Buttons */
        @media (max-width: 576px) {
            .btn-group {
                flex-wrap: wrap;
                gap: 4px;
            }
            .btn-group .btn {
                margin: 2px;
                padding: 5px 8px;
                font-size: 10px;
            }
        }

        /* Chart Container Responsive */
        @media (max-width: 768px) {
            canvas {
                max-width: 100% !important;
                height: auto !important;
            }
        }

        /* Empty State Responsive */
        @media (max-width: 768px) {
            .text-center.py-4 {
                padding: 20px 10px !important;
            }
            .text-center.py-4 i {
                font-size: 24px !important;
            }
        }
    </style>
</head>
<body>
    @auth
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-header-brand">
                <img src="{{ asset('logofasasa_black.png') }}" alt="Fasasa" style="height: 24px; width: auto;">
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ Request::is('tas*') && !Request::is('laporan*') ? 'active' : '' }}" href="{{ route('tas.index') }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Master Tas</span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link {{ Request::is('laporan*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                    <i class="bi bi-clipboard-data"></i>
                    <span>Laporan Harian</span>
                </a>
            </div>
        </nav>
        <div class="sidebar-stats">
            <div class="stat-item">
                <div class="stat-label"><i class="bi bi-box"></i> Total Stok</div>
                {{-- Gunakan data yang sama dengan stok per warna --}}
                @php
                    $totalStokFromMasuk = \App\Models\StokMasuk::sum('jumlah') ?? 0;
                @endphp
                <div class="stat-value">{{ $totalStokFromMasuk }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label"><i class="bi bi-tags"></i> Total Model</div>
                <div class="stat-value">{{ \App\Models\Tas::count() ?? 0 }}</div>
            </div>
            
            <!-- Grid stok warna per tas -->
            <div class="stat-item">
                <div class="stat-label mb-2">
                    <i class="bi bi-grid-fill"></i> Stok Warna per Tas
                    <small class="float-end text-muted" id="toggleStokView" style="cursor: pointer;">
                        <i class="bi bi-chevron-down"></i>
                    </small>
                </div>
                
                <div id="stokWarnaContainer" style="max-height: 320px; overflow-y: auto; display: block;">
                    @php
                        // Query untuk mendapatkan stok per warna per tas
                        $stokData = \App\Models\StokMasuk::selectRaw('
                            tas_id,
                            warna,
                            SUM(jumlah) as stok_warna
                        ')
                        ->with(['tas' => function($query) {
                            $query->select('id', 'model_tas', 'nama_tas', 'kode_tas');
                        }])
                        ->whereHas('tas')
                        ->groupBy('tas_id', 'warna')
                        ->orderBy('tas_id')
                        ->orderBy('stok_warna', 'desc')
                        ->get()
                        ->groupBy('tas_id');
                        
                        // Helper function untuk warna
                        function getColorHexSidebar($warna) {
                            $colors = [
                                'black' => '#000000',
                                'brown' => '#8B4513',
                                'coffee' => '#5d2700',
                                'cream' => '#FFFDD0',
                                'maroon' => '#920000',
                                'khaki' => '#f0e68c',
                                'caramel' => '#AF6E4D',
                                'merah' => '#e74c3c',
                                'biru' => '#3498db',
                                'matcha' => '#90ff4b',
                                'sunflower' => '#ffef16',
                                'red cherry' => '#ff0000',
                                'pink' => '#e91e63',
                                'denim' => '#22435c',
                                'grey' => '#95a5a6',
                                'coklat' => '#A0522D',
                                'Terracota' => '#ff6600',
                                'Mocca' => '#6d5849',
                            ];
                            
                            if (!$warna) return '#6c757d';
                            
                            $warnaLower = strtolower(trim($warna));
                            return $colors[$warnaLower] ?? '#6c757d';
                        }
                        
                        $tasCount = 0;
                        $maxTasShow = 6; // Batasi jumlah tas yang ditampilkan
                    @endphp
                    
                    @if($stokData->isEmpty())
                        <div class="text-center text-muted py-3 small">
                            <i class="bi bi-inbox" style="font-size: 20px; opacity: 0.5;"></i>
                            <div class="mt-1">Tidak ada data stok per warna</div>
                        </div>
                    @else
                        @foreach($stokData as $tasId => $warnaItems)
                            @if($warnaItems->first()->tas && $tasCount < $maxTasShow)
                                @php 
                                    $tas = $warnaItems->first()->tas;
                                    $tasCount++;
                                @endphp
                                
                                <div class="mb-3 pb-2 border-bottom tas-stok-item">
                                    <!-- Header Tas -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div style="max-width: 70%;">
                                            <div class="fw-semibold small text-truncate" title="{{ $tas->model_tas }}">
                                                <i class="bi bi-bag me-1" style="font-size: 10px;"></i>
                                                {{ $tas->model_tas }}
                                            </div>
                                            <div class="text-muted extra-small text-truncate" title="{{ $tas->nama_tas }}">
                                                {{ $tas->nama_tas }}
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary small">
                                                {{ $warnaItems->sum('stok_warna') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Grid Warna -->
                                    <div class="row g-1">
                                        @foreach($warnaItems as $item)
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between align-items-center p-1 border rounded bg-light" 
                                                    style="min-height: 28px;">
                                                    <div class="d-flex align-items-center" style="max-width: 70%;">
                                                        <div class="color-dot me-1 flex-shrink-0" 
                                                            style="background-color: {{ getColorHexSidebar($item->warna) }};
                                                                    width: 8px; height: 8px; border-radius: 50%;
                                                                    @if(in_array(strtolower($item->warna), ['putih', 'white'])) border: 1px solid #dee2e6; @endif">
                                                        </div>
                                                        <span class="small text-truncate" title="{{ $item->warna }}">
                                                            {{ $item->warna }}
                                                        </span>
                                                    </div>
                                                    <span class="badge bg-dark small flex-shrink-0">{{ $item->stok_warna }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        
                        @if(count($stokData) > $maxTasShow)
                            <div class="text-center mt-2">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-secondary" 
                                        id="showMoreStok"
                                        style="font-size: 10px; padding: 2px 8px;">
                                    <i class="bi bi-plus-circle"></i> Tampilkan {{ count($stokData) - $maxTasShow }} tas lainnya
                                </button>
                            </div>
                            
                            <!-- Tas tersembunyi -->
                            <div id="hiddenTasStok" style="display: none;">
                                @php $hiddenCount = 0; @endphp
                                @foreach($stokData as $tasId => $warnaItems)
                                    @if($warnaItems->first()->tas && $hiddenCount >= $maxTasShow)
                                        @php $tas = $warnaItems->first()->tas; @endphp
                                        
                                        <div class="mb-3 pb-2 border-bottom tas-stok-item">
                                            <!-- Header Tas -->
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div style="max-width: 70%;">
                                                    <div class="fw-semibold small text-truncate" title="{{ $tas->model_tas }}">
                                                        <i class="bi bi-bag me-1" style="font-size: 10px;"></i>
                                                        {{ $tas->model_tas }}
                                                    </div>
                                                    <div class="text-muted extra-small text-truncate" title="{{ $tas->nama_tas }}">
                                                        {{ $tas->nama_tas }}
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-primary small">
                                                        {{ $warnaItems->sum('stok_warna') }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Grid Warna -->
                                            <div class="row g-1">
                                                @foreach($warnaItems as $item)
                                                    <div class="col-6">
                                                        <div class="d-flex justify-content-between align-items-center p-1 border rounded bg-light" 
                                                            style="min-height: 28px;">
                                                            <div class="d-flex align-items-center" style="max-width: 70%;">
                                                                <div class="color-dot me-1 flex-shrink-0" 
                                                                    style="background-color: {{ getColorHexSidebar($item->warna) }};
                                                                            width: 8px; height: 8px; border-radius: 50%;
                                                                            @if(in_array(strtolower($item->warna), ['putih', 'white'])) border: 1px solid #dee2e6; @endif">
                                                                </div>
                                                                <span class="small text-truncate" title="{{ $item->warna }}">
                                                                    {{ $item->warna }}
                                                                </span>
                                                            </div>
                                                            <span class="badge bg-dark small flex-shrink-0">{{ $item->stok_warna }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @php if($warnaItems->first()->tas) $hiddenCount++; @endphp
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar-custom">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 20px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <span class="navbar-custom-brand">
                    <i class="bi bi-bag-check me-1"></i> Fasasa Local Brand
                    <span class="navbar-custom-brand-muted">• {{ auth()->user()->name }}</span>
                </span>
            </div>
            <div style="display: flex; align-items: center; gap: 16px;">
                <span class="navbar-custom-date d-none d-md-block">
                    <i class="bi bi-calendar3"></i> {{ date('d M Y') }}
                </span>
                <div class="dropdown">
                    <button class="navbar-custom-user dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> 
                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                        <span class="d-md-none">User</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-header">
                                <i class="bi bi-person-check"></i> {{ auth()->user()->name }}
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size: 12px;"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size: 12px;"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.toggle('active');
                    }
                });
                
                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', function() {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                    });
                }
                
                // Close sidebar when clicking on a link (mobile)
                const sidebarLinks = document.querySelectorAll('.sidebar-nav .nav-link');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 992) {
                            sidebar.classList.remove('active');
                            if (sidebarOverlay) {
                                sidebarOverlay.classList.remove('active');
                            }
                        }
                    });
                });
            }
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('active');
                    }
                }
            });
            
            // Adjust layout on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992 && sidebar) {
                    sidebar.classList.remove('active');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('active');
                    }
                }
            });

            // Toggle show/hide stok warna
            const toggleStokView = document.getElementById('toggleStokView');
            const stokWarnaContainer = document.getElementById('stokWarnaContainer');
            
            if (toggleStokView && stokWarnaContainer) {
                toggleStokView.addEventListener('click', function() {
                    if (stokWarnaContainer.style.display === 'none') {
                        stokWarnaContainer.style.display = 'block';
                        toggleStokView.innerHTML = '<i class="bi bi-chevron-down"></i>';
                    } else {
                        stokWarnaContainer.style.display = 'none';
                        toggleStokView.innerHTML = '<i class="bi bi-chevron-right"></i>';
                    }
                });
            }
            
            // Show more tas
            const showMoreStok = document.getElementById('showMoreStok');
            const hiddenTasStok = document.getElementById('hiddenTasStok');
            
            if (showMoreStok && hiddenTasStok) {
                showMoreStok.addEventListener('click', function() {
                    hiddenTasStok.style.display = 'block';
                    showMoreStok.style.display = 'none';
                    
                    // Adjust scroll height
                    setTimeout(() => {
                        stokWarnaContainer.scrollTop = stokWarnaContainer.scrollHeight;
                    }, 100);
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>