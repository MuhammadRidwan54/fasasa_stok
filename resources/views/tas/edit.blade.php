<!-- resources/views/tas/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Data Tas')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="mb-0">✏️ Edit Data Tas</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('tas.update', $ta->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kode_tas" class="form-label">Kode Tas *</label>
                            <input type="text" class="form-control @error('kode_tas') is-invalid @enderror" 
                                   id="kode_tas" name="kode_tas" value="{{ old('kode_tas', $ta->kode_tas) }}" 
                                   placeholder="Contoh: TAS001" required>
                            @error('kode_tas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="model_tas" class="form-label">Model Tas *</label>
                            <input type="text" class="form-control @error('model_tas') is-invalid @enderror" 
                                   id="model_tas" name="model_tas" value="{{ old('model_tas', $ta->model_tas) }}" 
                                   placeholder="Contoh: Backpack" required>
                            @error('model_tas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_tas" class="form-label">Nama Tas *</label>
                            <input type="text" class="form-control @error('nama_tas') is-invalid @enderror" 
                                   id="nama_tas" name="nama_tas" value="{{ old('nama_tas', $ta->nama_tas) }}" 
                                   placeholder="Contoh: Tas Sekolah" required>
                            @error('nama_tas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="warna_tas" class="form-label">Warna Tas *</label>
                            <select class="form-select @error('warna_tas') is-invalid @enderror" 
                                    id="warna_tas" name="warna_tas" required>
                                <option value="">Pilih Warna</option>
                                <option value="Hitam" {{ old('warna_tas', $ta->warna_tas) == 'Hitam' ? 'selected' : '' }}>Hitam</option>
                                <option value="Putih" {{ old('warna_tas', $ta->warna_tas) == 'Putih' ? 'selected' : '' }}>Putih</option>
                                <option value="Merah" {{ old('warna_tas', $ta->warna_tas) == 'Merah' ? 'selected' : '' }}>Merah</option>
                                <option value="Biru" {{ old('warna_tas', $ta->warna_tas) == 'Biru' ? 'selected' : '' }}>Biru</option>
                                <option value="Hijau" {{ old('warna_tas', $ta->warna_tas) == 'Hijau' ? 'selected' : '' }}>Hijau</option>
                                <option value="Coklat" {{ old('warna_tas', $ta->warna_tas) == 'Coklat' ? 'selected' : '' }}>Coklat</option>
                                <option value="Abu-abu" {{ old('warna_tas', $ta->warna_tas) == 'Abu-abu' ? 'selected' : '' }}>Abu-abu</option>
                                <option value="Kuning" {{ old('warna_tas', $ta->warna_tas) == 'Kuning' ? 'selected' : '' }}>Kuning</option>
                            </select>
                            @error('warna_tas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="stok" class="form-label">Stok *</label>
                            <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                   id="stok" name="stok" value="{{ old('stok', $ta->stok) }}" 
                                   min="0" required>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Update jumlah stok untuk warna ini</small>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Data</button>
                        <a href="{{ route('tas.index') }}" class="btn btn-secondary">Batal</a>
                        <a href="{{ route('tas.show', $ta->id) }}" class="btn btn-info">Lihat Detail</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection