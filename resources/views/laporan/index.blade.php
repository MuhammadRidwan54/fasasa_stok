@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-clipboard-data me-2"></i> Laporan Penjualan</h2>
        <p class="text-muted">Kelola dan eksport data penjualan</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('laporan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Laporan
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-funnel me-2"></i> Filter Laporan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan.index') }}" method="GET" id="filterForm" class="row g-3">
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-calendar me-1"></i> Periode</label>
                <select name="periode" class="form-select" id="periodeSelect">
                    <option value="">Semua Periode</option>
                    <option value="hari_ini" {{ request('periode') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="kemarin" {{ request('periode') == 'kemarin' ? 'selected' : '' }}>Kemarin</option>
                    <option value="minggu_ini" {{ request('periode') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ request('periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun_ini" {{ request('periode') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Custom Tanggal</option>
                </select>
            </div>
            
            <!-- Custom Date Range -->
            <div class="col-md-4" id="customDateRange" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-calendar-minus me-1"></i> Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" class="form-control" 
                               value="{{ request('dari_tanggal') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-calendar-plus me-1"></i> Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" class="form-control" 
                               value="{{ request('sampai_tanggal') }}">
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <label class="form-label"><i class="bi bi-bag me-1"></i> Pilih Tas</label>
                <select name="tas_id" class="form-select">
                    <option value="">Semua Tas</option>
                    @foreach($tas as $item)
                        <option value="{{ $item->id }}" {{ request('tas_id') == $item->id ? 'selected' : '' }}>
                            <i class="bi bi-upc-scan"></i> {{ $item->kode_tas }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label"><i class="bi bi-shop me-1"></i> Platform</label>
                <select name="platform" class="form-select">
                    <option value="">Semua Platform</option>
                    <option value="shopee" {{ request('platform') == 'shopee' ? 'selected' : '' }}>
                        Shopee
                    </option>
                    <option value="tiktok" {{ request('platform') == 'tiktok' ? 'selected' : '' }}>
                        TikTok Shop
                    </option>
                    <option value="offline" {{ request('platform') == 'offline' ? 'selected' : '' }}>
                        Offline
                    </option>
                    <option value="lainnya" {{ request('platform') == 'lainnya' ? 'selected' : '' }}>
                        Lainnya
                    </option>
                </select>
            </div>
            
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-check-circle me-1"></i> Terapkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Update Summary untuk tambah platform breakdown -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-receipt text-primary fs-1 mb-2"></i>
                <h5 class="card-title text-primary">{{ $laporans->count() }}</h5>
                <p class="card-text text-muted">Total Transaksi</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-cart-check text-success fs-1 mb-2"></i>
                <h5 class="card-title text-success">{{ $totalTerjual ?? 0 }} pcs</h5>
                <p class="card-text text-muted">Total Terjual</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body text-center">
                <i class="bi bi-cash-stack text-warning fs-1 mb-2"></i>
                <h5 class="card-title text-warning">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h5>
                <p class="card-text text-muted">Total Pendapatan</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body text-center">
                <i class="bi bi-shop text-info fs-1 mb-2"></i>
                <h5 class="card-title text-info">{{ $totalPlatforms ?? 0 }}</h5>
                <p class="card-text text-muted">Platform Aktif</p>
            </div>
        </div>
    </div>
</div>

<!-- Export Buttons -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><i class="bi bi-download me-2"></i> Eksport Data</h5>
                        <p class="text-muted mb-0">Export data berdasarkan filter yang dipilih</p>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-1"></i> Export Data
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" onclick="exportData('excel')">
                                    <i class="bi bi-file-earmark-excel text-success me-2"></i> Export Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="exportData('pdf')">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Export PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update tabel untuk tambah kolom platform -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-table me-2"></i> Data Laporan Penjualan</h5>
        <div>
            <span class="badge bg-info me-2"><i class="bi bi-list-ol"></i> {{ $laporans->total() }} transaksi</span>
            @if(request()->hasAny(['search', 'warna', 'status']))
                <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eye"></i> Tampilkan Semua
                </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if($laporans->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-clipboard-x text-muted fs-1 mb-3"></i>
                <h4>Tidak ada data laporan</h4>
                <p>Silahkan tambah laporan atau ubah filter pencarian</p>
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
                            <th width="120">Kode Tas</th>
                            <th width="120">Nama Tas</th>
                            <th width="80">Warna</th>
                            <th width="80">Terjual</th>
                            <th width="100">Platform</th>
                            <th width="100">Harga</th>
                            <th width="100">Total</th>
                            <th width="80">Sisa Stok</th>
                            <th width="120">Keterangan</th>
                            <th width="80">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporans as $laporan)
                        <tr>
                            <td>{{ ($laporans->currentPage() - 1) * $laporans->perPage() + $loop->iteration }}</td>
                            <td>
                                <i class="bi bi-calendar3 text-primary me-1"></i>
                                {{ $laporan->tanggal->format('d/m/Y') }}
                            </td>
                            <td>
                                <i class="bi bi-upc-scan text-secondary me-1"></i>
                                <strong>{{ $laporan->tas->kode_tas }}</strong>
                            </td>
                            <td>{{ $laporan->tas->nama_tas }}</td>
                            <td>
                                <span class="badge badge-stok" style="
                                    background-color: {{ $laporan->tas->warna_tas == 'Hitam' ? '#000' : 
                                                      ($laporan->tas->warna_tas == 'Merah' ? '#dc3545' : 
                                                      ($laporan->tas->warna_tas == 'Biru' ? '#0d6efd' : '#6c757d')) }};
                                    color: white;
                                    padding: 4px 8px;
                                ">
                                    {{ $laporan->tas->warna_tas }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    <i class="bi bi-cart"></i> {{ $laporan->jumlah_terjual }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $platformColors = [
                                        'shopee' => 'warning',
                                        'tiktok' => 'dark',
                                        'offline' => 'primary',
                                        'lainnya' => 'secondary'
                                    ];
                                    
                                    $platformIcons = [
                                        'shopee' => 'bi-shop',
                                        'tiktok' => 'bi-tiktok',
                                        'offline' => 'bi-shop-window',
                                        'lainnya' => 'bi-three-dots'
                                    ];
                                    
                                    $platformNames = [
                                        'shopee' => 'Shopee',
                                        'tiktok' => 'TikTok',
                                        'offline' => 'Offline',
                                        'lainnya' => 'Lainnya'
                                    ];
                                    
                                    $color = $platformColors[$laporan->platform] ?? 'secondary';
                                    $icon = $platformIcons[$laporan->platform] ?? 'bi-question-circle';
                                    $name = $platformNames[$laporan->platform] ?? 'Unknown';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <i class="bi {{ $icon }} me-1"></i> {{ $name }}
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-currency-dollar text-success me-1"></i>
                                {{ number_format($laporan->tas->harga, 0, ',', '.') }}
                            </td>
                            <td>
                                <strong class="text-success">
                                    <i class="bi bi-cash-stack"></i> {{ number_format($laporan->jumlah_terjual * $laporan->tas->harga, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <span class="badge @if($laporan->sisa_stok > 10) bg-success @elseif($laporan->sisa_stok > 0) bg-warning text-dark @else bg-danger @endif">
                                    <i class="bi bi-box"></i> {{ $laporan->sisa_stok }}
                                </span>
                            </td>
                            <td>
                                @if($laporan->keterangan)
                                    <small class="text-muted" title="{{ $laporan->keterangan }}">
                                        <i class="bi bi-chat-left-text"></i> {{ Str::limit($laporan->keterangan, 15) }}
                                    </small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('laporan.show', $laporan->id) }}" class="btn btn-info" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Hapus laporan ini? Stok akan dikembalikan.')" title="Hapus">
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
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    <i class="bi bi-list-check"></i> Menampilkan {{ $laporans->firstItem() }} - {{ $laporans->lastItem() }} dari {{ $laporans->total() }} transaksi
                </div>
                <div>
                    {{ $laporans->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.getElementById('periodeSelect');
    const customDateRange = document.getElementById('customDateRange');
    
    // Toggle custom date range
    periodeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDateRange.style.display = 'block';
        } else {
            customDateRange.style.display = 'none';
        }
    });
    
    // Trigger on load
    if (periodeSelect.value === 'custom') {
        customDateRange.style.display = 'block';
    }
    
    // Set default dates for custom range
    const today = new Date().toISOString().split('T')[0];
    const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 2).toISOString().split('T')[0];
    
    if (!document.querySelector('input[name="dari_tanggal"]').value) {
        document.querySelector('input[name="dari_tanggal"]').value = firstDayOfMonth;
    }
    if (!document.querySelector('input[name="sampai_tanggal"]').value) {
        document.querySelector('input[name="sampai_tanggal"]').value = today;
    }
});

// Function untuk export data
function exportData(type) {
    const form = document.getElementById('filterForm');
    const originalAction = form.action;
    
    if (type === 'excel') {
        form.action = "{{ route('laporan.export.excel') }}";
    } else if (type === 'pdf') {
        form.action = "{{ route('laporan.export.pdf') }}";
    }
    
    form.submit();
    
    // Kembalikan action ke semula
    setTimeout(() => {
        form.action = originalAction;
    }, 100);
}
</script>
@endsection
@endsection