<div class="row" style="animation: fadeInUp 0.5s ease 0.1s forwards; opacity: 0;">
    <div class="col-md-6">
        <div class="mb-3 info-card-animated" style="animation: fadeInLeft 0.5s ease 0.2s forwards; opacity: 0;">
            <label class="form-label"><strong><i class="bi bi-tag me-1"></i> Kode Tas</strong></label>
            <div class="p-2 bg-light rounded info-content-animated">
                <span class="fw-bold text-dark">{{ $tas->kode_tas }}#</span>
            </div>
        </div>
        
        <div class="mb-3 info-card-animated" style="animation: fadeInLeft 0.5s ease 0.3s forwards; opacity: 0;">
            <label class="form-label"><strong><i class="bi bi-box me-1"></i> Nama Tas</strong></label>
            <div class="p-2 bg-light rounded info-content-animated">
                {{ $tas->nama_tas }}
            </div>
        </div>
        
        <div class="mb-3 info-card-animated" style="animation: fadeInLeft 0.5s ease 0.4s forwards; opacity: 0;">
            <label class="form-label"><strong><i class="bi bi-grid me-1"></i> Kategori</strong></label>
            <div class="p-2 bg-light rounded info-content-animated">
                {{ $tas->model_tas }}
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="mb-3 info-card-animated" style="animation: fadeInRight 0.5s ease 0.2s forwards; opacity: 0;">
            <label class="form-label"><strong><i class="bi bi-cash-coin me-1"></i> Harga</strong></label>
            <div class="p-2 bg-light rounded info-content-animated">
                <span class="fw-bold text-success">Rp {{ number_format($tas->harga, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="mb-3 info-card-animated" style="animation: fadeInRight 0.5s ease 0.3s forwards; opacity: 0;">
            <label class="form-label"><strong><i class="bi bi-box-seam me-1"></i> Total Stok</strong></label>
            <div class="p-2 bg-light rounded info-content-animated">
                <span class="fw-bold">{{ $totalStok }} pcs</span>
                @if($tas->stok > 10)
                    <span class="badge badge-success ms-2 badge-pulse">Aman</span>
                @elseif($tas->stok > 0)
                    <span class="badge badge-warning ms-2 badge-pulse">Menipis</span>
                @else
                    <span class="badge badge-danger ms-2 badge-pulse">Kritis</span>
                @endif
            </div>
        </div>
        
        <div class="mb-3 info-card-animated" style="animation: fadeInRight 0.5s ease 0.4s forwards; opacity: 0;">
            <label class="form-label"><strong><i class="bi bi-calculator me-1"></i> Nilai Stok</strong></label>
            <div class="p-2 bg-light rounded info-content-animated">
                <span class="fw-bold text-info">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Warna dan Stok -->
<div class="mt-4" style="animation: fadeInUp 0.5s ease 0.5s forwards; opacity: 0;">
    <h6 class="section-title-animated">
        <i class="bi bi-palette me-1"></i> Detail Warna dan Stok
    </h6>
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>Warna</th>
                    <th width="100">Stok</th>
                    <th width="120">Tanggal Masuk</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tas->stokMasuk as $index => $stokMasuk)
                <tr class="table-row-animated" style="animation: fadeInUp 0.4s ease {{ $index * 0.1 + 0.6 }}s forwards; opacity: 0;">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @php
                            $color = $warnaColors[$stokMasuk->warna] ?? ['bg' => '#f8f9fa', 'text' => '#000000'];
                        @endphp
                        <span class="badge badge-colored" style="
                            background-color: {{ $color['bg'] }};
                            color: {{ $color['text'] }};
                            padding: 4px 8px;
                            border-radius: 4px;
                        ">
                            {{ $stokMasuk->warna }}
                        </span>
                    </td>
                    <td><strong class="stock-value-animated">{{ $stokMasuk->jumlah }} pcs</strong></td>
                    <td><span class="date-animated">{{ $stokMasuk->tanggal_masuk->format('d/m/Y') }}</span></td>
                    <td><small class="text-muted keterangan-animated">{{ $stokMasuk->keterangan ?? '-' }}</small></td>
                </tr>
                @empty
                <tr style="animation: fadeInUp 0.5s ease 0.7s forwards; opacity: 0;">
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-inbox me-1" style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                        Belum ada data stok masuk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Informasi Tambahan -->
<div class="mt-3 pt-3 border-top" style="animation: fadeInUp 0.5s ease 0.8s forwards; opacity: 0;">
    <div class="row">
        <div class="col-md-6">
            <small class="text-muted created-info-animated">
                <i class="bi bi-clock-history me-1"></i>
                <span class="time-created" data-timestamp="{{ $tas->created_at->timestamp }}">
                    Dibuat: {{ $tas->created_at->format('d M Y H:i') }}
                </span>
            </small>
        </div>
        <div class="col-md-6 text-end">
            <small class="text-muted updated-info-animated">
                <i class="bi bi-arrow-clockwise me-1"></i>
                <span class="time-updated" data-timestamp="{{ $tas->updated_at->timestamp }}">
                    Diupdate: {{ $tas->updated_at->format('d M Y H:i') }}
                </span>
            </small>
        </div>
    </div>
</div>

<style>
    /* Animation Keyframes */
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
    
    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
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
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes highlight {
        0% {
            background-color: rgba(52, 152, 219, 0.1);
        }
        100% {
            background-color: transparent;
        }
    }
    
    /* Info Card Animations */
    .info-card-animated {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .info-card-animated:hover {
        transform: translateY(-3px);
    }
    
    .info-card-animated:hover .info-content-animated {
        background-color: rgba(0, 0, 0, 0.02);
        border-left: 3px solid #3498db;
    }
    
    .info-content-animated {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    /* Section Title Animation */
    .section-title-animated {
        position: relative;
        padding-bottom: 8px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }
    
    .section-title-animated::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #3498db, #2ecc71);
        transition: width 0.5s ease;
    }
    
    .section-title-animated:hover::after {
        width: 100%;
    }
    
    /* Table Row Animations (Sama seperti dashboard) */
    .table-row-animated {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: left;
    }
    
    .table-row-animated:hover {
        background-color: rgba(0, 102, 204, 0.08) !important;
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Badge Animations */
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
    
    /* Value Animations */
    .stock-value-animated {
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .table-row-animated:hover .stock-value-animated {
        transform: scale(1.1);
        color: #2ecc71;
        font-weight: bold;
    }
    
    .date-animated {
        transition: all 0.3s ease;
    }
    
    .table-row-animated:hover .date-animated {
        color: #3498db;
        transform: translateX(3px);
    }
    
    .keterangan-animated {
        transition: all 0.3s ease;
    }
    
    .table-row-animated:hover .keterangan-animated {
        color: #333;
        transform: translateX(2px);
    }
    
    /* Info Text Animations */
    .created-info-animated, .updated-info-animated {
        transition: all 0.3s ease;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
    }
    
    .created-info-animated:hover {
        background-color: rgba(52, 152, 219, 0.1);
        transform: translateX(3px);
    }
    
    .updated-info-animated:hover {
        background-color: rgba(46, 204, 113, 0.1);
        transform: translateX(-3px);
    }
    
    /* Highlight important values */
    .text-primary {
        transition: all 0.3s ease;
    }
    
    .info-card-animated:hover .text-primary {
        color: #2980b9 !important;
        transform: translateX(2px);
    }
    
    .text-success {
        transition: all 0.3s ease;
    }
    
    .info-card-animated:hover .text-success {
        color: #27ae60 !important;
        transform: translateX(2px);
    }
    
    .text-info {
        transition: all 0.3s ease;
    }
    
    .info-card-animated:hover .text-info {
        color: #2980b9 !important;
        transform: translateX(2px);
    }
</style>

<script>
    // Fungsi untuk format waktu relatif (versi perbaikan)
    function timeAgo(timestamp) {
        // Pastikan timestamp valid
        if (!timestamp || isNaN(timestamp)) {
            return 'waktu tidak tersedia';
        }
        
        const now = Math.floor(Date.now() / 1000);
        const seconds = now - timestamp;
        
        if (seconds < 60) {
            return 'baru saja';
        } else if (seconds < 3600) {
            const minutes = Math.floor(seconds / 60);
            return `${minutes} menit yang lalu`;
        } else if (seconds < 86400) {
            const hours = Math.floor(seconds / 3600);
            return `${hours} jam yang lalu`;
        } else if (seconds < 2592000) {
            const days = Math.floor(seconds / 86400);
            return `${days} hari yang lalu`;
        } else if (seconds < 31536000) {
            const months = Math.floor(seconds / 2592000);
            return `${months} bulan yang lalu`;
        } else {
            const years = Math.floor(seconds / 31536000);
            return `${years} tahun yang lalu`;
        }
    }
    
    // Fungsi untuk update waktu real-time
    function updateRealTimeElements() {
        console.log('Updating real-time elements...');
        
        const createdElements = document.querySelectorAll('.time-created');
        const updatedElements = document.querySelectorAll('.time-updated');
        
        console.log('Found created elements:', createdElements.length);
        console.log('Found updated elements:', updatedElements.length);
        
        createdElements.forEach((element, index) => {
            const timestamp = element.getAttribute('data-timestamp');
            console.log(`Created ${index}:`, timestamp);
            
            if (timestamp) {
                const timestampNum = parseInt(timestamp);
                const originalText = 'Dibuat: ';
                const timeAgoText = timeAgo(timestampNum);
                
                // Format: "Dibuat: [waktu relatif] (d M Y H:i)"
                const originalDate = new Date(timestampNum * 1000);
                const formattedDate = originalDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                element.innerHTML = `${originalText}${timeAgoText} <span class="text-muted" style="font-size: 0.85em;">(${formattedDate})</span>`;
            }
        });
        
        updatedElements.forEach((element, index) => {
            const timestamp = element.getAttribute('data-timestamp');
            console.log(`Updated ${index}:`, timestamp);
            
            if (timestamp) {
                const timestampNum = parseInt(timestamp);
                const originalText = 'Diupdate: ';
                const timeAgoText = timeAgo(timestampNum);
                
                // Format: "Diupdate: [waktu relatif] (d M Y H:i)"
                const originalDate = new Date(timestampNum * 1000);
                const formattedDate = originalDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                element.innerHTML = `${originalText}${timeAgoText} <span class="text-muted" style="font-size: 0.85em;">(${formattedDate})</span>`;
            }
        });
    }
    
    // Script untuk animasi dan real-time
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - initializing quickview script');
        
        // Update awal waktu real-time
        setTimeout(function() {
            console.log('Initial real-time update');
            updateRealTimeElements();
        }, 100);
        
        // Trigger animations when modal is shown
        const modalElement = document.getElementById('detailModal');
        if (modalElement) {
            console.log('Detail modal found');
            
            modalElement.addEventListener('shown.bs.modal', function () {
                console.log('Modal shown - running animations');
                
                // Animate info cards
                const infoCards = document.querySelectorAll('.info-card-animated');
                console.log('Info cards found:', infoCards.length);
                infoCards.forEach((card, index) => {
                    setTimeout(() => {
                        card.style.animationPlayState = 'running';
                    }, index * 100);
                });
                
                // Animate table rows
                const tableRows = document.querySelectorAll('.table-row-animated');
                console.log('Table rows found:', tableRows.length);
                tableRows.forEach((row, index) => {
                    setTimeout(() => {
                        row.style.animationPlayState = 'running';
                    }, index * 50 + 500);
                });
                
                // Highlight the first row
                if (tableRows.length > 0) {
                    tableRows[0].style.animation = 'highlight 2s ease';
                    setTimeout(() => {
                        tableRows[0].style.animation = '';
                    }, 2000);
                }
                
                // Update waktu real-time setelah modal ditampilkan
                setTimeout(function() {
                    console.log('Modal real-time update');
                    updateRealTimeElements();
                    
                    // Start interval for this modal instance
                    const realTimeInterval = setInterval(function() {
                        console.log('Interval update for modal');
                        updateRealTimeElements();
                    }, 60000); // Update setiap menit
                    
                    // Clear interval when modal is hidden
                    modalElement.addEventListener('hidden.bs.modal', function() {
                        console.log('Modal hidden - clearing interval');
                        clearInterval(realTimeInterval);
                    }, { once: true });
                    
                }, 500);
            });
        } else {
            console.log('Detail modal not found');
        }
        
        // Add hover effects to badges
        document.querySelectorAll('.badge-colored').forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Add click effect to table rows
        document.querySelectorAll('.table-row-animated').forEach(row => {
            row.addEventListener('click', function() {
                this.style.animation = 'highlight 1s ease';
                setTimeout(() => {
                    this.style.animation = '';
                }, 1000);
            });
        });
        
        // Global interval untuk real-time update
        setInterval(function() {
            console.log('Global interval update');
            updateRealTimeElements();
        }, 60000);
    });
</script>