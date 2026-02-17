<!-- resources/views/laporan/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="bi bi-eye me-2"></i> Detail Laporan</h4>
            </div>
            <div class="card-body">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('laporan.index') }}">
                                <i class="bi bi-clipboard-data"></i> Laporan
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <i class="bi bi-receipt"></i> Detail
                        </li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="bi bi-info-circle me-2"></i> Informasi Transaksi</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%" class="bg-light">
                                    <i class="bi bi-calendar3 me-1"></i> Tanggal
                                </th>
                                <td>{{ $laporan->tanggal->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="bi bi-upc-scan me-1"></i> Kode Tas
                                </th>
                                <td>
                                    <strong class="text-primary">{{ $laporan->tas->kode_tas }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="bi bi-bag me-1"></i> Nama Tas
                                </th>
                                <td>{{ $laporan->tas->nama_tas }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="bi bi-palette me-1"></i> Warna
                                </th>
                                <td>
                                    <span class="badge badge-stok" style="
                                        background-color: {{ $laporan->tas->warna_tas == 'Hitam' ? '#000' : 
                                                          ($laporan->tas->warna_tas == 'Merah' ? '#dc3545' : 
                                                          ($laporan->tas->warna_tas == 'Biru' ? '#0d6efd' : '#6c757d')) }};
                                        color: white;
                                        padding: 6px 12px;
                                    ">
                                        {{ $laporan->tas->warna_tas }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="bi bi-cart me-1"></i> Jumlah Terjual
                                </th>
                                <td>
                                    <span class="badge bg-primary">
                                        <i class="bi bi-cart"></i> {{ $laporan->jumlah_terjual }} pcs
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h5><i class="bi bi-graph-up me-2"></i> Detail Harga & Platform</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%" class="bg-light">
                                    <i class="bi bi-shop me-1"></i> Platform
                                </th>
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
                                            'tiktok' => 'TikTok Shop',
                                            'offline' => 'Offline Store',
                                            'lainnya' => 'Lainnya'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $platformColors[$laporan->platform] ?? 'secondary' }}">
                                        <i class="bi {{ $platformIcons[$laporan->platform] ?? 'bi-question-circle' }} me-1"></i>
                                        {{ $platformNames[$laporan->platform] ?? $laporan->platform }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="bi bi-currency-dollar me-1"></i> Harga Satuan
                                </th>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($laporan->tas->harga, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="bi bi-cash-stack me-1"></i> Total Harga
                                </th>
                                <td>
                                    <strong class="text-success">
                                        Rp {{ number_format($laporan->jumlah_terjual * $laporan->tas->harga, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">
                                    <i class="bi bi-box me-1"></i> Sisa Stok Setelah Transaksi
                                </th>
                                <td>
                                    <span class="badge @if($laporan->sisa_stok > 10) bg-success @elseif($laporan->sisa_stok > 0) bg-warning text-dark @else bg-danger @endif">
                                        <i class="bi bi-box"></i> {{ $laporan->sisa_stok }} pcs
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Keterangan -->
                @if($laporan->keterangan)
                <div class="mt-4">
                    <h5><i class="bi bi-chat-left-text me-2"></i> Keterangan</h5>
                    <div class="card">
                        <div class="card-body bg-light">
                            <i class="bi bi-quote text-muted me-2"></i>
                            {{ $laporan->keterangan }}
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Timestamp -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="bi bi-clock-history me-1"></i> Dibuat: {{ $laporan->created_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="bi bi-arrow-clockwise me-1"></i> Diupdate: {{ $laporan->updated_at->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                        </div>
                        <div class="btn-group">
                            <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Hapus laporan ini? Stok akan dikembalikan.')">
                                    <i class="bi bi-trash me-1"></i> Hapus Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection