@extends('layouts.admin')

@section('title', 'Petugas Dashboard - Belanja.ID')
@section('page_title', 'Kelola Operasional')

@section('content')
    <div class="dashboard-wrapper">
        {{-- Greeting Section --}}
        <div class="greeting-bar">
            <div class="greeting-text">
                @php
                    $hour = date('H');
                    $greeting = 'Selamat Pagi';
                    if ($hour >= 11 && $hour < 15)
                        $greeting = 'Selamat Siang';
                    elseif ($hour >= 15 && $hour < 19)
                        $greeting = 'Selamat Sore';
                    elseif ($hour >= 19 || $hour < 5)
                        $greeting = 'Selamat Malam';
                @endphp
                <h1>{{ $greeting }}, {{ Auth::user()->name }}! 👋</h1>
                <p>Berikut adalah ringkasan operasional toko hari ini, {{ date('d M Y') }}.</p>
            </div>
            <div class="quick-actions">
                <a href="{{ route('admin.products') }}" class="action-btn primary">
                    <i class="fas fa-plus"></i> <span>Produk Baru</span>
                </a>
                <a href="{{ route('admin.stock_requests') }}" class="action-btn secondary">
                    <i class="fas fa-clipboard-list"></i> <span>Minta Stok</span>
                </a>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="glass-card stat-item">
                <div class="stat-icon yellow">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-details">
                    <span class="label">Produk Aktif</span>
                    <h3>{{ $activeProductsCount }}</h3>
                    <div class="trend up"><i class="fas fa-check-circle"></i> Live di Katalog</div>
                </div>
            </div>

            <div class="glass-card stat-item">
                <div class="stat-icon red">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-details">
                    <span class="label">Stok Menipis</span>
                    <h3>{{ $lowStockCount }}</h3>
                    <div class="trend down"><i class="fas fa-arrow-down"></i> Perlu Restock</div>
                </div>
            </div>

            <div class="glass-card stat-item">
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-details">
                    <span class="label">Pesanan Baru</span>
                    <h3>{{ $pendingOrdersCount }}</h3>
                    <div class="trend info"><i class="fas fa-spinner fa-spin"></i> Menunggu Konfirmasi</div>
                </div>
            </div>

            <div class="glass-card stat-item">
                <div class="stat-icon blue">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="stat-details">
                    <span class="label">Siap Dikirim</span>
                    <h3>{{ $processingOrdersCount }}</h3>
                    <div class="trend blue"><i class="fas fa-box"></i> Perlu Packing</div>
                </div>
            </div>
        </div>

        <div class="main-grid">
            {{-- Low Stock Products --}}
            <div class="glass-card content-section">
                <div class="section-header">
                    <h3><i class="fas fa-cubes"></i> Daftar Stok Menipis</h3>
                    <a href="{{ route('admin.products') }}" class="view-all">Lihat Semua</a>
                </div>

                <div class="stock-list">
                    @forelse($lowStockProducts as $product)
                        <div class="stock-card">
                            <div class="product-info">
                                <img src="{{ Str::startsWith($product->main_image, 'http') ? $product->main_image : asset('storage/' . $product->main_image) }}"
                                    onerror="this.src='https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&h=100&fit=crop'"
                                    alt="{{ $product->name }}">
                                <div class="text">
                                    <h4>{{ $product->name }}</h4>
                                    <p>SKU: #{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                            <div class="stock-status">
                                <span class="badge {{ $product->stock <= 2 ? 'danger' : 'warning' }}">{{ $product->stock }}
                                    Sisa</span>
                                <a href="{{ route('admin.stock_requests') }}" class="restock-link">Restock</a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-check-circle"></i>
                            <p>Semua stok produk mencukupi.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Activity Feed --}}
            <div class="glass-card content-section">
                <div class="section-header">
                    <h3><i class="fas fa-history"></i> Aktivitas Terakhir</h3>
                    <a href="{{ route('admin.orders') }}" class="view-all">Semua Pesanan</a>
                </div>

                <div class="activity-timeline">
                    @forelse($recentActivities as $activity)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="time">{{ $activity->created_at->diffForHumans() }}</div>
                                <div class="description">
                                    Pesanan <strong>#ORD-{{ $activity->id }}</strong>
                                    <span class="status-pill {{ $activity->status }}">{{ ucfirst($activity->status) }}</span>
                                </div>
                                <div class="customer">Customer: {{ $activity->full_name }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <p>Belum ada aktivitas hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary: #e74c3c;
            --primary-soft: #fef2f2;
            --secondary: #34495e;
            --success: #2ecc71;
            --warning: #f1c40f;
            --danger: #e74c3c;
            --info: #3498db;
            --text-main: #2c3e50;
            --text-muted: #7f8c8d;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.5);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }

        .dashboard-wrapper {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        }

        /* Greeting Bar */
        .greeting-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .greeting-text h1 {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .greeting-text p {
            color: var(--text-muted);
            font-size: 15px;
        }

        .quick-actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .action-btn.primary {
            background: var(--primary);
            color: #fff;
        }

        .action-btn.secondary {
            background: #fff;
            color: var(--text-main);
            border: 1px solid #eee;
        }

        .action-btn:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-item {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.yellow {
            background: rgba(241, 196, 15, 0.1);
            color: var(--warning);
        }

        .stat-icon.red {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }

        .stat-icon.purple {
            background: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
        }

        .stat-icon.blue {
            background: rgba(52, 152, 219, 0.1);
            color: var(--info);
        }

        .stat-details .label {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .stat-details h3 {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-main);
            margin: 2px 0;
        }

        .trend {
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .trend.up {
            color: var(--success);
        }

        .trend.down {
            color: var(--danger);
        }

        .trend.info {
            color: #9b59b6;
        }

        .trend.blue {
            color: var(--info);
        }

        /* Main Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .content-section {
            padding: 24px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .view-all {
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        /* Stock List */
        .stock-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .stock-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: #fcfcfc;
            border-radius: 14px;
            border: 1px solid #f0f0f0;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .product-info img {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
        }

        .product-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
        }

        .product-info p {
            font-size: 11px;
            color: var(--text-muted);
        }

        .stock-status {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge.danger {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }

        .badge.warning {
            background: rgba(241, 196, 15, 0.1);
            color: #d68910;
        }

        .restock-link {
            font-size: 11px;
            color: var(--info);
            text-decoration: none;
            font-weight: 600;
        }

        /* Activity Timeline */
        .activity-timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .timeline-item {
            display: flex;
            gap: 16px;
            padding-bottom: 24px;
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 20px;
            bottom: 0;
            width: 2px;
            background: #f0f0f0;
        }

        .timeline-marker {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--primary);
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px var(--primary-soft);
            z-index: 1;
            margin-top: 4px;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-content .time {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .timeline-content .description {
            font-size: 14px;
            color: var(--text-main);
        }

        .status-pill {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 700;
            margin-left: 4px;
        }

        .status-pill.pending {
            background: #fff7e6;
            color: #d68910;
        }

        .status-pill.processing {
            background: #e6f7ff;
            color: #1890ff;
        }

        .status-pill.success {
            background: #f6ffed;
            color: #52c41a;
        }

        .customer {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 12px;
            opacity: 0.3;
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .greeting-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .quick-actions {
                width: 100%;
            }

            .action-btn {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
@endsection