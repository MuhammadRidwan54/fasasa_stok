@extends('layouts.app')

@section('title', 'Tambah Laporan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Laporan Penjualan</h1>
        <p class="page-subtitle">Catat penjualan/stok keluar</p>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-1"></i> Form Input Laporan
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.store') }}" method="POST" id="laporanForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">Tanggal *</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="platform" class="form-label">Platform *</label>
                            <select class="form-select @error('platform') is-invalid @enderror" 
                                    id="platform" name="platform" required>
                                <option value="">Pilih Platform</option>
                                <option value="shopee" {{ old('platform') == 'shopee' ? 'selected' : '' }}>Shopee</option>
                                <option value="tiktok" {{ old('platform') == 'tiktok' ? 'selected' : '' }}>TikTok Shop</option>
                                <option value="offline" {{ old('platform') == 'offline' ? 'selected' : '' }}>Offline/Endorse</option>
                                <option value="affiliate" {{ old('platform') == 'affiliate' ? 'selected' : '' }}>Affiliate</option>
                                <option value="lainnya" {{ old('platform') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('platform')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tas_id" class="form-label">Tas *</label>
                            <select class="form-select @error('tas_id') is-invalid @enderror" 
                                    id="tas_id" name="tas_id" required onchange="updateWarnaOptions()">
                                <option value="">Pilih Tas</option>
                                @foreach($tas as $t)  <!-- Ubah $tasList menjadi $tas -->
                                    <option value="{{ $t->id }}" 
                                            data-kode="{{ $t->kode_tas }}"
                                            data-nama="{{ $t->nama_tas }}"
                                            data-harga="{{ $t->harga }}"
                                            data-stok="{{ $t->stok }}"
                                            {{ old('tas_id') == $t->id ? 'selected' : '' }}>
                                        [{{ $t->kode_tas }}] {{ $t->nama_tas }} - Stok: {{ $t->stok }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tas_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="warna" class="form-label">Warna *</label>
                            <select class="form-select @error('warna') is-invalid @enderror" 
                                    id="warna" name="warna" required disabled>
                                <option value="">Pilih Warna Terlebih Dahulu</option>
                                <!-- Opsi warna akan diisi oleh JavaScript -->
                            </select>
                            @error('warna')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jumlah_terjual" class="form-label">Jumlah Terjual *</label>
                            <input type="number" class="form-control @error('jumlah_terjual') is-invalid @enderror" 
                                   id="jumlah_terjual" name="jumlah_terjual" value="{{ old('jumlah_terjual') }}" 
                                   min="1" required>
                            @error('jumlah_terjual')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Informasi</label>
                            <div id="tas-info" style="padding: 10px; background-color: #f8f9fa; border-radius: 6px; font-size: 12px;">
                                <div><strong>Kode:</strong> <span id="info-kode">-</span>#</div>
                                <div><strong>Harga:</strong> Rp <span id="info-harga">0</span></div>
                                <div><strong>Stok Tersedia:</strong> <span id="info-stok">0</span> pcs</div>
                                <div><strong>Warna Tersedia:</strong> <span id="info-warna">-</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" name="keterangan" rows="2" 
                                      placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; padding-top: 12px; border-top: 1px solid #e5e5e5;">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan Laporan
                        </button>
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
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
                <i class="bi bi-info-circle me-1"></i> Panduan
            </div>
            <div class="card-body" style="font-size: 12px; line-height: 1.8; color: #666;">
                <p><strong>Cara Pengisian:</strong></p>
                <ul style="margin-bottom: 12px; padding-left: 16px;">
                    <li>Pilih tanggal transaksi</li>
                    <li>Tentukan platform penjualan</li>
                    <li>Pilih tas yang terjual</li>
                    <li>Pilih warna tas yang terjual</li>
                    <li>Masukkan jumlah yang terjual</li>
                </ul>
                <p><strong>Catatan:</strong></p>
                <p>Stok akan otomatis berkurang sesuai jumlah yang terjual. Pastikan data sudah benar sebelum menyimpan.</p>
                <p><em>Warna hanya bisa dipilih setelah memilih tas.</em></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-tag me-1"></i> Platform
            </div>
            <div class="card-body" style="font-size: 12px; line-height: 1.6;">
                <p>
                    <span class="badge" style="background-color: #ee4d2d; color: white; padding: 2px 6px;">
                        <i class="bi bi-shop me-1"></i> Shopee
                    </span>
                    <br/><small>Marketplace Shopee</small>
                </p>
                <p>
                    <span class="badge" style="background-color: #000; color: white; padding: 2px 6px;">
                        <i class="bi bi-tiktok me-1"></i> TikTok Shop
                    </span>
                    <br/><small>E-Commerce TikTok</small>
                </p>
                <p>
                    <span class="badge bg-primary" style="padding: 2px 6px;">
                        <i class="bi bi-shop-window me-1"></i> Offline
                    </span>
                    <br/><small>Toko atau Endorse</small>
                </p>
                <p>
                    <span class="badge bg-success" style="padding: 2px 6px;">
                        <i class="bi bi-link me-1"></i> Affiliate
                    </span>
                    <br/><small>Program Affiliate</small>
                </p>
                <p>
                    <span class="badge bg-secondary" style="padding: 2px 6px;">
                        <i class="bi bi-three-dots me-1"></i> Lainnya
                    </span>
                    <br/><small>Platform lainnya</small>
                </p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Data warna dari server (akan diambil via AJAX atau inline)
let tasData = {
    @foreach($tas as $t)  <!-- Ubah $tasList menjadi $tas -->
    "{{ $t->id }}": {
        kode: "{{ $t->kode_tas }}",
        nama: "{{ $t->nama_tas }}",
        harga: {{ $t->harga }},
        stok: {{ $t->stok }},
        warna_tas: "{{ $t->warna_tas }}",
        stok_masuk: @json($t->stokMasuk ?? [])
    },
    @endforeach
};

// Data warna dan stok per warna
function getWarnaStokData(tasId) {
    const tas = tasData[tasId];
    if (!tas) return {};
    
    const warnaStok = {};
    if (tas.stok_masuk && tas.stok_masuk.length > 0) {
        tas.stok_masuk.forEach(item => {
            if (!warnaStok[item.warna]) {
                warnaStok[item.warna] = 0;
            }
            warnaStok[item.warna] += item.jumlah;
        });
    }
    
    return warnaStok;
}

// Update pilihan warna berdasarkan tas yang dipilih
function updateWarnaOptions() {
    const tasSelect = document.getElementById('tas_id');
    const warnaSelect = document.getElementById('warna');
    const tasId = tasSelect.value;
    
    // Reset pilihan warna
    warnaSelect.innerHTML = '<option value="">Pilih Warna</option>';
    warnaSelect.disabled = true;
    
    if (!tasId) {
        updateTasInfo();
        return;
    }
    
    const tas = tasData[tasId];
    if (!tas) return;
    
    // Update informasi tas
    updateTasInfo();
    
    // Ambil data stok per warna
    const warnaStokData = getWarnaStokData(tasId);
    
    // Parse warna dari string (format: "Warna1, Warna2, ...")
    let warnaList = [];
    if (tas.warna_tas) {
        warnaList = tas.warna_tas.split(',').map(w => w.trim()).filter(w => w);
    }
    
    // Jika tidak ada warna di string, ambil dari stok_masuk
    if (warnaList.length === 0 && Object.keys(warnaStokData).length > 0) {
        warnaList = Object.keys(warnaStokData);
    }
    
    // Tambahkan opsi warna
    if (warnaList.length > 0) {
        warnaSelect.disabled = false;
        
        warnaList.forEach(warna => {
            const stokWarna = warnaStokData[warna] || 0;
            const option = document.createElement('option');
            option.value = warna;
            option.textContent = `${warna} (Stok: ${stokWarna} pcs)`;
            option.dataset.stok = stokWarna;
            
            // Set selected jika ada old value
            if ("{{ old('warna') }}" === warna) {
                option.selected = true;
            }
            
            warnaSelect.appendChild(option);
        });
        
        // Tambahkan event listener untuk update stok saat warna berubah
        warnaSelect.onchange = updateStokInfo;
    } else {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'Tidak ada warna tersedia';
        warnaSelect.appendChild(option);
    }
}

// Update informasi tas
function updateTasInfo() {
    const tasSelect = document.getElementById('tas_id');
    const tasId = tasSelect.value;
    
    if (!tasId) {
        document.getElementById('info-kode').textContent = '-';
        document.getElementById('info-harga').textContent = '0';
        document.getElementById('info-stok').textContent = '0';
        document.getElementById('info-warna').textContent = '-';
        return;
    }
    
    const tas = tasData[tasId];
    if (!tas) return;
    
    document.getElementById('info-kode').textContent = tas.kode;
    document.getElementById('info-harga').textContent = tas.harga.toLocaleString('id-ID');
    document.getElementById('info-stok').textContent = tas.stok.toLocaleString('id-ID');
    
    // Tampilkan daftar warna
    const warnaStokData = getWarnaStokData(tasId);
    const warnaList = Object.keys(warnaStokData);
    if (warnaList.length > 0) {
        document.getElementById('info-warna').textContent = warnaList.join(', ');
    } else if (tas.warna_tas) {
        document.getElementById('info-warna').textContent = tas.warna_tas;
    } else {
        document.getElementById('info-warna').textContent = '-';
    }
    
    // Update stok info berdasarkan warna yang dipilih
    updateStokInfo();
}

// Update informasi stok berdasarkan warna yang dipilih
function updateStokInfo() {
    const tasSelect = document.getElementById('tas_id');
    const warnaSelect = document.getElementById('warna');
    const tasId = tasSelect.value;
    const warna = warnaSelect.value;
    
    if (!tasId || !warna) return;
    
    const warnaStokData = getWarnaStokData(tasId);
    const stokWarna = warnaStokData[warna] || 0;
    
    // Update informasi di tempat yang sesuai (bisa ditambahkan jika diperlukan)
    const selectedOption = warnaSelect.options[warnaSelect.selectedIndex];
    if (selectedOption && selectedOption.dataset.stok) {
        // Update teks opsi dengan stok terkini
        selectedOption.textContent = `${warna} (Stok: ${stokWarna} pcs)`;
    }
}

// Validasi form sebelum submit
document.getElementById('laporanForm').addEventListener('submit', function(e) {
    const tasSelect = document.getElementById('tas_id');
    const warnaSelect = document.getElementById('warna');
    const jumlahInput = document.getElementById('jumlah_terjual');
    const tasId = tasSelect.value;
    const warna = warnaSelect.value;
    const jumlah = parseInt(jumlahInput.value);
    
    if (!tasId || !warna) {
        alert('Harap pilih tas dan warna terlebih dahulu!');
        e.preventDefault();
        return;
    }
    
    // Ambil stok untuk warna yang dipilih
    const warnaStokData = getWarnaStokData(tasId);
    const stokWarna = warnaStokData[warna] || 0;
    
    if (jumlah > stokWarna) {
        alert(`Jumlah terjual (${jumlah}) melebihi stok tersedia untuk warna ${warna} (${stokWarna} pcs)!`);
        e.preventDefault();
        return;
    }
    
    if (jumlah <= 0) {
        alert('Jumlah terjual harus lebih dari 0!');
        e.preventDefault();
        return;
    }
});

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Update warna options jika tas sudah terpilih (misalnya dari old input)
    const tasId = document.getElementById('tas_id').value;
    if (tasId) {
        updateWarnaOptions();
        updateTasInfo();
    }
    
    // Update informasi saat jumlah berubah
    document.getElementById('jumlah_terjual').addEventListener('change', function() {
        const tasId = document.getElementById('tas_id').value;
        const warna = document.getElementById('warna').value;
        
        if (tasId && warna) {
            const warnaStokData = getWarnaStokData(tasId);
            const stokWarna = warnaStokData[warna] || 0;
            const jumlah = parseInt(this.value);
            
            if (jumlah > stokWarna) {
                this.setCustomValidity(`Stok tersedia hanya ${stokWarna} pcs`);
                this.reportValidity();
            } else {
                this.setCustomValidity('');
            }
        }
    });
});
</script>
@endsection
@endsection