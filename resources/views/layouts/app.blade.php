<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Stok Tas Fasasa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #fafbfc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            color: #2d3748;
        }
        .sidebar {
            background-color: #ffffff;
            min-height: 100vh;
            padding: 0;
            position: fixed;
            width: 250px;
            box-shadow: 1px 0 3px rgba(0,0,0,0.05);
            border-right: 1px solid #e2e8f0;
        }
        .sidebar .logo {
            padding: 16px 20px;
            text-align: center;
            background-color: #ffffff;
            color: #2d3748;
            font-weight: 600;
            font-size: 1.1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .sidebar .nav-link {
            color: #4a5568;
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        .sidebar .nav-link:hover {
            background-color: #f7fafc;
            color: #2d3748;
            border-left: 3px solid #cbd5e0;
        }
        .sidebar .nav-link.active {
            background-color: #edf2f7;
            color: #2d3748;
            border-left: 3px solid #4a5568;
            font-weight: 500;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 16px;
            text-align: center;
        }
        .main-content {
            margin-left: 250px;
            padding: 16px;
            min-height: 100vh;
        }
        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-left: 250px;
            padding: 0 20px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s;
            margin-bottom: 16px;
            background-color: #ffffff;
        }
        .card:hover {
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            border-color: #cbd5e0;
        }
        .card-header {
            background-color: #f7fafc;
            color: #2d3748;
            border-radius: 6px 6px 0 0 !important;
            padding: 12px 16px;
            font-weight: 500;
            font-size: 0.95rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .card-body {
            padding: 16px;
            font-size: 0.95rem;
        }
        .stat-card {
            text-align: center;
            padding: 16px;
        }
        .stat-card .number {
            font-size: 1.8rem;
            font-weight: 600;
            color: #2d3748;
        }
        .stat-card .label {
            color: #718096;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        .badge-stok {
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 400;
            font-size: 0.85rem;
        }
        .btn-primary {
            background-color: #4a5568;
            border-color: #4a5568;
            padding: 6px 16px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .btn-primary:hover {
            background-color: #2d3748;
            border-color: #2d3748;
        }
        .btn-secondary {
            background-color: #cbd5e0;
            border-color: #cbd5e0;
            color: #2d3748;
            padding: 6px 16px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .btn-secondary:hover {
            background-color: #a0aec0;
            border-color: #a0aec0;
        }
        .btn-info {
            background-color: #4a5568;
            border-color: #4a5568;
        }
        .btn-info:hover {
            background-color: #2d3748;
            border-color: #2d3748;
        }
        .btn-warning {
            background-color: #ed8936;
            border-color: #ed8936;
        }
        .btn-warning:hover {
            background-color: #c05621;
            border-color: #c05621;
        }
        .btn-danger {
            background-color: #e53e3e;
            border-color: #e53e3e;
        }
        .btn-danger:hover {
            background-color: #c53030;
            border-color: #c53030;
        }
        .table {
            font-size: 0.9rem;
        }
        .table th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: 600;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px;
            font-size: 0.85rem;
        }
        .table td {
            vertical-align: middle;
            padding: 10px;
            border-color: #e2e8f0;
        }
        .form-control, .form-select {
            border-radius: 4px;
            border: 1px solid #cbd5e0;
            padding: 8px;
            font-size: 0.9rem;
            background-color: #ffffff;
        }
        .form-control:focus, .form-select:focus {
            border-color: #a0aec0;
            box-shadow: 0 0 0 1px rgba(74, 85, 104, 0.1);
        }
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 4px;
        }
        .badge {
            font-size: 0.75rem;
            padding: 3px 8px;
            font-weight: 500;
        }
        .badge-info {
            background-color: #bee3f8;
            color: #2c5282;
        }
        .badge-success {
            background-color: #c6f6d5;
            color: #22543d;
        }
        .badge-warning {
            background-color: #feebc8;
            color: #7c2d12;
        }
        .badge-danger {
            background-color: #fed7d7;
            color: #742a2a;
        }
        .alert {
            font-size: 0.9rem;
            border-radius: 4px;
            border: 1px solid;
        }
        .alert-success {
            background-color: #f0fff4;
            border-color: #9ae6b4;
            color: #22543d;
        }
        .alert-danger {
            background-color: #fff5f5;
            border-color: #fc8181;
            color: #742a2a;
        }
        h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: #2d3748;
        }
        h2 {
            font-size: 1.4rem;
        }
        h5 {
            font-size: 0.95rem;
        }
        .text-muted {
            color: #718096;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .main-content, .navbar-custom {
                margin-left: 0;
            }
        }
        .bi {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    @auth
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="bi bi-bag-fill me-2"></i> FASASA
        </div>
        <nav class="nav flex-column pt-3">
            <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a class="nav-link {{ Request::is('tas*') && !Request::is('laporan*') ? 'active' : '' }}" href="{{ route('tas.index') }}">
                <i class="bi bi-box-seam"></i> Master Tas
            </a>
            <a class="nav-link {{ Request::is('laporan*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                <i class="bi bi-clipboard-data"></i> Laporan Harian
            </a>
            <div class="mt-4 px-3 text-white-50 small">
                <i class="bi bi-graph-up"></i> Quick Stats
            </div>
            <div class="px-3 py-2">
                <div class="text-white small"><i class="bi bi-box"></i> Total Stok</div>
                <div class="text-white h5">{{ \App\Models\Tas::sum('stok') ?? 0 }} pcs</div>
            </div>
            <div class="px-3 py-2">
                <div class="text-white small"><i class="bi bi-tags"></i> Total Model</div>
                <div class="text-white h5">{{ \App\Models\Tas::count() ?? 0 }} item</div>
            </div>
        </nav>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <span class="navbar-text">
                <strong><i class="bi bi-bag-check"></i> Stok Tas Fasasa</strong> • 
                <span class="text-muted">{{ auth()->user()->name }}</span>
            </span>
            <div class="d-flex align-items-center">
                <span class="me-3 text-muted">
                    <i class="bi bi-calendar3"></i> {{ date('d F Y') }}
                </span>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="dropdown-header text-muted small px-3">
                                <i class="bi bi-person-check"></i> Login sebagai: <strong>{{ auth()->user()->name }}</strong>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
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
    <div class="{{ auth()->check() ? 'main-content' : '' }}">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
