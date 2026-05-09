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
                <li>
                    <form action="{{ route('laporan.export.excel') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        @php
                            $selectedPlatforms = request('platform', []);
                            if (!is_array($selectedPlatforms)) {
                                $selectedPlatforms = [$selectedPlatforms];
                            }
                        @endphp
                        
                        @foreach($selectedPlatforms as $platform)
                            <input type="hidden" name="platform[]" value="{{ $platform }}">
                        @endforeach
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-excel me-2 text-success"></i> Excel (Basic)
                        </button>
                    </form>
                </li>
                
                <li>
                    <form action="{{ route('laporan.export.excel.advanced') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        @php
                            $selectedPlatforms = request('platform', []);
                            if (!is_array($selectedPlatforms)) {
                                $selectedPlatforms = [$selectedPlatforms];
                            }
                        @endphp
                        
                        @foreach($selectedPlatforms as $platform)
                            <input type="hidden" name="platform[]" value="{{ $platform }}">
                        @endforeach
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i> Excel (Advanced)
                        </button>
                    </form>
                </li>
                
                <li>
                    <form action="{{ route('laporan.export.excel.multi') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        @php
                            $selectedPlatforms = request('platform', []);
                            if (!is_array($selectedPlatforms)) {
                                $selectedPlatforms = [$selectedPlatforms];
                            }
                        @endphp
                        
                        @foreach($selectedPlatforms as $platform)
                            <input type="hidden" name="platform[]" value="{{ $platform }}">
                        @endforeach
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-bar-graph me-2 text-success"></i> Excel (Multi-Sheet)
                        </button>
                    </form>
                </li>
                
                <li><hr class="dropdown-divider"></li>
                
                <li>
                    <form action="{{ route('laporan.export.pdf') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        @php
                            $selectedPlatforms = request('platform', []);
                            if (!is_array($selectedPlatforms)) {
                                $selectedPlatforms = [$selectedPlatforms];
                            }
                        @endphp
                        
                        @foreach($selectedPlatforms as $platform)
                            <input type="hidden" name="platform[]" value="{{ $platform }}">
                        @endforeach
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> PDF
                        </button>
                    </form>
                </li>
                
                <li>
                    <form action="{{ route('laporan.export.csv') }}" method="POST" class="d-inline export-form">
                        @csrf
                        <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        <input type="hidden" name="ke_tanggal" value="{{ request('ke_tanggal') }}">
                        @php
                            $selectedPlatforms = request('platform', []);
                            if (!is_array($selectedPlatforms)) {
                                $selectedPlatforms = [$selectedPlatforms];
                            }
                        @endphp
                        
                        @foreach($selectedPlatforms as $platform)
                            <input type="hidden" name="platform[]" value="{{ $platform }}">
                        @endforeach
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-file-earmark-text me-2 text-primary"></i> CSV
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        
        <a href="{{ route('laporan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Laporan
        </a>
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

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-2" id="filterForm">
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
                <label class="form-label"><i class="bi bi-shop"></i> Platform</label>
                <select name="platform" class="form-select">
                    <option value="">Pilih Platform</option>
                    <option value="tiktok" {{ request('platform') == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                    <option value="shopee" {{ request('platform') == 'shopee' ? 'selected' : '' }}>Shopee</option>
                    <option value="affiliate" {{ request('platform') == 'affiliate' ? 'selected' : '' }}>Affiliate</option>
                    <option value="offline" {{ request('platform') == 'offline' ? 'selected' : '' }}>Offline</option>
                </select>
            </div>
            
            <!-- SEARCH BAR -->
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-search"></i> Cari Laporan</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" 
                        id="searchLaporan" 
                        class="form-control border-start-0" 
                        placeholder="Cari berdasarkan kode tas, nama tas, warna, atau platform..."
                        autocomplete="off"
                        style="border-left: none;">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearchLaporan" style="display: none;">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <small class="text-muted" id="searchResultInfo" style="font-size: 11px; display: none;">
                    <i class="bi bi-info-circle"></i> <span id="resultCount">0</span> hasil ditemukan
                </small>
            </div>
            
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Table -->
<div class="card card-animated" style="animation: fadeInUp 0.6s ease 1s forwards; opacity: 0;">
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
                            <th width="250">Keterangan</th>
                            <th width="80">Platform</th>
                            <th width="60">Qty</th>
                            <th width="100">Harga</th>
                            <th width="100">Total</th>
                            <th width="60">Sisa</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="laporanTableBody">
                        @foreach($laporans as $index => $item)
                        @php
                            $warna = $item->warna ?? $item->tas->warna_tas;
                            
                            $warnaColors = [
                                'Hitam' => ['bg' => '#333333', 'text' => '#ffffff'],
                                'Putih' => ['bg' => '#ffffff', 'text' => '#000000', 'border' => '#ddd'],
                                'Merah' => ['bg' => '#e74c3c', 'text' => '#ffffff'],
                                'Biru' => ['bg' => '#3498db', 'text' => '#ffffff'],
                                'Matcha' => ['bg' => '#00ff59', 'text' => '#000000'],
                                'Kuning' => ['bg' => '#f39c12', 'text' => '#000000'],
                                'Ungu' => ['bg' => '#8e44ad', 'text' => '#ffffff'],
                                'Pink' => ['bg' => '#e91e63', 'text' => '#ffffff'],
                                'Navy' => ['bg' => '#1a3a52', 'text' => '#ffffff'],
                                'Maroon' => ['bg' => '#85144b', 'text' => '#ffffff'],
                                'Grey' => ['bg' => '#95a5a6', 'text' => '#000000'],
                                'Coklat' => ['bg' => '#A0522D', 'text' => '#ffffff'],
                                'Khaki' => ['bg' => '#f0e68c', 'text' => '#000000'],
                                'Caramel' => ['bg' => '#AF6E4D', 'text' => '#ffffff'],
                                'Cream' => ['bg' => '#FFFDD0', 'text' => '#000000'],
                                'Coffee' => ['bg' => '#AF6E4D', 'text' => '#ffffff'],
                                'Brown' => ['bg' => '#8B4513', 'text' => '#ffffff'],
                                'Black' => ['bg' => '#000000', 'text' => '#ffffff'],
                                'Cherry red' => ['bg' => '#ff0000', 'text' => 'rgb(255, 255, 255)'],
                            ];
                            
                            $color = $warnaColors[$warna] ?? ['bg' => '#f8f9fa', 'text' => '#000000'];
                            
                            $stokWarna = $item->tas->stokMasuk
                                ->where('warna', $item->warna)
                                ->sum('jumlah');
                            
                            $stokClass = $stokWarna > 10 ? 'badge-success' : ($stokWarna > 0 ? 'badge-warning' : 'badge-danger');
                            $statusStok = $stokWarna > 10 ? 'Stok Tersedia' : ($stokWarna > 0 ? 'Stok Menipis' : 'Stok Habis');
                            
                            // Escape string untuk JavaScript
                            $keteranganEscape = addslashes($item->keterangan ?? '');
                            $deskripsiEscape = addslashes($item->tas->deskripsi ?? 'Tidak ada deskripsi');
                        @endphp
                        <tr class="table-row-animated">
                            <td class="text-center">{{ ($laporans->currentPage() - 1) * $laporans->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->tanggal->format('d M Y') }}</td>
                            <td>
                                <strong class="table-link-animated tas-link" 
                                        style="color: #000000; cursor: pointer;"
                                        data-tas-id="{{ $item->tas->id }}"
                                        data-tas-name="{{ $item->tas->nama_tas }}"
                                        data-tas-code="{{ $item->tas->kode_tas }}"
                                        data-tas-price="{{ $item->tas->harga }}"
                                        data-tas-deskripsi="{{ $deskripsiEscape }}"
                                        data-tas-warna="{{ $warna }}"
                                        data-tas-stok="{{ $stokWarna }}"
                                        data-laporan-keterangan="{{ $keteranganEscape }}">
                                    {{ $item->tas->kode_tas }}#
                                </strong>
                                @if($item->keterangan)
                                    <i class="bi bi-chat-text-fill text-info" 
                                    style="font-size: 10px; cursor: help;" 
                                    title="{{ $item->keterangan }}"></i>
                                @endif
                            </td>
                            <td>{{ $item->tas->nama_tas }}</td>
                            <td>
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
                                @if($item->keterangan)
                                    <div class="keterangan-static" style="max-width: 250px; background-color: #f8f9fa; border-radius: 4px; padding: 4px 8px; border-left: 3px solid #0dcaf0;">
                                        <i class="bi bi-chat-text me-1 text-info" style="font-size: 10px;"></i>
                                        <span style="font-size: 11px; line-height: 1.4; word-wrap: break-word; white-space: normal;">
                                            {{ $item->keterangan }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted" style="font-size: 11px;">-</span>
                                @endif
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
                                        @case('affiliate')
                                            <i class="bi bi-link"></i> Affiliate
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
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge {{ $stokClass }} badge-pulse" 
                                        style="font-size: 12px; min-width: 45px; text-align: center;"
                                        title="{{ $statusStok }} untuk warna {{ $item->warna }}">
                                        {{ $stokWarna }}
                                    </span>
                                    <small class="text-muted" style="font-size: 10px; cursor: help;" 
                                        title="Stok tersedia untuk warna {{ $item->warna }}">
                                        <i class="bi bi-info-circle"></i>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-animated">
                                    <a href="{{ route('laporan.show', $item->id) }}" class="btn btn-info btn-action btn-sm" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('laporan.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-action btn-sm" 
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 12px; border-top: 1px solid #e5e5e5;">
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

<!-- Modal Detail Tas -->
<div class="modal fade" id="tasDetailModal" tabindex="-1" aria-labelledby="tasDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title text-white" id="tasDetailModalLabel">
                    <i class="bi bi-bag me-2"></i> Detail Tas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <div id="modalLoading" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
                <div id="modalContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Search input styling */
    #searchLaporan {
        transition: all 0.3s ease;
    }

    #searchLaporan:focus {
        border-color: #86b7fe;
        box-shadow: none;
    }

    #clearSearchLaporan {
        border-left: none;
    }

    #clearSearchLaporan:hover {
        background-color: #f8f9fa;
    }

    /* Search highlight styling */
    .search-highlight {
        background-color: #ffeb3b;
        padding: 0 2px;
        border-radius: 3px;
        font-weight: 500;
        display: inline-block;
    }

    /* Animasi loading saat search */
    .search-loading {
        position: relative;
    }

    .search-loading::after {
        content: '';
        position: absolute;
        right: 45px;
        top: 50%;
        transform: translateY(-50%);
        width: 14px;
        height: 14px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        z-index: 10;
    }

    @keyframes spin {
        0% { transform: translateY(-50%) rotate(0deg); }
        100% { transform: translateY(-50%) rotate(360deg); }
    }

    /* Row yang tidak sesuai akan disembunyikan */
    .filtered-out {
        display: none !important;
    }

    /* CSS untuk keterangan static (tidak berjalan) */
    .keterangan-static {
        transition: all 0.3s ease;
        word-break: break-word;
        white-space: normal;
        line-height: 1.4;
    }
    
    .keterangan-static:hover {
        background-color: #e9ecef !important;
        transform: translateX(2px);
    }
    
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

    @keyframes numberPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .stat-card:hover .stat-card-number {
        animation: numberPulse 1.5s ease-in-out infinite;
    }

    .glow-effect {
        text-shadow: 0 0 10px currentColor;
    }

    /* Style untuk badge warna di tabel laporan - VERSI LAMA */
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

    /* Badge animations - PULSE EFFECT */
    .badge-pulse {
        animation: pulse 2s infinite;
        transition: all 0.3s ease;
    }

    .badge-pulse:hover {
        animation: none;
        transform: scale(1.1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* Platform badge animations */
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

    /* Table Animations */
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

    /* Sales amount animation */
    .text-success {
        transition: all 0.3s ease;
    }
    
    .table-row-animated:hover .text-success {
        transform: scale(1.05);
        display: inline-block;
    }

    /* Empty state animation */
    .empty-state-animated {
        animation: fadeInUp 0.8s ease 1s forwards;
        opacity: 0;
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

    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .detail-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .detail-label {
        font-weight: 600;
        color: #495057;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .detail-value {
        font-size: 14px;
        color: #2c3e50;
        margin-top: 5px;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
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
    // Search functionality untuk filter tabel - VERSI YANG TIDAK MERUSAK HTML
    let searchTimeoutLaporan;
    let currentSearchTerm = '';

    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function highlightTextInElement(element, searchTerm) {
        if (!searchTerm || searchTerm.trim() === '') return;
        
        if (element.nodeType === Node.TEXT_NODE) {
            const text = element.textContent;
            if (text && text.toLowerCase().includes(searchTerm.toLowerCase())) {
                const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
                const span = document.createElement('span');
                span.innerHTML = text.replace(regex, '<span class="search-highlight">$1</span>');
                element.parentNode.replaceChild(span, element);
            }
        } else if (element.nodeType === Node.ELEMENT_NODE) {
            if (['SCRIPT', 'STYLE', 'INPUT', 'TEXTAREA'].includes(element.tagName)) return;
            if (element.classList && element.classList.contains('search-highlight')) return;
            
            const children = Array.from(element.childNodes);
            for (const child of children) {
                highlightTextInElement(child, searchTerm);
            }
        }
    }

    function removeHighlights(element) {
        if (!element) return;
        
        const highlights = element.querySelectorAll('.search-highlight');
        highlights.forEach(highlight => {
            const parent = highlight.parentNode;
            const text = highlight.textContent;
            const textNode = document.createTextNode(text);
            parent.replaceChild(textNode, highlight);
            parent.normalize();
        });
    }

    function filterLaporanTable(searchTerm) {
        const tbody = document.getElementById('laporanTableBody');
        if (!tbody) return;
        
        const rows = tbody.querySelectorAll('tr');
        let visibleCount = 0;
        
        removeHighlights(tbody);
        
        if (!searchTerm || searchTerm.trim() === '') {
            rows.forEach(row => {
                row.classList.remove('filtered-out');
                row.style.display = '';
            });
            
            const searchResultInfo = document.getElementById('searchResultInfo');
            if (searchResultInfo) searchResultInfo.style.display = 'none';
            
            const emptyMessage = document.getElementById('emptySearchMessage');
            if (emptyMessage) emptyMessage.remove();
            return;
        }
        
        const searchLower = searchTerm.toLowerCase().trim();
        
        rows.forEach(row => {
            let found = false;
            const rowText = row.textContent || row.innerText;
            if (rowText.toLowerCase().includes(searchLower)) {
                found = true;
            }
            
            if (found) {
                row.classList.remove('filtered-out');
                row.style.display = '';
                visibleCount++;
                highlightTextInElement(row, searchTerm);
            } else {
                row.classList.add('filtered-out');
                row.style.display = 'none';
            }
        });
        
        const searchResultInfo = document.getElementById('searchResultInfo');
        const resultCountSpan = document.getElementById('resultCount');
        
        if (searchResultInfo && resultCountSpan) {
            if (visibleCount > 0) {
                resultCountSpan.textContent = visibleCount;
                searchResultInfo.innerHTML = `<i class="bi bi-info-circle"></i> ${visibleCount} hasil ditemukan`;
                searchResultInfo.style.display = 'block';
            } else {
                searchResultInfo.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Tidak ada hasil untuk "${searchTerm}"`;
                searchResultInfo.style.display = 'block';
            }
        }
        
        let emptyMessage = document.getElementById('emptySearchMessage');
        if (visibleCount === 0) {
            if (!emptyMessage) {
                const tbodyEl = document.getElementById('laporanTableBody');
                emptyMessage = document.createElement('tr');
                emptyMessage.id = 'emptySearchMessage';
                emptyMessage.innerHTML = `
                    <td colspan="12" style="text-align: center; padding: 40px;">
                        <i class="bi bi-inbox" style="font-size: 48px; color: #ccc;"></i>
                        <p class="mt-2 text-muted">Tidak ada data yang ditemukan untuk "<strong>${searchTerm}</strong>"</p>
                        <button class="btn btn-sm btn-outline-primary" onclick="clearLaporanSearch()">
                            <i class="bi bi-x-circle"></i> Hapus Pencarian
                        </button>
                    </td>
                `;
                tbodyEl.appendChild(emptyMessage);
            } else {
                emptyMessage.style.display = '';
                const strongEl = emptyMessage.querySelector('strong');
                if (strongEl) strongEl.textContent = searchTerm;
            }
        } else {
            if (emptyMessage) emptyMessage.style.display = 'none';
        }
    }

    function clearLaporanSearch() {
        const searchInput = document.getElementById('searchLaporan');
        if (searchInput) {
            searchInput.value = '';
            
            const tbody = document.getElementById('laporanTableBody');
            if (tbody) removeHighlights(tbody);
            
            filterLaporanTable('');
            
            const clearBtn = document.getElementById('clearSearchLaporan');
            if (clearBtn) clearBtn.style.display = 'none';
            
            const searchResultInfo = document.getElementById('searchResultInfo');
            if (searchResultInfo) searchResultInfo.style.display = 'none';
            
            searchInput.classList.remove('search-loading');
            
            const emptyMessage = document.getElementById('emptySearchMessage');
            if (emptyMessage) emptyMessage.remove();
        }
    }

    function animateValue(element, start, end, duration) {
        const startTime = performance.now();
        const prefix = element.getAttribute('data-prefix') || '';
        const suffix = element.getAttribute('data-suffix') || '';
        
        const formatNumber = (num) => {
            const roundedNum = Math.round(num);
            let formattedNum;
            if (prefix === 'Rp ') {
                formattedNum = roundedNum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            } else {
                formattedNum = roundedNum.toString();
            }
            return `${prefix}${formattedNum}${suffix}`;
        };
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easedProgress = 1 - Math.pow(1 - progress, 3);
            const currentValue = start + (end - start) * easedProgress;
            element.innerHTML = formatNumber(currentValue);
            
            if (progress < 1) {
                const scale = 1 + (Math.sin(progress * Math.PI * 8) * 0.05 * (1 - progress));
                element.style.transform = `scale(${scale})`;
                requestAnimationFrame(update);
            } else {
                element.innerHTML = formatNumber(end);
                element.style.transform = 'scale(1)';
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
            const delay = 600 + (index * 200);
            
            setTimeout(() => {
                if (prefix === 'Rp ') {
                    element.innerHTML = `${prefix}0${suffix}`;
                } else if (suffix) {
                    element.innerHTML = `0${suffix}`;
                } else {
                    element.innerHTML = '0';
                }
                animateValue(element, 0, finalValue, 1500);
                
                setTimeout(() => {
                    element.classList.add('glow-effect');
                    setTimeout(() => {
                        element.classList.remove('glow-effect');
                    }, 1000);
                }, 1600);
            }, delay);
        });
    }
    
    function showTasDetailModal(tasId, tasName, tasCode, tasPrice, tasDeskripsi, tasWarna, tasStok, laporanKeterangan) {
        const modal = new bootstrap.Modal(document.getElementById('tasDetailModal'));
        const modalContent = document.getElementById('modalContent');
        const modalLoading = document.getElementById('modalLoading');
        
        modalLoading.style.display = 'block';
        modalContent.style.display = 'none';
        
        const formattedPrice = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(tasPrice);
        
        setTimeout(() => {
            modalContent.innerHTML = `
                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-card">
                            <div class="d-flex align-items-center mb-3">
                                <div class="info-icon me-3">
                                    <i class="bi bi-bag fs-4"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0">${tasName}</h4>
                                    <small class="text-muted">${tasCode}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="bi bi-tag me-1"></i> Harga
                            </div>
                            <div class="detail-value">
                                <strong class="text-success">${formattedPrice}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="bi bi-palette me-1"></i> Warna
                            </div>
                            <div class="detail-value">
                                <span class="badge" style="background-color: ${getWarnaColor(tasWarna)}; color: white;">
                                    ${tasWarna}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="bi bi-box-seam me-1"></i> Stok Tersedia
                            </div>
                            <div class="detail-value">
                                <strong class="${tasStok > 10 ? 'text-success' : (tasStok > 0 ? 'text-warning' : 'text-danger')}">
                                    ${tasStok} pcs
                                </strong>
                                ${tasStok <= 10 ? '<small class="text-muted ms-2">(Stok menipis)</small>' : ''}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="bi bi-info-circle me-1"></i> Status Stok
                            </div>
                            <div class="detail-value">
                                <span class="badge ${tasStok > 10 ? 'bg-success' : (tasStok > 0 ? 'bg-warning' : 'bg-danger')}">
                                    ${tasStok > 10 ? 'Stok Tersedia' : (tasStok > 0 ? 'Stok Menipis' : 'Stok Habis')}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                ${laporanKeterangan ? `
                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-card" style="background-color: #fff3cd; border-left: 4px solid #ffc107;">
                            <div class="detail-label">
                                <i class="bi bi-chat-text me-1"></i> Keterangan Laporan
                            </div>
                            <div class="detail-value">
                                <div style="background-color: white; padding: 10px; border-radius: 8px; margin-top: 8px;">
                                    <i class="bi bi-chat-text-fill text-warning me-2"></i>
                                    ${laporanKeterangan}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ` : ''}
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="bi bi-file-text me-1"></i> Deskripsi Tas
                            </div>
                            <div class="detail-value">
                                ${tasDeskripsi || '<em class="text-muted">Tidak ada deskripsi</em>'}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            modalLoading.style.display = 'none';
            modalContent.style.display = 'block';
        }, 300);
        
        modal.show();
    }
    
    function getWarnaColor(warna) {
        const warnaMap = {
            'Hitam': '#333333',
            'Putih': '#666666',
            'Merah': '#e74c3c',
            'Biru': '#3498db',
            'Hijau': '#27ae60',
            'Kuning': '#f39c12',
            'Ungu': '#8e44ad',
            'Pink': '#e91e63',
            'Navy': '#1a3a52',
            'Maroon': '#85144b',
            'Abu-abu': '#95a5a6',
            'Coklat': '#A0522D',
            'Khaki': '#f0e68c',
            'Caramel': '#AF6E4D',
            'Cream': '#FFFDD0',
            'Coffee': '#D2691E',
            'Brown': '#8B4513',
            'Black': '#000000'
        };
        return warnaMap[warna] || '#6c757d';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        initializeCounterAnimations();
        
        // Event listener untuk search
        const searchInput = document.getElementById('searchLaporan');
        const clearBtn = document.getElementById('clearSearchLaporan');
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value;
                
                if (clearBtn) {
                    clearBtn.style.display = searchTerm ? 'block' : 'none';
                }
                
                if (searchTerm) {
                    searchInput.classList.add('search-loading');
                } else {
                    searchInput.classList.remove('search-loading');
                }
                
                clearTimeout(searchTimeoutLaporan);
                searchTimeoutLaporan = setTimeout(() => {
                    filterLaporanTable(searchTerm);
                    searchInput.classList.remove('search-loading');
                }, 300);
            });
        }
        
        if (clearBtn) {
            clearBtn.addEventListener('click', clearLaporanSearch);
        }
        
        // Event listener untuk link tas
        document.querySelectorAll('.tas-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const tasId = this.getAttribute('data-tas-id');
                const tasName = this.getAttribute('data-tas-name');
                const tasCode = this.getAttribute('data-tas-code');
                const tasPrice = parseFloat(this.getAttribute('data-tas-price'));
                const tasDeskripsi = this.getAttribute('data-tas-deskripsi');
                const tasWarna = this.getAttribute('data-tas-warna');
                const tasStok = parseInt(this.getAttribute('data-tas-stok'));
                const laporanKeterangan = this.getAttribute('data-laporan-keterangan');
                
                showTasDetailModal(tasId, tasName, tasCode, tasPrice, tasDeskripsi, tasWarna, tasStok, laporanKeterangan);
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
    });
    
    window.filterLaporanTable = filterLaporanTable;
    window.clearLaporanSearch = clearLaporanSearch;
    window.removeHighlights = removeHighlights;
    
    if (performance.navigation.type === performance.navigation.TYPE_BACK_FORWARD) {
        setTimeout(initializeCounterAnimations, 500);
    }
</script>
@endsection
@endsection