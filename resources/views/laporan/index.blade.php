<!-- resources/views/laporan/index.blade.php -->
@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Harian</h1>
        <p class="page-subtitle">Kelola laporan penjualan dan stok keluar</p>
    </div>
    <div class="d-flex flex-column flex-md-row gap-2">
        <!-- Export Buttons Group -->
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-download me-1"></i> Export
            </button>
            <ul class="dropdown-menu">
                <!-- Basic Excel -->
                <li>
                    <form action="{{ route('laporan.export.excel') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        <input type="hidden" name="platform" value="{{ request('platform') }}">
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-excel me-2 text-success"></i> Excel (Basic)
                        </button>
                    </form>
                </li>
                
                <!-- Advanced Excel -->
                <li>
                    <form action="{{ route('laporan.export.excel.advanced') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        <input type="hidden" name="platform" value="{{ request('platform') }}">
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i> Excel (Advanced)
                        </button>
                    </form>
                </li>
                
                <!-- Multi-Sheet Excel -->
                <li>
                    <form action="{{ route('laporan.export.excel.multi') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        <input type="hidden" name="platform" value="{{ request('platform') }}">
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-bar-graph me-2 text-success"></i> Excel (Multi-Sheet)
                        </button>
                    </form>
                </li>
                
                <li><hr class="dropdown-divider"></li>
                
                <!-- PDF -->
                <li>
                    <form action="{{ route('laporan.export.pdf') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        <input type="hidden" name="platform" value="{{ request('platform') }}">
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> PDF
                        </button>
                    </form>
                </li>
                
                <!-- CSV -->
                <li>
                    <form action="{{ route('laporan.export.csv') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        <input type="hidden" name="platform" value="{{ request('platform') }}">
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-text me-2 text-primary"></i> CSV
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        
        <!-- Button Tambah Laporan -->
        <a href="{{ route('laporan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Laporan
        </a>
    </div>
</div>

<!-- Filter Section -->
<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-2">
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-calendar3"></i> Dari Tanggal</label>
                <input type="date" name="dari_tanggal" class="form-control" 
                       value="{{ request('dari_tanggal') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-calendar3"></i> Ke Tanggal</label>
                <input type="date" name="ke_tanggal" class="form-control" 
                       value="{{ request('ke_tanggal') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label"><i class="bi bi-clock"></i> Periode</label>
                <select name="periode" class="form-select">
                    <option value="">Pilih Periode</option>
                    <option value="hari_ini" {{ request('periode') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="kemarin" {{ request('periode') == 'kemarin' ? 'selected' : '' }}>Kemarin</option>
                    <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label"><i class="bi bi-shop"></i> Platform</label>
                <select name="platform" class="form-select">
                    <option value="">Semua</option>
                    <option value="shopee" {{ request('platform') == 'shopee' ? 'selected' : '' }}>Shopee</option>
                    <option value="tiktok" {{ request('platform') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                    <option value="offline" {{ request('platform') == 'offline' ? 'selected' : '' }}>Offline</option>
                    <option value="lainnya" {{ request('platform') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('laporan.index') }}" class="btn btn-secondary flex-grow-1">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="row mb-4">
     @php
        $statCards = [
            [
                'icon' => 'bi-receipt',
                'label' => 'Total Laporan',
                'value' => $laporans->total(),
                'color' => '#3498db',
                'animationDelay' => '0.6s',
                'prefix' => '',
                'suffix' => ''
            ],
            [
                'icon' => 'bi-box',
                'label' => 'Total Qty Terjual',
                'value' => $totalQty ?? 0,
                'color' => '#2ecc71',
                'animationDelay' => '0.7s',
                'prefix' => '',
                'suffix' => ''
            ],
            [
                'icon' => 'bi-cash-stack',
                'label' => 'Total Penjualan',
                'value' => $totalPenjualan ?? 0,
                'color' => '#f39c12',
                'animationDelay' => '0.8s',
                'prefix' => 'Rp ',
                'suffix' => ',00'
            ],
            [
                'icon' => 'bi-shop',
                'label' => 'Platform Aktif',
                'value' => $platformAktif ?? 0,
                'color' => '#9b59b6',
                'animationDelay' => '0.9s',
                'prefix' => '',
                'suffix' => ''
            ]
        ];
    @endphp
    
    @foreach($statCards as $index => $card)
    <div class="col-md-3 col-sm-6">
        <div class="stat-card stat-card-animated" 
             style="animation: fadeInUp 0.5s ease {{ $card['animationDelay'] }} forwards; opacity: 0; border-color: {{ $card['color'] }}20;">
            <i class="bi {{ $card['icon'] }} stat-card-icon" style="color: {{ $card['color'] }};"></i>
            <div class="stat-card-label">{{ $card['label'] }}</div>
            <div class="stat-card-number" 
                 id="stat-card-{{ $index }}" 
                 data-value="{{ $card['value'] }}"
                 data-prefix="{{ $card['prefix'] }}"
                 data-suffix="{{ $card['suffix'] }}"
                 style="color: {{ $card['color'] }};">
                @if($card['prefix'] == 'Rp ')
                    {{ $card['prefix'] }}0{{ $card['suffix'] }}
                @else
                    0{{ $card['suffix'] }}
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Table -->
<div class="card card-animated" style="animation: fadeInUp 0.6s ease 1s forwards; opacity: 0;">
    <!-- Filter Toggle Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="btn-group" role="group">
            <a href="{{ route('laporan.index', array_merge(request()->except('filter'), ['filter' => 'all'])) }}" 
            class="btn {{ request('filter', 'all') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-list-ul me-1"></i> Semua
            </a>
            <a href="{{ route('laporan.index', array_merge(request()->except('filter'), ['filter' => 'offline'])) }}" 
            class="btn {{ request('filter') == 'offline' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-shop-window me-1"></i> Promotions
            </a>
        </div>
        
        @if(request()->hasAny(['dari_tanggal', 'ke_tanggal', 'platform', 'filter']))
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary" style="padding: 4px 8px; font-size: 11px;">
                <i class="bi bi-arrow-clockwise"></i> Reset Filter
            </a>
        @endif
    </div>

    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <span>
            <i class="bi bi-table me-1"></i> Daftar Laporan 
            @if(request('filter') == 'offline')
                <span class="badge bg-primary ms-2">Toko/Endorse</span>
            @endif
        </span>
    </div>
    <div class="card-body">
        @if($laporans->isEmpty())
            <div style="text-align: center; padding: 40px 20px; color: #999;" class="empty-state-animated">
                <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 8px; animation: float 3s ease-in-out infinite;"></i>
                <h6>Data tidak ditemukan</h6>
                <p style="margin-bottom: 16px;">Silahkan coba filter lain atau tambahkan laporan baru</p>
                <a href="{{ route('laporan.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Laporan
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Tanggal</th>
                            <th width="100">Kode Tas</th>
                            <th width="120">Nama Tas</th>
                            <th width="80">Warna</th>
                            <th width="80">Platform</th>
                            <th width="60">Qty</th>
                            <th width="100">Harga</th>
                            <th width="100">Total</th>
                            <th width="60">Sisa</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporans as $index => $item)
                        <tr class="table-row-animated" style="animation: fadeInUp 0.4s ease {{ $index * 0.05 + 1.1 }}s forwards; opacity: 0;">
                            <td class="text-center">{{ ($laporans->currentPage() - 1) * $laporans->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->tanggal->format('d M Y') }}</td>
                            <td><strong class="table-link-animated" style="color: #000000; cursor: pointer;" onclick="showTasDetail({{ $item->tas->id }})">{{ $item->tas->kode_tas }}#</strong></td>
                            <td>{{ $item->tas->nama_tas }}</td>
                            <td>
                                @php
                                    // Ambil warna dari LAPORAN (bukan dari tas)
                                    $warna = $item->warna ?? $item->tas->warna_tas;
                                    
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
                                    
                                    $color = $warnaColors[$warna] ?? ['bg' => '#f8f9fa', 'text' => '#000000'];
                                @endphp
                                <span class="badge badge-colored" style="
                                    background-color: {{ $color['bg'] }};
                                    color: {{ $color['text'] }};
                                    border: 1px solid {{ $color['border'] ?? '#dee2e6' }};
                                    padding: 4px 8px;
                                    border-radius: 4px;
                                    font-size: 11px;
                                    white-space: nowrap;
                                ">
                                    {{ $warna }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info platform-badge">
                                    @switch($item->platform)
                                        @case('shopee')
                                            <i class="bi bi-shop"></i> Shopee
                                            @break
                                        @case('tiktok')
                                            <i class="bi bi-tiktok"></i> TikTok
                                            @break
                                        @case('offline')
                                            <i class="bi bi-shop-window"></i> Offline
                                            @break
                                        @default
                                            <i class="bi bi-three-dots"></i> Lainnya
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ $item->jumlah_terjual }} pcs</td>
                            <td>Rp {{ number_format($item->tas->harga, 0, ',', '.') }},00</td>
                            <td><strong class="text-success">Rp {{ number_format($item->jumlah_terjual * $item->tas->harga, 0, ',', '.') }},00</strong></td>
                            <td>
                                @php
                                    // Hitung stok untuk warna tertentu dari stok_masuk
                                    $stokWarna = $item->tas->stokMasuk
                                        ->where('warna', $item->warna)
                                        ->sum('jumlah');
                                    
                                    // Tentukan class badge berdasarkan stok
                                    $stokClass = $stokWarna > 10 ? 'badge-success' : ($stokWarna > 0 ? 'badge-warning' : 'badge-danger');
                                    
                                    // Tentukan label status
                                    $statusStok = $stokWarna > 10 ? 'Stok Tersedia' : ($stokWarna > 0 ? 'Stok Menipis' : 'Stok Habis');
                                @endphp
                                
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Badge Stok -->
                                    <span class="badge {{ $stokClass }} badge-pulse" 
                                        style="font-size: 12px; min-width: 45px; text-align: center;"
                                        title="{{ $statusStok }} untuk warna {{ $item->warna }}">
                                        {{ $stokWarna }}
                                    </span>
                                    
                                    <!-- Tooltip/Info kecil (opsional) -->
                                    <small class="text-muted" style="font-size: 10px; cursor: help;" 
                                        title="Stok tersedia untuk warna {{ $item->warna }}">
                                        <i class="bi bi-info-circle"></i>
                                    </small>
                                </div>
                                
                                <!-- Tampilkan sisa stok total (jika ingin ditampilkan juga) -->
                                @if($item->sisa_stok != $stokWarna)
                                    <div style="font-size: 9px; color: #999; margin-top: 2px;">
                                        Total: {{ $item->sisa_stok }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-animated">
                                    <a href="{{ route('laporan.show', $item->id) }}" class="btn btn-info btn-action" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('laporan.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-action" 
                                                onclick="return confirm('Hapus laporan ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 12px; border-top: 1px solid #e5e5e5; animation: fadeInUp 0.5s ease 1.5s forwards; opacity: 0;">
                <div style="font-size: 12px; color: #666;">
                    Menampilkan {{ $laporans->firstItem() }} - {{ $laporans->lastItem() }} dari {{ $laporans->total() }} item
                </div>
                <div>
                    {{ $laporans->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
</div>


<style>
    /* CSS tambahan untuk animasi angka */
    .stat-card-number {
        transition: all 0.5s ease-out;
    }

    .number-animate {
        animation: numberBounce 0.6s ease-out;
        display: inline-block;
    }

    @keyframes numberBounce {
        0% { transform: scale(0.5); opacity: 0; }
        50% { transform: scale(1.1); }
        70% { transform: scale(0.95); }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes countUp {
        from { transform: translateY(10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes numberPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Animasi untuk card saat hover */
    .stat-card:hover .stat-card-number {
        animation: numberPulse 1.5s ease-in-out infinite;
    }

    /* Efek glow untuk angka */
    .glow-effect {
        text-shadow: 0 0 10px currentColor;
    }



    /* Style untuk badge warna di tabel laporan */
    .badge-colored {
        transition: all 0.3s ease;
        display: inline-block;
        font-weight: 500;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .badge-colored:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        filter: brightness(1.1);
    }

    /* Animasi hover untuk badge warna */
    .table-row-animated:hover .badge-colored {
        transform: translateY(-2px) scale(1.05);
    }




    /* Table Animations for Laporan Page */
    .table-row-animated {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: left;
    }
    
    .table-row-animated:hover {
        background-color: rgba(0, 102, 204, 0.08) !important;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Link animations */
    .table-link-animated {
        transition: all 0.2s ease;
        position: relative;
    }
    
    .table-link-animated:hover {
        color: #0066cc !important;
        transform: translateX(2px);
        text-decoration: none;
    }
    
    .table-link-animated::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1px;
        bottom: -2px;
        left: 0;
        background-color: #0066cc;
        transition: width 0.3s ease;
    }
    
    .table-link-animated:hover::after {
        width: 100%;
    }
    
    /* Badge animations */
    .badge-pulse {
        animation: pulse 2s infinite;
        transition: all 0.3s ease;
    }
    
    .badge-pulse:hover {
        animation: none;
        transform: scale(1.1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .platform-badge {
        transition: all 0.3s ease;
    }
    
    .platform-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        filter: brightness(1.1);
    }
    
    /* Button group animations */
    .btn-group-animated {
        transition: all 0.3s ease;
    }
    
    .btn-action {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .btn-action:active {
        transform: translateY(0);
    }
    
    /* Empty state animation */
    .empty-state-animated {
        animation: fadeInUp 0.8s ease 1s forwards;
        opacity: 0;
    }
    
    /* Sales amount animation */
    .text-success {
        transition: all 0.3s ease;
    }
    
    .table-row-animated:hover .text-success {
        transform: scale(1.05);
        display: inline-block;
    }

    /* export */
    .export-form {
        margin: 0;
    }
    .export-form button {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        padding: 8px 16px;
    }
    .export-form button:hover {
        background-color: #f8f9fa;
    }
    .dropdown-item i {
        width: 20px;
    }
    
    /* Loading indicator */
    .export-loading {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    
    .export-loading .spinner {
        background: white;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
    }

    
    /* Keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInFromRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.1);
        }
        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(0, 0, 0, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
        }
    }
</style>

@section('scripts')
<script>
    function animateValue(element, start, end, duration) {
        const startTime = performance.now();
        const prefix = element.getAttribute('data-prefix') || '';
        const suffix = element.getAttribute('data-suffix') || '';
        
        // Format number dengan thousand separator
        const formatNumber = (num) => {
            const roundedNum = Math.round(num);
            
            // Format angka dengan titik sebagai pemisah ribuan
            let formattedNum;
            if (prefix === 'Rp ') {
                // Untuk currency, gunakan format Indonesia
                formattedNum = roundedNum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            } else {
                // Untuk angka biasa
                formattedNum = roundedNum.toString();
            }
            
            return `${prefix}${formattedNum}${suffix}`;
        };
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function (easeOutCubic)
            const easedProgress = 1 - Math.pow(1 - progress, 3);
            
            const currentValue = start + (end - start) * easedProgress;
            element.innerHTML = formatNumber(currentValue);
            
            // Add scale effect during animation
            if (progress < 1) {
                const scale = 1 + (Math.sin(progress * Math.PI * 8) * 0.05 * (1 - progress));
                element.style.transform = `scale(${scale})`;
                requestAnimationFrame(update);
            } else {
                element.innerHTML = formatNumber(end);
                element.style.transform = 'scale(1)';
                
                // Add final bounce animation
                element.classList.add('number-animate');
                setTimeout(() => {
                    element.classList.remove('number-animate');
                }, 600);
            }
        }
        
        requestAnimationFrame(update);
    }

    function initializeCounterAnimations() {
        const statNumbers = document.querySelectorAll('.stat-card-number[data-value]');
        
        statNumbers.forEach((element, index) => {
            const finalValue = parseFloat(element.getAttribute('data-value')) || 0;
            const prefix = element.getAttribute('data-prefix') || '';
            const suffix = element.getAttribute('data-suffix') || '';
            
            // Set delay berdasarkan posisi
            const delay = 600 + (index * 200);
            
            setTimeout(() => {
                // Set initial value dengan format yang benar
                if (prefix === 'Rp ') {
                    element.innerHTML = `${prefix}0${suffix}`;
                } else if (suffix) {
                    element.innerHTML = `0${suffix}`;
                } else {
                    element.innerHTML = '0';
                }
                
                console.log(`Animating card ${index}:`, {
                    element: element,
                    finalValue: finalValue,
                    prefix: prefix,
                    suffix: suffix,
                    startHTML: element.innerHTML
                });
                
                // Start animation
                animateValue(element, 0, finalValue, 1500);
                
                // Add glow effect setelah animasi selesai
                setTimeout(() => {
                    element.classList.add('glow-effect');
                    setTimeout(() => {
                        element.classList.remove('glow-effect');
                    }, 1000);
                }, 1600);
                
            }, delay);
        });
    }
       
    function showTasDetail(tasId) {
        // Animation feedback for clicked link
        const clickedLink = event.target;
        clickedLink.style.color = '#004080';
        setTimeout(() => {
            clickedLink.style.color = '';
        }, 300);
        
        window.open(`/tas/${tasId}`, '_blank');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize counter animations
        initializeCounterAnimations();
        
        // Add hover effects to buttons
        document.querySelectorAll('.btn-action').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 300);
                }
            });
        });
        
        // Add animation to platform badges
        document.querySelectorAll('.platform-badge').forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Add click animation for table rows
        document.querySelectorAll('.table-row-animated').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.btn-action') || e.target.closest('form')) {
                    return;
                }
                
                if (e.target.closest('.table-link-animated')) {
                    return;
                }
                
                this.style.backgroundColor = 'rgba(52, 152, 219, 0.1)';
                setTimeout(() => {
                    this.style.backgroundColor = '';
                }, 300);
                
                const viewBtn = this.querySelector('.btn-info');
                if (viewBtn && viewBtn.href) {
                    window.location.href = viewBtn.href;
                }
            });
        });
        
        // Add hover effect to sales amount
        document.querySelectorAll('.text-success').forEach(amount => {
            amount.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.textShadow = '0 2px 4px rgba(0, 128, 0, 0.2)';
            });
            
            amount.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.textShadow = 'none';
            });
        });

        // Export forms
        const exportForms = document.querySelectorAll('.export-form');
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'export-loading';
        loadingOverlay.innerHTML = `
            <div class="spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Menyiapkan laporan...</p>
                <small class="text-muted">Harap tunggu sebentar</small>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
        
        exportForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                loadingOverlay.style.display = 'flex';
                setTimeout(() => {
                    loadingOverlay.style.display = 'none';
                }, 30000);
            });
        });
        
        window.addEventListener('load', function() {
            loadingOverlay.style.display = 'none';
        });
        
        // Reload counter animations on page refresh (optional)
        let hasAnimated = false;
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible' && !hasAnimated) {
                setTimeout(initializeCounterAnimations, 100);
                hasAnimated = true;
            }
        });
    });
    
    // Re-run animations when navigating back to page
    if (performance.navigation.type === performance.navigation.TYPE_BACK_FORWARD) {
        setTimeout(initializeCounterAnimations, 500);
    }
</script>
@endsection
@endsection