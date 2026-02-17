<!-- resources/views/tas/create.blade.php -->
@extends('layouts.app')

@section('title', 'Tambah Tas Baru')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i> Tambah Tas Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('tas.store') }}" method="POST" id="tasForm">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="kode_tas" class="form-label">
                                <i class="bi bi-upc-scan me-1"></i> Kode Tas *
                            </label>
                            <input type="text" class="form-control @error('kode_tas') is-invalid @enderror" 
                                   id="kode_tas" name="kode_tas" value="{{ old('kode_tas') }}" 
                                   placeholder="TAS001" required>
                            @error('kode_tas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="model_tas" class="form-label">
                                <i class="bi bi-tags me-1"></i> Model Tas *
                            </label>
                            <input type="text" class="form-control @error('model_tas') is-invalid @enderror" 
                                   id="model_tas" name="model_tas" value="{{ old('model_tas') }}" 
                                   placeholder="Backpack" required>
                            @error('model_tas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="nama_tas" class="form-label">
                                <i class="bi bi-bag me-1"></i> Nama Tas *
                            </label>
                            <input type="text" class="form-control @error('nama_tas') is-invalid @enderror" 
                                   id="nama_tas" name="nama_tas" value="{{ old('nama_tas') }}" 
                                   placeholder="Tas Sekolah" required>
                            @error('nama_tas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Harga -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="harga" class="form-label">
                                <i class="bi bi-currency-dollar me-1"></i> Harga per Item (Rp) *
                            </label>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                   id="harga" name="harga" value="{{ old('harga', 0) }}" 
                                   min="0" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Warna dan Stok (Multiple) -->
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="bi bi-palette me-2"></i> Warna dan Stok</h5>
                            <small class="text-white-50">Tambahkan minimal satu warna untuk tas ini</small>
                        </div>
                        <div class="card-body">
                            <div id="warna-container">
                                <!-- Warna pertama -->
                                <div class="row mb-3 warna-item">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-circle-fill me-1"></i> Warna *</label>
                                        <select class="form-select warna-select" name="warna_tas[]" required>
                                            <option value="">Pilih Warna</option>
                                            <option value="Hitam">Hitam</option>
                                            <option value="Putih">Putih</option>
                                            <option value="Merah">Merah</option>
                                            <option value="Biru">Biru</option>
                                            <option value="Hijau">Hijau</option>
                                            <option value="Coklat">Coklat</option>
                                            <option value="Abu-abu">Abu-abu</option>
                                            <option value="Kuning">Kuning</option>
                                            <option value="Ungu">Ungu</option>
                                            <option value="Pink">Pink</option>
                                            <option value="Navy">Navy</option>
                                            <option value="Maroon">Maroon</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-box me-1"></i> Stok Awal *</label>
                                        <input type="number" class="form-control stok-input" 
                                               name="stok[]" value="0" min="0" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm hapus-warna" disabled>
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="tambah-warna" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Warna Lain
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Data Tas
                        </button>
                        <a href="{{ route('tas.index') }}" class="btn btn-secondary px-4">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const warnaContainer = document.getElementById('warna-container');
    const tambahWarnaBtn = document.getElementById('tambah-warna');
    
    // Template untuk warna baru
    const warnaTemplate = `
        <div class="row mb-3 warna-item">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-circle-fill me-1"></i> Warna *</label>
                <select class="form-select warna-select" name="warna_tas[]" required>
                    <option value="">Pilih Warna</option>
                    <option value="Hitam">Hitam</option>
                    <option value="Putih">Putih</option>
                    <option value="Merah">Merah</option>
                    <option value="Biru">Biru</option>
                    <option value="Hijau">Hijau</option>
                    <option value="Coklat">Coklat</option>
                    <option value="Abu-abu">Abu-abu</option>
                    <option value="Kuning">Kuning</option>
                    <option value="Ungu">Ungu</option>
                    <option value="Pink">Pink</option>
                    <option value="Navy">Navy</option>
                    <option value="Maroon">Maroon</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-box me-1"></i> Stok Awal *</label>
                <input type="number" class="form-control stok-input" 
                       name="stok[]" value="0" min="0" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm hapus-warna">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>
        </div>
    `;
    
    // Tambah warna baru
    tambahWarnaBtn.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.innerHTML = warnaTemplate;
        warnaContainer.appendChild(newItem);
        
        // Aktifkan tombol hapus untuk semua item
        updateHapusButtons();
    });
    
    // Update tombol hapus
    function updateHapusButtons() {
        const warnaItems = document.querySelectorAll('.warna-item');
        const hapusButtons = document.querySelectorAll('.hapus-warna');
        
        // Jika hanya ada satu item, nonaktifkan tombol hapus
        if (warnaItems.length === 1) {
            hapusButtons[0].disabled = true;
        } else {
            hapusButtons.forEach(button => {
                button.disabled = false;
            });
        }
        
        // Tambahkan event listener untuk tombol hapus
        hapusButtons.forEach(button => {
            button.onclick = function() {
                if (warnaItems.length > 1) {
                    this.closest('.warna-item').remove();
                    updateHapusButtons();
                }
            };
        });
    }
    
    // Inisialisasi tombol hapus
    updateHapusButtons();
    
    // Validasi form sebelum submit
    document.getElementById('tasForm').addEventListener('submit', function(e) {
        const warnaSelects = document.querySelectorAll('.warna-select');
        const stokInputs = document.querySelectorAll('.stok-input');
        let isValid = true;
        
        // Validasi setiap warna dan stok
        for (let i = 0; i < warnaSelects.length; i++) {
            if (!warnaSelects[i].value) {
                alert('Semua warna harus dipilih!');
                isValid = false;
                break;
            }
            if (stokInputs[i].value < 0) {
                alert('Stok tidak boleh negatif!');
                isValid = false;
                break;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
@endsection