@extends('layouts.app')

@section('title', 'Detail Laporan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Laporan</h1>
        <p class="page-subtitle">Informasi lengkap transaksi penjualan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.edit', $laporan->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-receipt me-1"></i> Informasi Transaksi
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            {{ $laporan->tanggal->format('d F Y') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Platform</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            @switch($laporan->platform)
                                @case('shopee')
                                    <span class="badge" style="background-color: #ee4d2d; color: white; padding: 4px 8px;">
                                        <i class="bi bi-shop me-1"></i> Shopee
                                    </span>
                                    @break
                                @case('tiktok')
                                    <span class="badge" style="background-color: #000; color: white; padding: 4px 8px;">
                                        <i class="bi bi-tiktok me-1"></i> TikTok Shop
                                    </span>
                                    @break
                                @case('offline')
                                    <span class="badge bg-primary" style="padding: 4px 8px;">
                                        <i class="bi bi-shop-window me-1"></i> Offline/Endorse
                                    </span>
                                    @break
                                @case('affiliate')
                                    <span class="badge bg-success" style="padding: 4px 8px;">
                                        <i class="bi bi-link me-1"></i> Affiliate
                                    </span>
                                @default
                                    <span class="badge bg-secondary" style="padding: 4px 8px;">
                                        <i class="bi bi-three-dots me-1"></i> Lainnya
                                    </span>
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Tas</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px; border-left: 3px solid #000;">
                            <strong>{{ $laporan->tas->kode_tas }}#</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Tas</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            {{ $laporan->tas->nama_tas }}
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Warna</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            @php
                                $warnaColors = [
                                    'Black' => ['bg' => '#000000', 'text' => '#ffffff'],
                                    'Brown' => ['bg' => '#8B4513', 'text' => '#ffffff'],
                                    'Coffee' => ['bg' => '#D2691E', 'text' => '#ffffff'],
                                    'Cream' => ['bg' => '#FFFDD0', 'text' => '#000000'],
                                    'Maroon' => ['bg' => '#800000', 'text' => '#ffffff'],
                                    'Khaki' => ['bg' => '#f0e68c', 'text' => '#000000'],
                                    'Caramel' => ['bg' => '#AF6E4D', 'text' => '#ffffff'],
                                    '' => ['bg' => '#ffffff', 'text' => '#000000'],
                                    'Merah' => ['bg' => '#e74c3c', 'text' => '#ffffff'],
                                    'Biru' => ['bg' => '#3498db', 'text' => '#ffffff'],
                                    'Hijau' => ['bg' => '#27ae60', 'text' => '#ffffff'],
                                    'Kuning' => ['bg' => '#f39c12', 'text' => '#000000'],
                                    'Ungu' => ['bg' => '#8e44ad', 'text' => '#ffffff'],
                                    'Pink' => ['bg' => '#e91e63', 'text' => '#ffffff'],
                                    'Navy' => ['bg' => '#1a3a52', 'text' => '#ffffff'],
                                    'Abu-abu' => ['bg' => '#95a5a6', 'text' => '#000000'],
                                    'Coklat' => ['bg' => '#A0522D', 'text' => '#ffffff'],
                                    'Hitam' => ['bg' => '#333333', 'text' => '#ffffff']
                                ];
                                
                                $warna = $laporan->warna ?? $laporan->tas->warna_tas;
                                $color = $warnaColors[$warna] ?? ['bg' => '#f8f9fa', 'text' => '#000000'];
                                
                                // Hitung stok per warna
                                $stokPerWarna = 0;
                                $stokSetelahTransaksi = 0;
                                if ($laporan->tas && $laporan->tas->stokMasuk) {
                                    // Stok awal untuk warna ini
                                    $stokPerWarna = $laporan->tas->stokMasuk
                                        ->where('warna', $warna)
                                        ->sum('jumlah');
                                        
                                    // Stok setelah transaksi untuk warna ini
                                    $stokSetelahTransaksi = $stokPerWarna;
                                }
                            @endphp
                            <span class="badge" style="
                                background-color: {{ $color['bg'] }};
                                color: {{ $color['text'] }};
                                padding: 4px 8px;
                                border-radius: 4px;
                                font-size: 12px;
                                border: 1px solid #dee2e6;
                            ">
                                {{ $warna }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah Terjual</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            <strong>{{ $laporan->jumlah_terjual }} pcs</strong>
                        </div>
                    </div>
                </div>

                <hr style="margin: 16px 0; border-color: #e5e5e5;">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Harga Satuan</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            Rp {{ number_format($laporan->tas->harga, 0, ',', '.') }},00
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total Harga</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            <strong style="font-size: 14px; color: #000;">
                                Rp {{ number_format($laporan->jumlah_terjual * $laporan->tas->harga, 0, ',', '.') }},00
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Sisa Stok Setelah Transaksi</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            @if($laporan->sisa_stok > 10)
                                <span class="badge badge-success">{{ $laporan->sisa_stok }} pcs</span>
                            @elseif($laporan->sisa_stok > 0)
                                <span class="badge badge-warning">{{ $laporan->sisa_stok }} pcs</span>
                            @else
                                <span class="badge badge-danger">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($laporan->keterangan)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Keterangan</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px; font-size: 12px; line-height: 1.6;">
                            {{ $laporan->keterangan }}
                        </div>
                    </div>
                </div>
                @endif

                <div style="padding-top: 12px; border-top: 1px solid #e5e5e5; margin-top: 16px;">
                    <p style="font-size: 11px; color: #999; margin-bottom: 4px;">
                        <i class="bi bi-clock-history"></i> Dibuat: {{ $laporan->created_at->format('d M Y H:i') }}
                    </p>
                    <p style="font-size: 11px; color: #999; margin: 0;">
                        <i class="bi bi-arrow-clockwise"></i> Diupdate: {{ $laporan->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-receipt-cutoff me-1"></i> Ringkasan
            </div>
            <div class="card-body">
                <div style="text-align: center;">
                    <div style="font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 8px;">Nilai Transaksi</div>
                    <div style="font-size: 22px; font-weight: 600; color: #000; margin-bottom: 16px;">
                        Rp {{ number_format($laporan->jumlah_terjual * $laporan->tas->harga, 0, ',', '.') }},00
                    </div>
                    <div style="padding: 12px; background-color: #f5f5f5; border-radius: 6px; font-size: 12px; line-height: 1.8;">
                        <div><strong>{{ $laporan->jumlah_terjual }} pcs</strong> × Rp {{ number_format($laporan->tas->harga, 0, ',', '.') }},00</div>
                        <div style="margin-top: 4px; font-size: 11px; color: #666;">
                            <i class="bi bi-palette"></i> Warna: {{ $laporan->warna ?? $laporan->tas->warna_tas }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-seam me-1"></i> Informasi Stok (Per Warna)
            </div>
            <div class="card-body" style="font-size: 12px; line-height: 1.8;">
                @php
                    $warna = $laporan->warna ?? $laporan->tas->warna_tas;
                @endphp
                <p><strong>Warna: {{ $warna }}</strong></p>
                <ul style="padding-left: 16px; margin-bottom: 12px;">
                    <li>Stok awal: <strong>{{ $stokPerWarna }} pcs</strong></li>
                    <li>Setelah transaksi: <strong>{{ $stokSetelahTransaksi }} pcs</strong></li>
                </ul>
                <p><strong>Pengurangan Stok:</strong> {{ $laporan->jumlah_terjual }} pcs</p>
                
                @if($stokPerWarna == 0)
                    <div style="margin-top: 8px; padding: 8px; background-color: #fff3cd; border-radius: 4px; font-size: 11px;">
                        <i class="bi bi-exclamation-triangle"></i> Stok untuk warna ini sudah habis
                    </div>
                @elseif($stokSetelahTransaksi == 0)
                    <div style="margin-top: 8px; padding: 8px; background-color: #f8d7da; border-radius: 4px; font-size: 11px;">
                        <i class="bi bi-exclamation-triangle"></i> Stok habis setelah transaksi ini
                    </div>
                @elseif($stokSetelahTransaksi < 5)
                    <div style="margin-top: 8px; padding: 8px; background-color: #fff3cd; border-radius: 4px; font-size: 11px;">
                        <i class="bi bi-exclamation-triangle"></i> Stok menipis untuk warna ini
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear me-1"></i> Aksi
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 8px;">
                <!-- Return Stock Button -->
                <button type="button" class="btn btn-info" style="width: 100%;" data-bs-toggle="modal" data-bs-target="#returnModal">
                    <i class="bi bi-arrow-return-left me-1"></i> Return Stok
                </button>
                
                <!-- Delete Form -->
                <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;" 
                            onclick="return confirm('Hapus laporan ini? Stok akan dikembalikan.')">
                        <i class="bi bi-trash me-1"></i> Hapus Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Return Stock Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('laporan.return-stok', $laporan->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">
                        <i class="bi bi-arrow-return-left me-2"></i>Return Stok
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Informasi Transaksi</label>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-1"><strong>Nama Tas:</strong> {{ $laporan->tas->nama_tas }}</p>
                            <p class="mb-1"><strong>Warna:</strong> {{ $laporan->warna }}</p>
                            <p class="mb-0"><strong>Jumlah Return:</strong> {{ $laporan->jumlah_terjual }} pcs</p>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small>Stok akan dikembalikan ke gudang untuk warna <strong>{{ $laporan->warna }}</strong>. 
                        Stok total tas akan bertambah {{ $laporan->jumlah_terjual }} pcs.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-check-circle me-1"></i> Konfirmasi Return
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .badge-success {
        background-color: #d4edda;
        color: #155724;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .badge-warning {
        background-color: #fff3cd;
        color: #856404;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .badge-danger {
        background-color: #f8d7da;
        color: #721c24;
        padding: 4px 8px;
        border-radius: 4px;
    }
</style>
@endsection