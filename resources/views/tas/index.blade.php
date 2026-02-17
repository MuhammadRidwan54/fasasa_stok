@extends('layouts.app')

@section('title', 'Master Tas')

@section('content')
<div class="page-header" style="animation: fadeInUp 0.6s ease forwards; opacity: 0;">
    <div>
        <h1 class="page-title" style="animation: slideInFromLeft 0.5s ease 0.2s forwards; opacity: 0;">Master Tas</h1>
        <p class="page-subtitle" style="animation: slideInFromLeft 0.5s ease 0.3s forwards; opacity: 0;">Kelola data tas, warna, dan stok</p>
    </div>
    <div style="animation: slideInFromRight 0.5s ease 0.4s forwards; opacity: 0;">
        <a href="{{ route('tas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Tas
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4" style="animation: fadeInUp 0.6s ease 0.5s forwards; opacity: 0;">
    <div class="card-body">
        <form action="{{ route('tas.index') }}" method="GET" class="row g-2">
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-search"></i> Cari</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Kode/Nama/Model..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label"><i class="bi bi-palette"></i> Warna</label>
                <select name="warna" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['Hitam', 'Putih', 'Merah', 'Biru', 'Hijau', 'Coklat', 'Abu-abu', 'Kuning', 'Ungu', 'Pink', 'Navy', 'Maroon'] as $warna)
                        <option value="{{ $warna }}" {{ request('warna') == $warna ? 'selected' : '' }}>
                            {{ $warna }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="bi bi-exclamation-triangle"></i> Status Stok</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Aman (>10)</option>
                    <option value="menipis" {{ request('status') == 'menipis' ? 'selected' : '' }}>Menipis (1-10)</option>
                    <option value="kritis" {{ request('status') == 'kritis' ? 'selected' : '' }}>Kritis (0)</option>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('tas.index') }}" class="btn btn-secondary" style="flex: 1;">
                    <i class="bi bi-arrow-clockwise me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="row mb-4">
    @php
        $statCards = [
            [
                'icon' => 'bi-boxes',
                'label' => 'Total Item',
                'value' => $tas->total(),
                'color' => '#3498db',
                'animationDelay' => '0.6s',
                'prefix' => '',
                'suffix' => ''
            ],
            [
                'icon' => 'bi-box',
                'label' => 'Total Stok',
                'value' => $totalStok ?? 0,
                'color' => '#2ecc71',
                'animationDelay' => '0.7s',
                'prefix' => '',
                'suffix' => ''
            ],
            [
                'icon' => 'bi-cash-stack',
                'label' => 'Nilai Stok',
                'value' => $totalNilai ?? 0,
                'color' => '#f39c12',
                'animationDelay' => '0.8s',
                'prefix' => 'Rp ',
                'suffix' => ',00'
            ],
            [
                'icon' => 'bi-exclamation-octagon',
                'label' => 'Stok Kritis',
                'value' => $stokKritis ?? 0,
                'color' => '#e74c3c',
                'animationDelay' => '0.9s',
                'prefix' => '',
                'suffix' => ''
            ]
        ];
    @endphp
    
    @foreach($statCards as $index => $card)
    <div class="col-md-3 col-sm-6">
        <div class="stat-card stat-card-animated" 
             style="animation: fadeInUp 0.5s ease {{ $card['animationDelay'] }} forwards; opacity: 0; border-color: {{ $card['color'] }}20;">
            <i class="bi {{ $card['icon'] }} stat-card-icon" style="color: {{ $card['color'] }};"></i>
            <div class="stat-card-label">{{ $card['label'] }}</div>
            <div class="stat-card-number" 
                 id="tas-stat-card-{{ $index }}" 
                 data-value="{{ $card['value'] }}"
                 data-prefix="{{ $card['prefix'] }}"
                 data-suffix="{{ $card['suffix'] }}"
                 style="color: {{ $card['color'] }};">
                @if($card['prefix'] == 'Rp ')
                    {{ $card['prefix'] }}0{{ $card['suffix'] }}
                @else
                    0{{ $card['suffix'] }}
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Table -->
<div class="card card-animated" style="animation: fadeInUp 0.6s ease 1s forwards; opacity: 0;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <span><i class="bi bi-table me-1"></i> Daftar Tas</span>
        @if(request()->hasAny(['search', 'warna', 'status']))
            <a href="{{ route('tas.index') }}" class="btn btn-outline-secondary" style="padding: 4px 8px; font-size: 11px;">
                <i class="bi bi-eye"></i> Tampilkan Semua
            </a>
        @endif
    </div>
    <div class="card-body">
        @if($tas->isEmpty())
            <div style="text-align: center; padding: 40px 20px; color: #999;" class="empty-state-animated">
                <i class="bi bi-inboxes" style="font-size: 32px; display: block; margin-bottom: 8px; animation: float 3s ease-in-out infinite;"></i>
                <h6>Data tidak ditemukan</h6>
                <p style="margin-bottom: 16px;">Silahkan coba filter lain atau tambahkan data baru</p>
                <a href="{{ route('tas.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Tas
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="100">Kode</th>
                            <th width="100">Model</th>
                            <th width="120">Nama Tas</th>
                            <th width="80">Warna</th>
                            <th width="60">Stok</th>
                            <th width="100">Harga</th>
                            <th width="100">Nilai</th>
                            <th width="80">Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tas as $index => $item)
                        <tr class="table-row-animated" style="animation: fadeInUp 0.4s ease {{ $index * 0.05 + 1.1 }}s forwards; opacity: 0;">
                            <td class="text-center">{{ ($tas->currentPage() - 1) * $tas->perPage() + $loop->iteration }}</td>
                            <td>
                                <a href="javascript:void(0);" class="text-dark fw-bold table-link-animated" 
                                   onclick="showDetailModal({{ $item->id }})"
                                   style="text-decoration: none; cursor: pointer;">
                                    {{ $item->kode_tas }}#
                                </a>
                            </td>
                            <td>{{ $item->model_tas }}</td>
                            <td>
                                <a href="javascript:void(0);" class="table-link-animated"
                                   onclick="showDetailModal({{ $item->id }})"
                                   style="text-decoration: none; color: #333; cursor: pointer;">
                                    {{ $item->nama_tas }}
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-colored" style="
                                    background-color: {{ $item->warna_tas == 'Hitam' ? '#333' : 
                                                      ($item->warna_tas == 'Merah' ? '#e74c3c' : 
                                                      ($item->warna_tas == 'Biru' ? '#3498db' : 
                                                      ($item->warna_tas == 'Hijau' ? '#27ae60' :
                                                      ($item->warna_tas == 'Kuning' ? '#f39c12' :
                                                      ($item->warna_tas == 'Ungu' ? '#8e44ad' :
                                                      ($item->warna_tas == 'Pink' ? '#e91e63' :
                                                      ($item->warna_tas == 'Navy' ? '#1a3a52' :
                                                      ($item->warna_tas == 'Maroon' ? '#85144b' : '#95a5a6')))))))) }};
                                    color: {{ in_array($item->warna_tas, ['Kuning', 'Putih']) ? '#000' : 'white' }};
                                ">
                                    {{ $item->warna_tas }}
                                </span>
                            </td>
                            <td>{{ $item->stok }} pcs</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }},00</td>
                            <td>Rp {{ number_format($item->stok * $item->harga, 0, ',', '.') }},00</td>
                            <td>
                                @if($item->stok > 10)
                                    <span class="badge badge-success badge-pulse">Aman</span>
                                @elseif($item->stok > 0)
                                    <span class="badge badge-warning badge-pulse">Menipis</span>
                                @else
                                    <span class="badge badge-danger badge-pulse">Kritis</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-animated">
                                    <a href="javascript:void(0);" class="btn btn-info btn-action" 
                                       onclick="showDetailModal({{ $item->id }})" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('tas.edit', $item->id) }}" class="btn btn-warning btn-action" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('tas.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-action" 
                                                onclick="return confirm('Hapus data tas ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 12px; border-top: 1px solid #e5e5e5; animation: fadeInUp 0.5s ease 1.5s forwards; opacity: 0;">
                <div style="font-size: 12px; color: #666;">
                    Menampilkan {{ $tas->firstItem() }} - {{ $tas->lastItem() }} dari {{ $tas->total() }} item
                </div>
                <div>
                    {{ $tas->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal untuk Detail Tas -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #000; color: #fff;">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="bi bi-info-circle me-1"></i> Detail Tas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Konten akan diisi oleh JavaScript -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Tutup
                </button>
                <a href="#" id="editLink" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <a href="#" id="detailLink" class="btn btn-info">
                    <i class="bi bi-eye me-1"></i> Lihat Detail Lengkap
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS tambahan untuk animasi angka pada Tas Page */
    .stat-card-number {
        transition: all 0.5s ease-out;
    }

    .number-animate {
        animation: numberBounce 0.6s ease-out;
        display: inline-block;
    }

    @keyframes numberBounce {
        0% { transform: scale(0.5); opacity: 0; }
        50% { transform: scale(1.1); }
        70% { transform: scale(0.95); }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes numberPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Animasi untuk card saat hover */
    .stat-card:hover .stat-card-number {
        animation: numberPulse 1.5s ease-in-out infinite;
    }

    /* Efek glow untuk angka */
    .glow-effect {
        text-shadow: 0 0 10px currentColor;
    }

    

    /* Table Animations for Tas Page */
    .table-row-animated {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: left;
    }
    
    .table-row-animated:hover {
        background-color: rgba(0, 102, 204, 0.08) !important;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Link animations */
    .table-link-animated {
        transition: all 0.2s ease;
        position: relative;
    }
    
    .table-link-animated:hover {
        color: #0066cc !important;
        transform: translateX(2px);
    }
    
    .table-link-animated::after {
        content: '';
        position: absolute;
        width: 0;
        height: 1px;
        bottom: -2px;
        left: 0;
        background-color: #0066cc;
        transition: width 0.3s ease;
    }
    
    .table-link-animated:hover::after {
        width: 100%;
    }
    
    /* Badge animations */
    .badge-pulse {
        animation: pulse 2s infinite;
        transition: all 0.3s ease;
    }
    
    .badge-pulse:hover {
        animation: none;
        transform: scale(1.1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .badge-colored {
        transition: all 0.3s ease;
    }
    
    .badge-colored:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        filter: brightness(1.1);
    }
    
    /* Button group animations */
    .btn-group-animated {
        transition: all 0.3s ease;
    }
    
    .btn-action {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .btn-action:active {
        transform: translateY(0);
    }
    
    /* Empty state animation */
    .empty-state-animated {
        animation: fadeInUp 0.8s ease 1s forwards;
        opacity: 0;
    }
    
    /* Keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInFromRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.1);
        }
        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(0, 0, 0, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
        }
    }
</style>

@section('scripts')
<script>
    // Fungsi animasi counter untuk Tas Page
    function animateTasValue(element, start, end, duration) {
        const startTime = performance.now();
        const prefix = element.getAttribute('data-prefix') || '';
        const suffix = element.getAttribute('data-suffix') || '';
        
        // Format number dengan thousand separator
        const formatNumber = (num) => {
            const roundedNum = Math.round(num);
            
            // Format angka dengan titik sebagai pemisah ribuan
            let formattedNum;
            if (prefix === 'Rp ') {
                // Untuk currency, gunakan format Indonesia
                formattedNum = roundedNum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            } else {
                // Untuk angka biasa
                formattedNum = roundedNum.toString();
            }
            
            return `${prefix}${formattedNum}${suffix}`;
        };
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function (easeOutCubic)
            const easedProgress = 1 - Math.pow(1 - progress, 3);
            
            const currentValue = start + (end - start) * easedProgress;
            element.innerHTML = formatNumber(currentValue);
            
            // Add scale effect during animation
            if (progress < 1) {
                const scale = 1 + (Math.sin(progress * Math.PI * 8) * 0.05 * (1 - progress));
                element.style.transform = `scale(${scale})`;
                requestAnimationFrame(update);
            } else {
                element.innerHTML = formatNumber(end);
                element.style.transform = 'scale(1)';
                
                // Add final bounce animation
                element.classList.add('number-animate');
                setTimeout(() => {
                    element.classList.remove('number-animate');
                }, 600);
            }
        }
        
        requestAnimationFrame(update);
    }
    
    // Fungsi untuk inisialisasi animasi counter
    function initializeTasCounterAnimations() {
        const statNumbers = document.querySelectorAll('.stat-card-number[id^="tas-stat-card-"]');
        
        statNumbers.forEach((element, index) => {
            const finalValue = parseFloat(element.getAttribute('data-value')) || 0;
            const prefix = element.getAttribute('data-prefix') || '';
            const suffix = element.getAttribute('data-suffix') || '';
            
            // Set delay berdasarkan posisi
            const delay = 600 + (index * 200);
            
            setTimeout(() => {
                // Set initial value dengan format yang benar
                if (prefix === 'Rp ') {
                    element.innerHTML = `${prefix}0${suffix}`;
                } else if (suffix) {
                    element.innerHTML = `0${suffix}`;
                } else {
                    element.innerHTML = '0';
                }
                
                // Start animation
                animateTasValue(element, 0, finalValue, 1500);
                
                // Add glow effect setelah animasi selesai
                setTimeout(() => {
                    element.classList.add('glow-effect');
                    setTimeout(() => {
                        element.classList.remove('glow-effect');
                    }, 1000);
                }, 1600);
                
            }, delay);
        });
    }

    // Fungsi untuk menampilkan modal detail
    function showDetailModal(tasId) {
        // Animation feedback for clicked row
        const clickedRow = event.target.closest('tr');
        if (clickedRow) {
            clickedRow.style.backgroundColor = 'rgba(52, 152, 219, 0.1)';
            setTimeout(() => {
                clickedRow.style.backgroundColor = '';
            }, 300);
        }
        
        // Tampilkan modal
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
        
        // Tampilkan loading
        document.getElementById('modalBody').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat data...</p>
            </div>
        `;
        
        // Ambil data via AJAX
        fetch(`/tas/${tasId}/quickview`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update konten modal
                document.getElementById('modalBody').innerHTML = data.html;
                
                // Update link edit dan detail
                document.getElementById('editLink').href = `/tas/${tasId}/edit`;
                document.getElementById('detailLink').href = `/tas/${tasId}`;
                
                // Update judul modal
                document.getElementById('detailModalLabel').innerHTML = 
                    `<i class="bi bi-info-circle me-1"></i> ${data.tas.kode_tas} - ${data.tas.nama_tas}`;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('modalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Gagal memuat data. Silakan coba lagi.
                    </div>
                `;
            });
    }

    // Tambahkan event listener untuk klik pada baris tabel
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi animasi counter untuk Tas Page
        console.log('DOM Loaded - Initializing Tas counter animations');
        setTimeout(initializeTasCounterAnimations, 100);
        
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            // Kecuali kolom aksi
            const cells = row.querySelectorAll('td:not(:last-child)');
            
            cells.forEach(cell => {
                // Skip jika sudah ada link di dalamnya
                if (cell.querySelector('a') || cell.querySelector('button')) {
                    return;
                }
                
                // Tambahkan event listener untuk klik pada sel
                cell.style.cursor = 'pointer';
                cell.addEventListener('click', function(e) {
                    // Cegah jika yang diklik adalah input/button/link
                    if (e.target.tagName === 'INPUT' || 
                        e.target.tagName === 'BUTTON' || 
                        e.target.tagName === 'A' ||
                        e.target.closest('button') || 
                        e.target.closest('a')) {
                        return;
                    }
                    
                    // Ambil ID tas dari baris
                    const tasId = row.querySelector('.btn-group a[onclick*="showDetailModal"]')
                        .getAttribute('onclick')
                        .match(/showDetailModal\((\d+)\)/)[1];
                    
                    showDetailModal(tasId);
                });
            });
        });
        
        // Add hover effects to buttons
        document.querySelectorAll('.btn-action').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                const icon = this.querySelector('i');
                if (icon) {
                    icon.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 300);
                }
            });
        });
        
        // Add animation to badges
        document.querySelectorAll('.badge-colored').forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Re-run animations when navigating back to page
        if (performance.navigation.type === performance.navigation.TYPE_BACK_FORWARD) {
            setTimeout(initializeTasCounterAnimations, 500);
        }
    });
</script>
@endsection
@endsection