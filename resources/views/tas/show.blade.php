@extends('layouts.app')

@section('title', 'Detail Tas')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Tas</h1>
        <p class="page-subtitle">Informasi lengkap data tas</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-1"></i> Informasi Tas
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Tas</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px; border-left: 3px solid #000;">
                            <strong>{{ $ta->kode_tas }}#</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Model Tas</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            {{ $ta->model_tas }}
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Tas</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            {{ $ta->nama_tas }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Warna</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            <span class="badge" style="
                                background-color: {{ $ta->warna_tas == 'Hitam' ? '#333' : 
                                                  ($ta->warna_tas == 'Merah' ? '#e74c3c' : 
                                                  ($ta->warna_tas == 'Biru' ? '#3498db' : 
                                                  ($ta->warna_tas == 'Hijau' ? '#27ae60' :
                                                  ($ta->warna_tas == 'Kuning' ? '#f39c12' :
                                                  ($ta->warna_tas == 'Ungu' ? '#8e44ad' :
                                                  ($ta->warna_tas == 'Pink' ? '#e91e63' :
                                                  ($ta->warna_tas == 'Navy' ? '#1a3a52' :
                                                  ($ta->warna_tas == 'Maroon' ? '#85144b' : '#95a5a6')))))))) }};
                                color: {{ in_array($ta->warna_tas, ['Kuning', 'Putih']) ? '#000' : 'white' }};
                            ">
                                {{ $ta->warna_tas }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Harga</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            <strong>Rp {{ number_format($ta->harga, 0, ',', '.') }},00</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stok</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            @if($ta->stok > 10)
                                <span class="badge badge-success">{{ $ta->stok }} pcs</span>
                            @elseif($ta->stok > 0)
                                <span class="badge badge-warning">{{ $ta->stok }} pcs</span>
                            @else
                                <span class="badge badge-danger">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Total Nilai Stok</label>
                        <div style="padding: 10px; background-color: #f5f5f5; border-radius: 6px;">
                            <strong style="font-size: 16px;">Rp {{ number_format($ta->stok * $ta->harga, 0, ',', '.') }},00</strong>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding-top: 12px; border-top: 1px solid #e5e5e5; margin-top: 16px;">
                    <div class="col-12">
                        <p style="font-size: 11px; color: #999; margin-bottom: 8px;">
                            <i class="bi bi-clock-history"></i> Dibuat: {{ $ta->created_at->format('d M Y H:i') }}
                        </p>
                        <p style="font-size: 11px; color: #999;">
                            <i class="bi bi-arrow-clockwise"></i> Diupdate: {{ $ta->updated_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tambahan: Riwayat Stok Masuk -->
        @if(isset($ta->stokMasuk) && count($ta->stokMasuk) > 0)
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-calendar-check me-1"></i> Riwayat Stok Masuk
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th width="30">No</th>
                                <th width="100">Warna</th>
                                <th width="100">Tanggal Masuk</th>
                                <th width="80">Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ta->stokMasuk as $index => $stokMasuk)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        $warnaColors = [
                                            'Black' => ['bg' => '#000000', 'text' => '#ffffff'],
                                            'Brown' => ['bg' => '#8B4513', 'text' => '#ffffff'],
                                            'Coffee' => ['bg' => '#D2691E', 'text' => '#ffffff'],
                                            'Cream' => ['bg' => '#FFFDD0', 'text' => '#000000'],
                                            'Maroon' => ['bg' => '#800000', 'text' => '#ffffff'],
                                            'Khaki' => ['bg' => '#f0e68c', 'text' => '#000000'],
                                            'Caramel' => ['bg' => '#AF6E4D', 'text' => '#ffffff'],
                                            'Merah' => ['bg' => '#e74c3c', 'text' => '#ffffff'],
                                            'Biru' => ['bg' => '#3498db', 'text' => '#ffffff'],
                                            'Hijau' => ['bg' => '#27ae60', 'text' => '#ffffff'],
                                            'Kuning' => ['bg' => '#f39c12', 'text' => '#000000'],
                                            'Ungu' => ['bg' => '#8e44ad', 'text' => '#ffffff'],
                                            'Pink' => ['bg' => '#e91e63', 'text' => '#ffffff'],
                                            'Navy' => ['bg' => '#1a3a52', 'text' => '#ffffff'],
                                            'Abu-abu' => ['bg' => '#95a5a6', 'text' => '#000000'],
                                            'Coklat' => ['bg' => '#A0522D', 'text' => '#ffffff']
                                        ];
                                        
                                        $color = $warnaColors[$stokMasuk->warna] ?? ['bg' => '#f8f9fa', 'text' => '#000000'];
                                    @endphp
                                    <span class="badge" style="
                                        background-color: {{ $color['bg'] }};
                                        color: {{ $color['text'] }};
                                        border: 1px solid #dee2e6;
                                        padding: 4px 8px;
                                        border-radius: 4px;
                                        font-size: 12px;
                                    ">
                                        {{ $stokMasuk->warna }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($stokMasuk->tanggal_masuk)->format('d M Y') }}</td>
                                <td>{{ $stokMasuk->jumlah }} pcs</td>
                                <td>{{ $stokMasuk->keterangan ?? 'Stok awal' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-calendar-check me-1"></i> Riwayat Stok Masuk
            </div>
            <div class="card-body" style="text-align: center; padding: 40px 20px; color: #999;">
                <i class="bi bi-inbox" style="font-size: 28px; display: block; margin-bottom: 8px;"></i>
                <p>Belum ada riwayat stok masuk</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-exclamation-triangle me-1"></i> Status Stok
            </div>
            <div class="card-body">
                @if($ta->stok > 10)
                    <div style="text-align: center; padding: 20px;">
                        <i class="bi bi-check-circle" style="font-size: 28px; color: #27ae60; display: block; margin-bottom: 8px;"></i>
                        <p style="font-weight: 600; color: #27ae60;">Stok Aman</p>
                        <p style="font-size: 12px; color: #666;">Stok tersedia dengan jumlah cukup</p>
                    </div>
                @elseif($ta->stok < 10 && $ta->stok > 0)
                    <div style="text-align: center; padding: 20px; background-color: #fffbf0; border-radius: 6px;">
                        <i class="bi bi-exclamation-triangle" style="font-size: 28px; color: #f39c12; display: block; margin-bottom: 8px;"></i>
                        <p style="font-weight: 600; color: #f39c12;">Stok Menipis</p>
                        <p style="font-size: 12px; color: #666;">Perlu dilakukan restock segera</p>
                    </div>
                @else
                    <div style="text-align: center; padding: 20px; background-color: #fff5f5; border-radius: 6px;">
                        <i class="bi bi-x-circle" style="font-size: 28px; color: #e74c3c; display: block; margin-bottom: 8px;"></i>
                        <p style="font-weight: 600; color: #e74c3c;">Stok Habis</p>
                        <p style="font-size: 12px; color: #666;">Segera lakukan restock</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear me-1"></i> Aksi
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('tas.edit', $ta->id) }}" class="btn btn-warning" style="width: 100%;">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="{{ route('tas.index') }}" class="btn btn-secondary" style="width: 100%;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection