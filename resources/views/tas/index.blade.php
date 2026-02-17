@extends('layouts.app')

@section('title', 'Master Tas')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-box-seam me-2"></i> Master Data Tas</h2>
        <p class="text-muted">Kelola data tas, warna, dan stok</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('tas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Tas Baru
        </a>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('tas.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-search me-1"></i> Cari</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Kode/Nama/Model..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-palette me-1"></i> Warna</label>
                <select name="warna" class="form-select">
                    <option value="">Semua Warna</option>
                    @foreach(['Hitam', 'Putih', 'Merah', 'Biru', 'Hijau', 'Coklat', 'Abu-abu', 'Kuning', 'Ungu', 'Pink', 'Navy', 'Maroon'] as $warna)
                        <option value="{{ $warna }}" {{ request('warna') == $warna ? 'selected' : '' }}>
                            {{ $warna }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-exclamation-triangle me-1"></i> Status Stok</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Stok Aman (>10)</option>
                    <option value="menipis" {{ request('status') == 'menipis' ? 'selected' : '' }}>Stok Menipis (1-10)</option>
                    <option value="kritis" {{ request('status') == 'kritis' ? 'selected' : '' }}>Stok Kritis (0)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('tas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body text-center">
                <i class="bi bi-boxes text-primary fs-1 mb-2"></i>
                <h5 class="card-title text-primary">{{ $tas->total() }}</h5>
                <p class="card-text text-muted">Total Item</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body text-center">
                <i class="bi bi-box text-success fs-1 mb-2"></i>
                <h5 class="card-title text-success">{{ $totalStok }}</h5>
                <p class="card-text text-muted">Total Stok</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body text-center">
                <i class="bi bi-cash-stack text-warning fs-1 mb-2"></i>
                <h5 class="card-title text-warning">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h5>
                <p class="card-text text-muted">Nilai Stok</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-octagon text-danger fs-1 mb-2"></i>
                <h5 class="card-title text-danger">{{ $stokKritis }}</h5>
                <p class="card-text text-muted">Stok Kritis</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-table me-2"></i> Daftar Tas</h5>
        <div>
            <span class="badge bg-info"><i class="bi bi-list-ol"></i> {{ $tas->count() }} item</span>
            @if(request()->hasAny(['search', 'warna', 'status']))
                <a href="{{ route('tas.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                    <i class="bi bi-eye"></i> Tampilkan Semua
                </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if($tas->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inboxes text-muted fs-1 mb-3"></i>
                <h4>Data tidak ditemukan</h4>
                <p>Silahkan coba filter lain atau tambahkan data baru</p>
                <a href="{{ route('tas.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Tas
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="120">Kode Tas</th>
                            <th width="100">Model</th>
                            <th width="150">Nama Tas</th>
                            <th width="100">Warna</th>
                            <th width="100">Stok</th>
                            <th width="120">Harga</th>
                            <th width="120">Total Nilai</th>
                            <th width="100">Status</th>
                            <th width="180" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tas as $item)
                        <tr class="align-middle">
                            <td class="text-center">{{ ($tas->currentPage() - 1) * $tas->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="kode-container bg-light p-2 rounded border">
                                    <i class="bi bi-upc-scan text-primary me-1"></i>
                                    <strong class="text-primary">{{ $item->kode_tas }}</strong>
                                </div>
                            </td>
                            <td>{{ $item->model_tas }}</td>
                            <td>{{ $item->nama_tas }}</td>
                            <td>
                                <span class="badge badge-stok d-block text-center" style="
                                    background-color: {{ $item->warna_tas == 'Hitam' ? '#000' : 
                                                      ($item->warna_tas == 'Merah' ? '#dc3545' : 
                                                      ($item->warna_tas == 'Biru' ? '#0d6efd' : 
                                                      ($item->warna_tas == 'Hijau' ? '#28a745' :
                                                      ($item->warna_tas == 'Kuning' ? '#ffc107' :
                                                      ($item->warna_tas == 'Ungu' ? '#6f42c1' :
                                                      ($item->warna_tas == 'Pink' ? '#e83e8c' :
                                                      ($item->warna_tas == 'Navy' ? '#001f3f' :
                                                      ($item->warna_tas == 'Maroon' ? '#85144b' : '#6c757d')))))))) }};
                                    color: {{ in_array($item->warna_tas, ['Kuning', 'Putih']) ? '#000' : 'white' }};
                                    padding: 8px 12px;
                                    margin: 2px 0;
                                ">
                                    <i class="bi bi-circle-fill me-1"></i> {{ $item->warna_tas }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary" style="font-size: 0.8em;">
                                        <i class="bi bi-box"></i> {{ $item->stok }} pcs
                                    </span>
                                    @if($item->stok == 0)
                                        <span class="badge bg-danger ms-1"><i class="bi bi-x-circle"></i> Habis</span>
                                    @endif
                                </div>
                            </td>
                            <td><i class="bi bi-currency-dollar text-success me-1"></i> {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>
                                <strong class="text-success">
                                    <i class="bi bi-cash-stack"></i> Rp {{ number_format($item->stok * $item->harga, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                @if($item->stok > 10)
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aman</span>
                                @elseif($item->stok > 0)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Menipis</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Kritis</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('tas.show', $item->id) }}" class="btn btn-info" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('tas.edit', $item->id) }}" class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('tas.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Hapus data tas ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- Spacer untuk jarak antar item -->
                        @if(!$loop->last)
                        <tr style="height: 15px; background-color: transparent;">
                            <td colspan="10"></td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    <i class="bi bi-list-check"></i> Menampilkan {{ $tas->firstItem() }} - {{ $tas->lastItem() }} dari {{ $tas->total() }} item
                </div>
                <div>
                    {{ $tas->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<style>
    .kode-container {
        border-left: 4px solid #3498db !important;
        font-family: 'Courier New', monospace;
    }
    .table > tbody > tr > td {
        vertical-align: middle;
    }
    .btn-group .btn {
        border-radius: 4px !important;
        margin: 0 2px;
    }
</style>
@endsection