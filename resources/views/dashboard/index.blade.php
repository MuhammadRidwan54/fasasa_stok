@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header" style="animation: fadeInUp 0.6s ease forwards; opacity: 0;">
    <div>
        <h1 class="page-title" style="animation: slideInFromLeft 0.5s ease 0.2s forwards; opacity: 0;">Dashboard</h1>
        <p class="page-subtitle" style="animation: slideInFromLeft 0.5s ease 0.3s forwards; opacity: 0;">Overview statistik stok dan penjualan</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4" style="margin-bottom: 24px;">
    @php
        $statCards = [
            [
                'icon' => 'bi-box',
                'label' => 'Total Stok',
                'value' => $totalStok ?? 0,
                'color' => '#3498db',
                'animationDelay' => '0.1s',
                'prefix' => '',
                'suffix' => ''
            ],
            [
                'icon' => 'bi-tags',
                'label' => 'Total Model',
                'value' => $totalModel ?? 0,
                'color' => '#2ecc71',
                'animationDelay' => '0.2s',
                'prefix' => '',
                'suffix' => ''
            ],
            [
                'icon' => 'bi-cash-stack',
                'label' => 'Nilai Stok',
                'value' => $nilaiStok ?? 0,
                'color' => '#f39c12',
                'animationDelay' => '0.3s',
                'prefix' => 'Rp ',
                'suffix' => ',00'
            ],
            [
                'icon' => 'bi-exclamation-triangle',
                'label' => 'Stok Kritis',
                'value' => $stokKritis ?? 0,
                'color' => '#e74c3c',
                'animationDelay' => '0.4s',
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
                 id="dashboard-stat-card-{{ $index }}" 
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

<!-- Charts -->
<div class="row mb-4">
    <div class="col-md-6" style="animation: fadeInUp 0.6s ease 0.5s forwards; opacity: 0;">
        <div class="card card-animated">
            <div class="card-header">
                <i class="bi bi-graph-up me-1"></i> Top 5 Model Stok Terbanyak
            </div>
            <div class="card-body">
                <canvas id="stokChart" height="80"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6" style="animation: fadeInUp 0.6s ease 0.6s forwards; opacity: 0;">
        <div class="card card-animated">
            <div class="card-header">
                <i class="bi bi-pie-chart me-1"></i> Status Stok
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row" style="animation: fadeInUp 0.6s ease 0.7s forwards; opacity: 0;">
    <div class="col-md-12">
        <div class="card card-animated">
            <div class="card-header">
                <i class="bi bi-clock-history me-1"></i> Aktivitas Terbaru
            </div>
            <div class="card-body">
                @if(isset($recentTransactions) && count($recentTransactions) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="80">Tanggal</th>
                                    <th width="120">Kode Tas</th>
                                    <th width="150">Nama Tas</th>
                                    <th width="80">Platform</th>
                                    <th width="80">Qty Terjual</th>
                                    <th width="120">Total</th>
                                    <th width="80">Stok Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $index => $item)
                                <tr class="table-row-animated" style="animation: fadeInUp 0.4s ease {{ $index * 0.1 + 0.8 }}s forwards; opacity: 0;">
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td><strong>{{ $item->tas->kode_tas }}#</strong></td>
                                    <td>{{ $item->tas->nama_tas }}</td>
                                    <td>
                                        <span class="badge badge-info platform-badge">
                                            @switch($item->platform)
                                                @case('shopee')
                                                    <i class="bi bi-shop"></i> Shopee
                                                    @break
                                                @case('tiktok')
                                                    <i class="bi bi-tiktok"></i> TikTok
                                                    @break
                                                @case('offline')
                                                    <i class="bi bi-shop-window"></i> Offline
                                                    @break
                                                @default
                                                    <i class="bi bi-three-dots"></i> Lainnya
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>{{ $item->jumlah_terjual }} pcs</td>
                                    <td>Rp {{ number_format($item->jumlah_terjual * $item->tas->harga, 0, ',', '.') }},00</td>
                                    <td>
                                        @if($item->sisa_stok > 10)
                                            <span class="badge badge-success badge-pulse">{{ $item->sisa_stok }}</span>
                                        @elseif($item->sisa_stok > 0)
                                            <span class="badge badge-warning badge-pulse">{{ $item->sisa_stok }}</span>
                                        @else
                                            <span class="badge badge-danger badge-pulse">0</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px 20px; color: #999;" class="empty-state-animated">
                        <i class="bi bi-inbox" style="font-size: 28px; display: block; margin-bottom: 8px;"></i>
                        <p>Belum ada aktivitas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS tambahan untuk animasi angka pada Dashboard */
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

    /* Animasi khusus untuk dashboard stat cards */
    .dashboard-stat-number {
        font-size: 24px;
        font-weight: 600;
    }


    /* Animations for Dashboard */
    @keyframes slideInFromLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
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
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-5px);
        }
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
    
    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }
        100% {
            background-position: 200px 0;
        }
    }
    
    /* Card Animations */
    .card-animated {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e5e5e5;
    }
    
    .card-animated:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #00000020;
    }
    
    .card-header {
        transition: background-color 0.3s ease;
    }
    
    .card-animated:hover .card-header {
        background-color: #00000008;
    }
    
    /* Stat Card Animations */
    .stat-card-animated {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-animated:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card-animated:hover .stat-card-icon {
        transform: scale(1.1) rotate(5deg);
        color: #000;
    }
    
    .stat-card-animated:hover .stat-card-number {
        transform: scale(1.05);
    }
    
    .stat-card-icon {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .stat-card-number {
        transition: all 0.3s ease;
    }
    
    .stat-card-animated::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s ease;
    }
    
    .stat-card-animated:hover::before {
        left: 100%;
    }
    
    /* Table Row Animations */
    .table-row-animated {
        transition: all 0.3s ease;
        transform-origin: left;
    }
    
    .table-row-animated:hover {
        background-color: rgba(0, 102, 204, 0.08) !important;
        transform: translateX(5px);
    }
    
    /* Badge Animations */
    .badge-pulse {
        animation: pulse 2s infinite;
        transition: all 0.3s ease;
    }
    
    .badge-pulse:hover {
        animation: none;
        transform: scale(1.1);
    }
    
    .platform-badge {
        transition: all 0.3s ease;
    }
    
    .platform-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Empty State Animation */
    .empty-state-animated {
        animation: fadeInUp 0.8s ease 0.5s forwards;
        opacity: 0;
    }
    
    .empty-state-animated i {
        animation: float 3s ease-in-out infinite;
    }
    
    /* Loading shimmer effect for cards */
    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }
</style>

@section('scripts')
<script>
    // Fungsi animasi counter untuk Dashboard
    function animateDashboardValue(element, start, end, duration) {
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
    
    // Fungsi untuk inisialisasi animasi counter di Dashboard
    function initializeDashboardCounterAnimations() {
        const statNumbers = document.querySelectorAll('.stat-card-number[id^="dashboard-stat-card-"]');
        
        statNumbers.forEach((element, index) => {
            const finalValue = parseFloat(element.getAttribute('data-value')) || 0;
            const prefix = element.getAttribute('data-prefix') || '';
            const suffix = element.getAttribute('data-suffix') || '';
            
            // Set delay berdasarkan posisi (lebih cepat karena dashboard)
            const delay = 100 + (index * 100);
            
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
                animateDashboardValue(element, 0, finalValue, 1200);
                
                // Add glow effect setelah animasi selesai
                setTimeout(() => {
                    element.classList.add('glow-effect');
                    setTimeout(() => {
                        element.classList.remove('glow-effect');
                    }, 1000);
                }, 1300);
                
            }, delay);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi animasi counter untuk Dashboard
        console.log('DOM Loaded - Initializing Dashboard counter animations');
        setTimeout(initializeDashboardCounterAnimations, 100);
        
        // Animate page elements on load
        animateDashboard();
        
        // Add hover effects to cards
        initCardHoverEffects();
        
        // Initialize charts
        initCharts();
        
        // Re-run animations when navigating back to page
        if (performance.navigation.type === performance.navigation.TYPE_BACK_FORWARD) {
            setTimeout(initializeDashboardCounterAnimations, 500);
        }
    });
    
    function animateDashboard() {
        // Animate stat cards one by one
        const statCards = document.querySelectorAll('.stat-card-animated');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.animationPlayState = 'running';
            }, index * 100);
        });
        
        // Animate table rows with staggered delay
        const tableRows = document.querySelectorAll('.table-row-animated');
        tableRows.forEach((row, index) => {
            setTimeout(() => {
                row.style.animationPlayState = 'running';
            }, index * 50 + 800);
        });
    }
    
    function initCardHoverEffects() {
        const cards = document.querySelectorAll('.card-animated');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
                
                // Add slight rotation to header icon
                const icon = this.querySelector('.card-header i');
                if (icon) {
                    icon.style.transform = 'rotate(10deg)';
                    setTimeout(() => {
                        icon.style.transform = 'rotate(0deg)';
                    }, 300);
                }
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    }
    
    function initCharts() {
        // Chart for Top 5 Model Stok
        const stokCtx = document.getElementById('stokChart')?.getContext('2d');
        if (stokCtx) {
            new Chart(stokCtx, {
                type: 'bar',
                data: {
                    labels: @json($topModels->pluck('nama_tas') ?? []),
                    datasets: [{
                        label: 'Jumlah Stok',
                        data: @json($topModels->pluck('stok') ?? []),
                        backgroundColor: [
                            '#3498db', '#2ecc71', '#f39c12', '#e74c3c', '#9b59b6'
                        ],
                        borderColor: [
                            '#2980b9', '#27ae60', '#e67e22', '#c0392b', '#8e44ad'
                        ],
                        borderWidth: 1,
                        borderRadius: 4,
                        hoverBackgroundColor: [
                            '#2980b9', '#27ae60', '#e67e22', '#c0392b', '#8e44ad'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Stok: ' + context.raw + ' pcs';
                                }
                            },
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#000',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: { size: 11 },
                                callback: function(value) {
                                    return value + ' pcs';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                font: { size: 10 }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }
        
        // Chart for Status Distribution
        const statusCtx = document.getElementById('statusChart')?.getContext('2d');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Aman (>5)', 'Menipis (1-5)', 'Kritis (0)'],
                    datasets: [{
                        data: [
                            @json($stokAman ?? 0),
                            @json($stokMenipis ?? 0),
                            @json($stokKritis ?? 0)
                        ],
                        backgroundColor: ['#2ecc71', '#f39c12', '#e74c3c'],
                        borderColor: ['#27ae60', '#e67e22', '#c0392b'],
                        borderWidth: 2,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 11 },
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + ' item';
                                }
                            },
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    }
    
    // Add real-time updates simulation (optional)
    function simulateRealTimeUpdates() {
        setInterval(() => {
            // Animate stat cards every 30 seconds
            const statCards = document.querySelectorAll('.stat-card-animated');
            statCards.forEach(card => {
                card.style.transform = 'translateY(-5px) scale(1.02)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 1000);
            });
        }, 30000);
    }
    
    // Start real-time updates (optional - uncomment if needed)
    // simulateRealTimeUpdates();
    
    // Add click animation for table rows
    document.querySelectorAll('.table-row-animated').forEach(row => {
        row.addEventListener('click', function() {
            this.style.backgroundColor = 'rgba(52, 152, 219, 0.1)';
            setTimeout(() => {
                this.style.backgroundColor = '';
            }, 300);
        });
    });
    
    // Debugging untuk memastikan data stat cards dimuat
    console.log('Dashboard page loaded with stat cards data');
    setTimeout(() => {
        const statNumbers = document.querySelectorAll('.stat-card-number[id^="dashboard-stat-card-"]');
        statNumbers.forEach((el, i) => {
            console.log(`Dashboard Card ${i}:`, {
                dataValue: el.getAttribute('data-value'),
                dataPrefix: el.getAttribute('data-prefix'),
                dataSuffix: el.getAttribute('data-suffix'),
                innerHTML: el.innerHTML
            });
        });
    }, 500);
</script>
@endsection
@endsection