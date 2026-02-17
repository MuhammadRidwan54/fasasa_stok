<!-- resources/views/tas/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Tas')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">👁️ Detail Tas</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informasi Tas</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Kode Tas</th>
                                <td><strong>{{ $ta->kode_tas }}</strong></td>
                            </tr>
                            <tr>
                                <th>Model Tas</th>
                                <td>{{ $ta->model_tas }}</td>
                            </tr>
                            <tr>
                                <th>Nama Tas</th>
                                <td>{{ $ta->nama_tas }}</td>
                            </tr>
                            <tr>
                                <th>Warna Tas</th>
                                <td>
                                    <span class="badge" style="background-color: {{ $ta->warna_tas == 'Hitam' ? '#000' : ($ta->warna_tas == 'Merah' ? '#dc3545' : ($ta->warna_tas == 'Biru' ? '#0d6efd' : '#6c757d')) }}; color: white; padding: 5px 10px;">
                                        {{ $ta->warna_tas }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Stok Tersedia</th>
                                <td>
                                    @if($ta->stok > 10)
                                        <span class="badge bg-success" style="font-size: 1em; padding: 8px 15px;">
                                            {{ $ta->stok }} pcs
                                        </span>
                                    @elseif($ta->stok > 0)
                                        <span class="badge bg-warning text-dark" style="font-size: 1em; padding: 8px 15px;">
                                            {{ $ta->stok }} pcs (Hampir Habis)
                                        </span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 1em; padding: 8px 15px;">
                                            Stok Habis
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Status Stok</h5>
                        <div class="alert @if($ta->stok > 10) alert-success @elseif($ta->stok > 0) alert-warning @else alert-danger @endif">
                            <h6>Status:</h6>
                            @if($ta->stok > 10)
                                <p>✅ Stok tersedia dengan jumlah cukup</p>
                            @elseif($ta->stok > 0)
                                <p>⚠️ Stok menipis, perlu restock</p>
                            @else
                                <p>❌ Stok habis, segera restock</p>
                            @endif
                        </div>
                        
                        <h5>Aksi</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('tas.edit', $ta->id) }}" class="btn btn-warning">✏️ Edit Data</a>
                            <a href="{{ route('tas.index') }}" class="btn btn-secondary">📋 Kembali ke Daftar</a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h5>Riwayat</h5>
                    <p>Dibuat pada: {{ $ta->created_at->format('d-m-Y H:i') }}</p>
                    <p>Terakhir diupdate: {{ $ta->updated_at->format('d-m-Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection