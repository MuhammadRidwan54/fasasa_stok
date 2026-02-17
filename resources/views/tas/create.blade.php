@extends('layouts.app')

@section('title', 'Tambah Tas Baru')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Tas Baru</h1>
        <p class="page-subtitle">Tambahkan data tas ke dalam sistem</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-1"></i> Form Input Tas
            </div>
            <div class="card-body">
                <form action="{{ route('tas.store') }}" method="POST" id="tasForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kode_tas" class="form-label">Kode #*</label>
                            <input type="text" class="form-control @error('kode_tas') is-invalid @enderror" 
                                id="kode_tas" name="kode_tas" value="{{ old('kode_tas') }}" 
                                placeholder="kode..." required>
                            @error('kode_tas')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="model_tas" class="form-label">Kategori*</label>
                            <select class="form-select @error('model_tas') is-invalid @enderror" 
                                    id="model_tas" name="model_tas" required>
                                <option value="">Pilih Model</option>
                                <option value="HOBO BAGS" {{ old('model_tas') == 'HOBO BAGS' ? 'selected' : '' }}>HOBO BAGS</option>
                                <option value="HANDBAGS" {{ old('model_tas') == 'HANDBAGS' ? 'selected' : '' }}>HANDBAGS</option>
                                <option value="TOTE BAGS" {{ old('model_tas') == 'TOTE BAGS' ? 'selected' : '' }}>TOTE BAGS</option>
                                <option value="SHOULDER BAGS" {{ old('model_tas') == 'SHOULDER BAGS' ? 'selected' : '' }}>SHOULDER BAGS</option>
                                <option value="BOSTON BAGS" {{ old('model_tas') == 'BOSTON BAGS' ? 'selected' : '' }}>BOSTON BAGS</option>
                                <option value="BUCKET BAGS" {{ old('model_tas') == 'BUCKET BAGS' ? 'selected' : '' }}>BUCKET BAGS</option>
                                <option value="SLOUCHE BAGS" {{ old('model_tas') == 'SLOUCHE BAGS' ? 'selected' : '' }}>SLOUCHE BAGS</option>
                            </select>
                            @error('model_tas')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_tas" class="form-label">Nama*</label>
                            <input type="text" class="form-control @error('nama_tas') is-invalid @enderror" 
                                id="nama_tas" name="nama_tas" value="{{ old('nama_tas') }}" 
                                placeholder="name..." required>
                            @error('nama_tas')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="harga" class="form-label">Harga *</label>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                id="harga" name="harga" value="{{ old('harga') }}"
                                    placeholder="Rp. " 
                                min="1" required>
                            @error('harga')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Warna, Stok, dan Tanggal Masuk -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="bi bi-palette me-1"></i> Warna, Stok, dan Tanggal Masuk
                            <small class="text-muted ms-2">Tambahkan minimal satu warna untuk tas ini</small>
                        </div>
                        <div class="card-body">
                            <div id="warna-container">
                                <!-- Warna pertama -->
                                <div class="row mb-3 warna-item">
                                    <div class="col-md-4">
                                        <label for="warna_tas_0" class="form-label">Warna *</label>
                                        <select class="form-select warna-select @error('warna_tas.0') is-invalid @enderror" 
                                                id="warna_tas_0" name="warna_tas[]" required>
                                            <option value="">Pilih Warna</option>
                                            <option value="Black" {{ old('warna_tas.0') == 'Black' ? 'selected' : '' }}>Black</option>
                                            <option value="Brown" {{ old('warna_tas.0') == 'Brown' ? 'selected' : '' }}>Brown</option>
                                            <option value="Coffee" {{ old('warna_tas.0') == 'Coffee' ? 'selected' : '' }}>Coffee (khaki)</option>
                                            <option value="Cream" {{ old('warna_tas.0') == 'Cream' ? 'selected' : '' }}>Cream (apricot)</option>
                                            <option value="Maroon" {{ old('warna_tas.0') == 'Maroon' ? 'selected' : '' }}>Maroon</option>
                                            <option value="Khaki" {{ old('warna_tas.0') == 'Khaki' ? 'selected' : '' }}>Khaki</option>
                                            <option value="Caramel" {{ old('warna_tas.0') == 'Caramel' ? 'selected' : '' }}>Caramel</option>
                                            <option value="Green" {{ old('warna_tas.0') == 'Green' ? 'selected' : '' }}>Green</option>
                                            <option value="Blue" {{ old('warna_tas.0') == 'Blue' ? 'selected' : '' }}>Blue</option>
                                            <option value="Yellow" {{ old('warna_tas.0') == 'Yellow' ? 'selected' : '' }}>Yellow</option>
                                            <option value="Grey" {{ old('warna_tas.0') == 'Grey' ? 'selected' : '' }}>Grey</option>
                                            <option value="Cherry red" {{ old('warna_tas.0') == 'Cherry red' ? 'selected' : '' }}>Cherry red</option>
                                        </select>
                                        @error('warna_tas.0')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="stok_0" class="form-label">Stok *</label>
                                        <input type="number" class="form-control stok-input @error('stok.0') is-invalid @enderror" 
                                            id="stok_0" name="stok[]" value="{{ old('stok.0') }}" min="0" required>
                                        @error('stok.0')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tanggal_masuk_0" class="form-label">Tanggal Masuk *</label>
                                        <input type="date" class="form-control tanggal-masuk @error('tanggal_masuk.0') is-invalid @enderror" 
                                            id="tanggal_masuk_0" name="tanggal_masuk[]" value="{{ old('tanggal_masuk.0', date('Y-m-d')) }}" required>
                                        @error('tanggal_masuk.0')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm hapus-warna" disabled>
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="tambah-warna" class="btn btn-outline-dark btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Warna Lain
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; padding-top: 12px; border-top: 1px solid #e5e5e5;">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan
                        </button>
                        <a href="{{ route('tas.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-1"></i> Informasi
            </div>
            <div class="card-body" style="font-size: 12px; line-height: 1.8; color: #666;">
                <p><strong>Panduan Pengisian:</strong></p>
                <ul style="margin-bottom: 12px; padding-left: 16px;">
                    <li>Kode Tas harus unik dan tidak boleh sama</li>
                    <li>Gunakan format yang konsisten untuk kode</li>
                    <li>Harga harus dalam Rupiah</li>
                    <li>Stok awal untuk tas baru</li>
                    <li>Tanggal masuk stok wajib diisi untuk setiap warna</li>
                </ul>
                <p><strong>Tip:</strong></p>
                <p>Pastikan semua data sudah benar sebelum menyimpan. Data yang sudah disimpan masih bisa diubah kemudian.</p>
                <p><em>Catatan: Anda bisa menambahkan beberapa warna dengan stok dan tanggal masuk masing-masing untuk tas ini.</em></p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const warnaContainer = document.getElementById('warna-container');
    const tambahWarnaBtn = document.getElementById('tambah-warna');
    let warnaCounter = 1;
    
    // Template untuk warna baru
    function getWarnaTemplate(index) {
        return `
            <div class="row mb-3 warna-item">
                <div class="col-md-4">
                    <label for="warna_tas_${index}" class="form-label">Warna *</label>
                    <select class="form-select warna-select" id="warna_tas_${index}" name="warna_tas[]" required>
                        <option value="">Pilih Warna</option>
                        <option value="Black">Black</option>
                        <option value="Brown">Brown</option>
                        <option value="Coffee">Coffee (khaki)</option>
                        <option value="Cream">Cream (apricot)</option>
                        <option value="Maroon">Maroon</option>
                        <option value="Khaki">Khaki</option>
                        <option value="Caramel">Caramel</option>
                        <option value="Green">Green</option>
                        <option value="Blue">Blue</option>
                        <option value="Yellow">Yellow</option>
                        <option value="Grey">Grey</option>
                        <option value="Cherry red">Cherry red</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="stok_${index}" class="form-label">Stok *</label>
                    <input type="number" class="form-control stok-input" 
                           id="stok_${index}" name="stok[]" value="0" min="0" required>
                </div>
                <div class="col-md-3">
                    <label for="tanggal_masuk_${index}" class="form-label">Tanggal Masuk *</label>
                    <input type="date" class="form-control tanggal-masuk" 
                           id="tanggal_masuk_${index}" name="tanggal_masuk[]" value="${new Date().toISOString().split('T')[0]}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm hapus-warna">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `;
    }
    
    // Tambah warna baru
    tambahWarnaBtn.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.innerHTML = getWarnaTemplate(warnaCounter);
        warnaContainer.appendChild(newItem);
        
        // Tingkatkan counter
        warnaCounter++;
        
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
        const tanggalInputs = document.querySelectorAll('.tanggal-masuk');
        let isValid = true;
        let warnaValues = new Set();
        
        // Validasi setiap warna, stok, dan tanggal
        for (let i = 0; i < warnaSelects.length; i++) {
            // Validasi warna
            if (!warnaSelects[i].value) {
                alert('Semua warna harus dipilih!');
                warnaSelects[i].focus();
                isValid = false;
                break;
            }
            
            // Cek duplikasi warna
            if (warnaValues.has(warnaSelects[i].value)) {
                alert('Warna tidak boleh duplikat! Setiap warna harus unik.');
                warnaSelects[i].focus();
                isValid = false;
                break;
            }
            warnaValues.add(warnaSelects[i].value);
            
            // Validasi stok
            if (stokInputs[i].value < 0) {
                alert('Stok tidak boleh negatif!');
                stokInputs[i].focus();
                isValid = false;
                break;
            }
            
            // Validasi tanggal
            if (!tanggalInputs[i].value) {
                alert('Tanggal masuk harus diisi!');
                tanggalInputs[i].focus();
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