@extends('layouts.app')

@section('title', 'Pesanan Saya - Belanja.ID')

@push('styles')
<style>
    :root {
        --primary: #e74c3c;
        --primary-soft: rgba(231, 76, 60, 0.1);
        --primary-hover: #c0392b;
        --secondary: #3b82f6;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --bg-color: #f1f5f9;
        --surface: #ffffff;
        --radius-lg: 20px;
        --radius-md: 14px;
        --radius-sm: 8px;
        --shadow-soft: 0 4px 20px rgba(0,0,0,0.03);
        --shadow-hover: 0 10px 30px rgba(0,0,0,0.08);
    }

    body {
        background-color: var(--bg-color);
        font-family: 'Inter', sans-serif;
    }

    .dashboard-user {
        padding: 40px 0 100px;
    }

    /* Hero Section */
    .dashboard-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: var(--radius-lg);
        padding: 40px 48px;
        margin-bottom: 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.2);
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(231,76,60,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }
    .dashboard-hero::after {
        content: '';
        position: absolute;
        bottom: -30%; left: 10%;
        width: 250px; height: 250px;
        background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 10;
    }
    .hero-title {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .hero-subtitle {
        font-size: 15px;
        color: #94a3b8;
        font-weight: 400;
    }

    /* Order Card */
    .order-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 28px;
        margin-bottom: 24px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: var(--shadow-soft);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }
    .order-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(226, 232, 240, 1);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px dashed #e2e8f0;
    }
    .order-meta {
        display: flex;
        align-items: center;
        gap: 24px;
    }
    .meta-box {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .meta-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .meta-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-dark);
    }
    .order-id {
        color: var(--primary);
        font-family: 'SF Mono', 'Fira Code', monospace;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-pending { background: #fef3c7; color: #b45309; }
    .status-verifying { background: #fce7f3; color: #be185d; }
    .status-processing { background: #dbeafe; color: #1d4ed8; }
    .status-shipped { background: #d1fae5; color: #047857; }
    .status-success { background: #dcfce7; color: #15803d; }
    .status-cancelled { background: #fee2e2; color: #b91c1c; }

    .order-body {
        display: flex;
        gap: 32px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    /* Product Icons */
    .order-product-icons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .product-icon-wrapper {
        width: 72px;
        height: 72px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: transform 0.2s;
    }
    .product-icon-wrapper:hover {
        transform: scale(1.05);
        border-color: #cbd5e1;
    }
    .product-icon-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }
    .product-qty-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: var(--text-dark);
        color: #fff;
        font-size: 10px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .product-more {
        background: #f1f5f9;
        color: var(--text-muted);
        font-weight: 800;
        font-size: 14px;
        border: 1px dashed #cbd5e1;
    }

    /* Details */
    .order-details { 
        flex: 1; 
        min-width: 250px; 
    }
    .product-list-summary {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 8px;
        line-height: 1.4;
    }
    .product-list-extra {
        color: var(--text-muted);
        font-weight: 500;
        font-size: 13px;
        margin-left: 4px;
    }
    .order-total {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 600;
    }
    .order-total span {
        color: var(--primary);
        font-weight: 800;
        font-size: 20px;
        margin-left: 6px;
        display: inline-block;
    }

    /* Actions */
    .order-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-left: auto;
        min-width: 160px;
    }
    .btn-action {
        width: 100%;
        padding: 12px 24px;
        border-radius: var(--radius-sm);
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        transition: all 0.2s ease;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-track {
        background: var(--primary);
        color: #fff !important;
        box-shadow: 0 4px 15px rgba(231, 76, 60, 0.25);
    }
    .btn-track:hover { 
        background: var(--primary-hover); 
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.35);
    }
    .btn-outline {
        background: transparent;
        color: var(--text-dark) !important;
        border: 1px solid #e2e8f0;
    }
    .btn-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Empty State */
    .empty-orders {
        text-align: center;
        padding: 80px 20px;
        background: #fff;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
    }
    .empty-icon {
        width: 100px;
        height: 100px;
        background: #f8fafc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        color: #cbd5e1;
        font-size: 40px;
    }
    .empty-orders h2 {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 8px;
    }
    .empty-orders p {
        color: var(--text-muted);
        font-size: 14px;
        margin-bottom: 24px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-hero { padding: 32px 24px; }
        .hero-title { font-size: 24px; }
        .order-header { flex-direction: column; align-items: flex-start; gap: 16px; }
        .order-meta { flex-wrap: wrap; gap: 16px; }
        .order-body { flex-direction: column; align-items: flex-start; gap: 20px; }
        .order-actions { width: 100%; margin-left: 0; }
        .btn-action { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="dashboard-user">
    <div class="container">
        
        <div class="dashboard-hero">
            <div class="hero-content">
                <h1 class="hero-title">Pesanan Saya</h1>
                <p class="hero-subtitle">Kelola dan lacak semua transaksi Anda di sini</p>
            </div>
            <div class="hero-icon" style="font-size: 48px; opacity: 0.1; position: relative; z-index: 10;">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>

        @if($orders->count() > 0)
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-meta">
                            <div class="meta-box">
                                <span class="meta-label">No. Pesanan</span>
                                <span class="meta-value order-id">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="meta-box">
                                <span class="meta-label">Tanggal Beli</span>
                                <span class="meta-value">{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        @php
                            $statusInfo = [
                                'pending' => ['label' => 'Menunggu Pembayaran', 'icon' => 'fa-clock'],
                                'verifying' => ['label' => 'Verifikasi', 'icon' => 'fa-search'],
                                'processing' => ['label' => 'Diproses', 'icon' => 'fa-box-open'],
                                'shipped' => ['label' => 'Dikirim', 'icon' => 'fa-truck'],
                                'success' => ['label' => 'Selesai', 'icon' => 'fa-check-circle'],
                                'cancelled' => ['label' => 'Dibatalkan', 'icon' => 'fa-times-circle'],
                            ];
                            $stat = $statusInfo[$order->status] ?? ['label' => $order->status, 'icon' => 'fa-info-circle'];
                        @endphp
                        <span class="status-badge status-{{ $order->status }}">
                            <i class="fas {{ $stat['icon'] }}"></i> {{ $stat['label'] }}
                        </span>
                    </div>
                    <div class="order-body">
                        <div class="order-product-icons">
                            @foreach($order->items->take(4) as $item)
                                <div class="product-icon-wrapper" title="{{ $item->product_name }}">
                                    @if($item->product_image)
                                        <img src="{{ Str::startsWith($item->product_image, 'http') ? $item->product_image : asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}">
                                    @else
                                        <i class="fas fa-box" style="color: #cbd5e1; font-size: 24px;"></i>
                                    @endif
                                    @if($item->quantity > 1)
                                        <span class="product-qty-badge">{{ $item->quantity }}</span>
                                    @endif
                                </div>
                            @endforeach
                            @if($order->items->count() > 4)
                                <div class="product-icon-wrapper product-more">
                                    +{{ $order->items->count() - 4 }}
                                </div>
                            @endif
                        </div>
                        <div class="order-details">
                            <div class="product-list-summary">
                                @php
                                    $firstItem = $order->items->first();
                                    $count = $order->items->count();
                                @endphp
                                {{ $firstItem->product_name }} 
                                @if($count > 1)
                                    <span class="product-list-extra">dan {{ $count - 1 }} produk lainnya</span>
                                @endif
                            </div>
                            <div class="order-total">
                                Total Belanja <br> <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="order-actions">
                            @if($order->status == 'pending')
                                <a href="{{ route('checkout.payment', $order->id) }}" class="btn-action btn-track">
                                    <i class="fas fa-wallet"></i> Bayar Sekarang
                                </a>
                            @endif
 
                            @if($order->status == 'success')
                                <div class="btn-action btn-outline" style="background: #f0fdf4; border-color: #bbf7d0; color: #16a34a !important; cursor: default;">
                                    <i class="fas fa-check-double"></i> Pesanan sudah diterima
                                </div>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn-action btn-outline" style="margin-top: 4px; font-size: 12px; padding: 8px;">
                                    Lihat Detail
                                </a>
                            @elseif(in_array($order->status, ['processing', 'shipped']))
                                <a href="{{ route('orders.show', $order->id) }}" class="btn-action btn-track">
                                    <i class="fas fa-search-location"></i> Lacak Pesanan
                                </a>
                            @else
                                <a href="{{ route('orders.show', $order->id) }}" class="btn-action btn-outline">
                                    <i class="fas fa-file-invoice"></i> Detail Pesanan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-orders">
                <div class="empty-icon"><i class="fas fa-box-open"></i></div>
                <h2>Belum ada pesanan</h2>
                <p>Anda belum memiliki riwayat transaksi atau pesanan apa pun.</p>
                <a href="{{ route('home') }}" class="btn-action btn-track" style="margin-top: 10px; width: auto;">
                    <i class="fas fa-shopping-cart"></i> Mulai Belanja Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
