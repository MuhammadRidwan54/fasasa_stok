<!-- resources/views/laporan/create.blade.php -->
@extends('layouts.app')

@section('title', 'Tambah Laporan')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i> Tambah Laporan Penjualan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.store') }}" method="POST" id="laporanForm">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tas_id" class="form-label">
                                <i class="bi bi-bag me-1"></i> Pilih Tas *
                            </label>
                            <select class="form-select @error('tas_id') is-invalid @enderror" 
                                    id="tas_id" name="tas_id" required>
                                <option value="">Pilih Tas</option>
                                @foreach($tas as $item)
                                <option value="{{ $item->id }}" 
                                        data-stok="{{ $item->stok }}"
                                        data-warna="{{ $item->warna_tas }}"
                                        data-harga="{{ $item->harga }}">
                                    <i class="bi bi-upc-scan"></i> {{ $item->kode_tas }} - {{ $item->nama_tas }} ({{ $item->warna_tas }}) - Stok: {{ $item->stok }}
                                </option>
                                @endforeach
                            </select>
                            @error('tas_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="tanggal" class="form-label">
                                <i class="bi bi-calendar3 me-1"></i> Tanggal Transaksi *
                            </label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" name="tanggal" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="jumlah_terjual" class="form-label">
                                <i class="bi bi-cart me-1"></i> Jumlah Terjual *
                            </label>
                            <input type="number" class="form-control @error('jumlah_terjual') is-invalid @enderror" 
                                   id="jumlah_terjual" name="jumlah_terjual" 
                                   value="{{ old('jumlah_terjual', 0) }}" min="0" required>
                            @error('jumlah_terjual')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="bi bi-box"></i> Stok tersedia: <span id="stok-tersedia">0</span> pcs
                            </small>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="platform" class="form-label">
                                <i class="bi bi-shop me-1"></i> Platform Penjualan *
                            </label>
                            <select class="form-select @error('platform') is-invalid @enderror" 
                                    id="platform" name="platform" required>
                                <option value="">Pilih Platform</option>
                                <option value="shopee" {{ old('platform') == 'shopee' ? 'selected' : '' }}>
                                    <i class="bi bi-shop"></i> Shopee
                                </option>
                                <option value="tiktok" {{ old('platform') == 'tiktok' ? 'selected' : '' }}>
                                    <i class="bi bi-tiktok"></i> TikTok Shop
                                </option>
                                {{-- <option value="offline" {{ old('platform') == 'offline' ? 'selected' : '' }}>
                                    <i class="bi bi-shop-window"></i> Offline Store --}}
                                </option>
                                <option value="lainnya" {{ old('platform') == 'lainnya' ? 'selected' : '' }}>
                                    <i class="bi bi-three-dots"></i> Lainnya
                                </option>
                            </select>
                            @error('platform')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="harga_satuan" class="form-label">
                                <i class="bi bi-currency-dollar me-1"></i> Harga Satuan
                            </label>
                            <input type="text" class="form-control" 
                                   id="harga_satuan" readonly
                                   value="Rp 0">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="keterangan" class="form-label">
                                <i class="bi bi-chat-left-text me-1"></i> Keterangan
                            </label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" name="keterangan" 
                                      rows="3" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Preview -->
                    <div class="card mb-4" id="preview-card" style="display: none;">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="bi bi-eye me-2"></i> Preview Transaksi</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong><i class="bi bi-palette"></i> Warna Tas:</strong>
                                    <div id="preview-warna">-</div>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="bi bi-cart"></i> Jumlah Terjual:</strong>
                                    <div id="preview-jumlah">0 pcs</div>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="bi bi-shop"></i> Platform:</strong>
                                    <div id="preview-platform">-</div>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="bi bi-cash-stack"></i> Total Harga:</strong>
                                    <div id="preview-total" class="h5 text-success">Rp 0</div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <strong><i class="bi bi-box"></i> Sisa Stok Setelah Transaksi:</strong>
                                    <div id="preview-sisa" class="h5">0 pcs</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Laporan
                        </button>
                        <a href="{{ route('laporan.index') }}" class="btn btn-secondary px-4">
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
    const tasSelect = document.getElementById('tas_id');
    const jumlahInput = document.getElementById('jumlah_terjual');
    const platformSelect = document.getElementById('platform');
    const stokTersediaSpan = document.getElementById('stok-tersedia');
    const hargaSatuanInput = document.getElementById('harga_satuan');
    const previewCard = document.getElementById('preview-card');
    
    // Platform icon mapping
    const platformIcons = {
        'shopee': '<i class="bi bi-shop text-warning"></i> Shopee',
        'tiktok': '<i class="bi bi-tiktok text-dark"></i> TikTok Shop',
        'lainnya': '<i class="bi bi-three-dots text-secondary"></i> Lainnya'
    };
    
    // Update stok tersedia ketika memilih tas
    tasSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stok = selectedOption.getAttribute('data-stok') || 0;
        const harga = selectedOption.getAttribute('data-harga') || 0;
        const warna = selectedOption.getAttribute('data-warna') || '';
        
        stokTersediaSpan.textContent = stok;
        hargaSatuanInput.value = 'Rp ' + formatNumber(harga);
        
        // Reset jumlah terjual
        jumlahInput.max = stok;
        jumlahInput.value = Math.min(jumlahInput.value, stok);
        
        updatePreview();
    });
    
    // Update preview ketika jumlah atau platform berubah
    jumlahInput.addEventListener('input', updatePreview);
    platformSelect.addEventListener('change', updatePreview);
    
    function updatePreview() {
        const selectedOption = tasSelect.options[tasSelect.selectedIndex];
        
        if (tasSelect.value && selectedOption) {
            const stok = parseInt(selectedOption.getAttribute('data-stok')) || 0;
            const harga = parseInt(selectedOption.getAttribute('data-harga')) || 0;
            const warna = selectedOption.getAttribute('data-warna') || '';
            const jumlah = parseInt(jumlahInput.value) || 0;
            const platform = platformSelect.value;
            const total = harga * jumlah;
            const sisa = stok - jumlah;
            
            // Update preview
            document.getElementById('preview-warna').textContent = warna;
            document.getElementById('preview-jumlah').textContent = jumlah + ' pcs';
            
            // Tampilkan platform dengan icon
            if (platform) {
                document.getElementById('preview-platform').innerHTML = platformIcons[platform] || platform;
            } else {
                document.getElementById('preview-platform').textContent = '-';
            }
            
            document.getElementById('preview-total').textContent = 'Rp ' + formatNumber(total);
            document.getElementById('preview-sisa').textContent = sisa + ' pcs';
            
            // Tampilkan preview card
            previewCard.style.display = 'block';
            
            // Ubah warna sisa stok
            const sisaElement = document.getElementById('preview-sisa');
            if (sisa > 10) {
                sisaElement.className = 'h5 text-success';
            } else if (sisa > 0) {
                sisaElement.className = 'h5 text-warning';
            } else {
                sisaElement.className = 'h5 text-danger';
            }
        } else {
            previewCard.style.display = 'none';
        }
    }
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Validasi sebelum submit
    document.getElementById('laporanForm').addEventListener('submit', function(e) {
        const stok = parseInt(stokTersediaSpan.textContent) || 0;
        const jumlah = parseInt(jumlahInput.value) || 0;
        const platform = platformSelect.value;
        
        if (jumlah > stok) {
            alert('Jumlah terjual tidak boleh melebihi stok tersedia!');
            e.preventDefault();
            return false;
        }
        
        if (jumlah <= 0) {
            alert('Jumlah terjual harus lebih dari 0!');
            e.preventDefault();
            return false;
        }
        
        if (!tasSelect.value) {
            alert('Pilih tas terlebih dahulu!');
            e.preventDefault();
            return false;
        }
        
        if (!platform) {
            alert('Pilih platform penjualan terlebih dahulu!');
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection
@endsection