@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Laporan</h1>
        <p class="page-subtitle">Ubah data transaksi penjualan</p>
    </div>
    <a href="{{ route('laporan.show', $laporan->id) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-1"></i> Form Edit Laporan
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.update', $laporan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                               value="{{ old('tanggal', $laporan->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Platform</label>
                        <select name="platform" class="form-select @error('platform') is-invalid @enderror" required>
                            <option value="">Pilih Platform</option>
                            <option value="shopee" {{ old('platform', $laporan->platform) == 'shopee' ? 'selected' : '' }}>Shopee</option>
                            <option value="tiktok" {{ old('platform', $laporan->platform) == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                            <option value="offline" {{ old('platform', $laporan->platform) == 'offline' ? 'selected' : '' }}>Offline</option>
                            <option value="affiliate" {{ old('platform', $laporan->platform) == 'affiliate' ? 'selected' : '' }}>Affiliate</option>
                            <option value="lainnya" {{ old('platform', $laporan->platform) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('platform')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Tas</label>
                        <select name="tas_id" id="tas_id" class="form-select @error('tas_id') is-invalid @enderror" required>
                            <option value="">Pilih Tas</option>
                            @foreach($tas as $item)
                                <option value="{{ $item->id }}" 
                                    {{ old('tas_id', $laporan->tas_id) == $item->id ? 'selected' : '' }}
                                    data-stok="{{ $item->stok }}"
                                    data-harga="{{ $item->harga }}">
                                    {{ $item->kode_tas }}# - {{ $item->nama_tas }} (Stok: {{ $item->stok }})
                                </option>
                            @endforeach
                        </select>
                        @error('tas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Warna</label>
                        <select name="warna" id="warna" class="form-select @error('warna') is-invalid @enderror" required>
                            <option value="">Pilih Warna</option>
                            @php
                                $selectedTas = $tas->find($laporan->tas_id);
                                $warnaOptions = $selectedTas ? 
                                    $selectedTas->stokMasuk->pluck('warna')->unique()->filter() : 
                                    collect([]);
                            @endphp
                            
                            @if($selectedTas)
                                @foreach($warnaOptions as $warnaOption)
                                    <option value="{{ $warnaOption }}" 
                                        {{ old('warna', $laporan->warna) == $warnaOption ? 'selected' : '' }}>
                                        {{ $warnaOption }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('warna')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah Terjual</label>
                        <input type="number" name="jumlah_terjual" id="jumlah_terjual" 
                               class="form-control @error('jumlah_terjual') is-invalid @enderror" 
                               value="{{ old('jumlah_terjual', $laporan->jumlah_terjual) }}" min="1" required>
                        <small class="text-muted">Stok tersedia untuk warna ini: <span id="stokInfo">-</span></small>
                        @error('jumlah_terjual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Harga Satuan</label>
                        <input type="text" id="harga" class="form-control" 
                               value="Rp {{ number_format($laporan->tas->harga, 0, ',', '.') }},00" readonly disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Total Harga</label>
                        <input type="text" id="total" class="form-control" 
                               value="Rp {{ number_format($laporan->jumlah_terjual * $laporan->tas->harga, 0, ',', '.') }},00" readonly disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $laporan->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Stok awal: {{ $laporan->tas->stok }} pcs. Transaksi ini mengurangi {{ $laporan->jumlah_terjual }} pcs.</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tasSelect = document.getElementById('tas_id');
    const warnaSelect = document.getElementById('warna');
    const jumlahInput = document.getElementById('jumlah_terjual');
    const stokInfo = document.getElementById('stokInfo');
    const totalInput = document.getElementById('total');
    
    // Data stok per warna (akan diisi dari server)
    let stokPerWarna = {};
    
    // Load stok per warna saat tas dipilih
    tasSelect.addEventListener('change', function() {
        const tasId = this.value;
        if (tasId) {
            fetch(`/tas/${tasId}/stok-warna`)
                .then(response => response.json())
                .then(data => {
                    stokPerWarna = data;
                    
                    // Update warna options
                    warnaSelect.innerHTML = '<option value="">Pilih Warna</option>';
                    Object.keys(stokPerWarna).forEach(warna => {
                        if (warna) { // Skip empty warna
                            const option = document.createElement('option');
                            option.value = warna;
                            option.textContent = `${warna} (Stok: ${stokPerWarna[warna]})`;
                            if (warna === '{{ $laporan->warna }}') {
                                option.selected = true;
                            }
                            warnaSelect.appendChild(option);
                        }
                    });
                    
                    // Trigger warna change untuk update stok
                    warnaSelect.dispatchEvent(new Event('change'));
                });
        }
    });
    
    // Update stok info saat warna dipilih
    warnaSelect.addEventListener('change', function() {
        const warna = this.value;
        const jumlah = parseInt(jumlahInput.value) || 0;
        
        if (warna && stokPerWarna[warna] !== undefined) {
            const stok = stokPerWarna[warna];
            const stokLama = '{{ $laporan->warna }}' === warna ? 
                stok + {{ $laporan->jumlah_terjual }} : stok;
            
            stokInfo.textContent = `${stokLama} pcs (termasuk stok yang akan dikembalikan dari transaksi ini)`;
            
            // Validasi stok
            if (jumlah > stokLama) {
                jumlahInput.classList.add('is-invalid');
            } else {
                jumlahInput.classList.remove('is-invalid');
            }
        } else {
            stokInfo.textContent = '-';
        }
        
        updateTotal();
    });
    
    // Update total saat jumlah berubah
    jumlahInput.addEventListener('input', function() {
        updateTotal();
        
        // Validasi stok
        const warna = warnaSelect.value;
        if (warna && stokPerWarna[warna] !== undefined) {
            const stokLama = '{{ $laporan->warna }}' === warna ? 
                stokPerWarna[warna] + {{ $laporan->jumlah_terjual }} : 
                stokPerWarna[warna];
            
            if (parseInt(this.value) > stokLama) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        }
    });
    
    function updateTotal() {
        const selectedOption = tasSelect.options[tasSelect.selectedIndex];
        const harga = selectedOption ? parseFloat(selectedOption.dataset.harga) : 0;
        const jumlah = parseInt(jumlahInput.value) || 0;
        
        const total = harga * jumlah;
        totalInput.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total) + ',00';
    }
    
    // Trigger initial load
    if (tasSelect.value) {
        tasSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection