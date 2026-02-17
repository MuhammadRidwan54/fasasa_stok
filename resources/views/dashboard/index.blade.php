@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2><i class="bi bi-speedometer2 me-2"></i> Dashboard Stok Tas</h2>
        <p class="text-muted">Overview manajemen stok dan penjualan harian</p>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body stat-card">
                <i class="bi bi-box-seam text-primary fs-1 mb-3"></i>
                <div class="number text-primary">{{ number_format($totalStok) }}</div>
                <div class="label">Total Stok</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body stat-card">
                <i class="bi bi-tags text-success fs-1 mb-3"></i>
                <div class="number text-success">{{ $totalModel }}</div>
                <div class="label">Model Tas</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body stat-card">
                <i class="bi bi-currency-dollar text-warning fs-1 mb-3"></i>
                <div class="number text-warning">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</div>
                <div class="label">Nilai Stok</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-body stat-card">
                <i class="bi bi-exclamation-triangle text-danger fs-1 mb-3"></i>
                <div class="number text-danger">{{ $stokKritis }}</div>
                <div class="label">Stok Kritis</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i> Penjualan 7 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="penjualanChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-palette me-2"></i> Stok Berdasarkan Warna</h5>
            </div>
            <div class="card-body">
                <canvas id="warnaChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Stok Terbanyak -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-trophy me-2"></i> 5 Tas dengan Stok Terbanyak</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th width="120">Kode Tas</th>
                                <th width="150">Nama Tas</th>
                                <th width="100">Model</th>
                                <th width="100">Warna</th>
                                <th width="100">Stok</th>
                                <th width="120">Harga</th>
                                <th width="120">Total Nilai</th>
                                <th width="100">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasTerbanyak as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <strong><i class="bi bi-upc-scan text-primary"></i> {{ $item->kode_tas }}</strong>
                                </td>
                                <td>{{ $item->nama_tas }}</td>
                                <td>{{ $item->model_tas }}</td>
                                <td>
                                    <span class="badge badge-stok" style="background-color: {{ $item->warna_tas == 'Hitam' ? '#000' : ($item->warna_tas == 'Merah' ? '#dc3545' : ($item->warna_tas == 'Biru' ? '#0d6efd' : '#6c757d')) }}; color: white;">
                                        <i class="bi bi-circle-fill me-1"></i> {{ $item->warna_tas }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="bi bi-box"></i> {{ $item->stok }} pcs
                                    </span>
                                </td>
                                <td><i class="bi bi-currency-dollar text-success"></i> {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>
                                    <strong class="text-success">
                                        <i class="bi bi-currency-dollar"></i> {{ number_format($item->stok * $item->harga, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    @if($item->stok > 20)
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aman</span>
                                    @elseif($item->stok > 5)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> Menipis</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Kritis</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Chart Penjualan
    const penjualanCtx = document.getElementById('penjualanChart').getContext('2d');
    const penjualanChart = new Chart(penjualanCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($penjualanHarian->pluck('date')->map(fn($date) => date('d M', strtotime($date)))) !!},
            datasets: [{
                label: 'Jumlah Terjual',
                data: {!! json_encode($penjualanHarian->pluck('total')) !!},
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' pcs';
                        }
                    }
                }
            }
        }
    });

    // Chart Warna
    const warnaCtx = document.getElementById('warnaChart').getContext('2d');
    const warnaChart = new Chart(warnaCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($stokByWarna->pluck('warna_tas')) !!},
            datasets: [{
                data: {!! json_encode($stokByWarna->pluck('total')) !!},
                backgroundColor: [
                    '#2c3e50', '#3498db', '#e74c3c', '#2ecc71', 
                    '#f39c12', '#9b59b6', '#1abc9c', '#d35400'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endsection
@endsection