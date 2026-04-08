@extends('layouts.app')

@section('title', $title . ' - Belanja.ID')

@push('styles')
<style>
    :root {
        --primary: #e74c3c;
        --primary-soft: rgba(231, 76, 60, 0.1);
        --text-main: #0f172a;
        --text-sub: #64748b;
        --bg-color: #f8fafc;
        --surface: #ffffff;
        --radius-xl: 20px;
        --radius-lg: 16px;
        --radius-md: 12px;
        --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.05);
        --shadow-md: 0 10px 20px -5px rgba(0,0,0,0.08);
        --border-light: #e2e8f0;
    }

    .category-page {
        padding: 40px 0 100px;
        background: var(--bg-color);
        font-family: 'Inter', sans-serif;
    }

    /* Category Header Redesign */
    .category-header-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: var(--radius-xl);
        padding: 48px;
        margin-bottom: 48px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }
    .category-header-banner::before {
        content: ''; position: absolute; right: 0; top: 0; height: 100%; width: 50%;
        background: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="80" cy="20" r="40" fill="white" fill-opacity="0.05"/><circle cx="90" cy="80" r="20" fill="white" fill-opacity="0.05"/></svg>') right center no-repeat;
        background-size: cover; z-index: 1;
    }
    .category-header-content { position: relative; z-index: 2; }

    .breadcrumb {
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
    }
    .breadcrumb a { color: #94a3b8; text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: #fff; }
    .breadcrumb .active { color: #fff; background: rgba(255,255,255,0.1); padding: 4px 12px; border-radius: 20px;}

    .category-title {
        font-size: 36px;
        font-weight: 900;
        letter-spacing: -0.5px;
        margin: 0;
    }

    /* Grid layout */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }

    /* Product Card Aesthetics */
    .product-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 16px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .product-card:hover { border-color: rgba(226,232,240,1); transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .product-image-container { border-radius: var(--radius-md); background: #f8fafc; border: none; }
    .badge-tag { border-radius: 8px; font-weight: 800; font-size: 12px; padding: 6px 12px; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 24px;
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-light);
    }
    .empty-state-icon {
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
    .empty-state h3 {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 12px;
    }
    .empty-state p {
        color: var(--text-sub);
        font-size: 15px;
        margin-bottom: 32px;
        font-weight: 500;
    }
    .btn-back-home {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 32px;
        background: var(--primary);
        color: #fff;
        border-radius: var(--radius-md);
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.2s;
        box-shadow: 0 4px 15px var(--primary-soft);
    }
    .btn-back-home:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
    }

/* Responsive */
    @media (max-width: 1024px) {
        .products-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .category-header-banner { padding: 32px 24px; }
        .category-title { font-size: 28px; }
        .products-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
    }
    @media (max-width: 480px) {
        .products-grid { grid-template-columns: 1fr; }
    }

    /* Animations for smooth entrance */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes revealBanner {
        from { opacity: 0; transform: scale(0.98); }
        to { opacity: 1; transform: scale(1); }
    }
    
    .category-header-banner {
        animation: revealBanner 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    .product-card {
        opacity: 0;
        animation: fadeInUp 0.5s ease-out forwards;
    }
    
    /* Staggered animation delay for cards */
    .product-card:nth-child(1) { animation-delay: 0.1s; }
    .product-card:nth-child(2) { animation-delay: 0.15s; }
    .product-card:nth-child(3) { animation-delay: 0.2s; }
    .product-card:nth-child(4) { animation-delay: 0.25s; }
    .product-card:nth-child(5) { animation-delay: 0.3s; }
    .product-card:nth-child(6) { animation-delay: 0.35s; }
    .product-card:nth-child(7) { animation-delay: 0.4s; }
    .product-card:nth-child(8) { animation-delay: 0.45s; }
</style>
@endpush

@section('content')
<div class="category-page">
    <div class="container">
        <!-- New Modern Header -->
        <div class="category-header-banner">
            <div class="category-header-content">
                <div class="breadcrumb">
                    <a href="/"><i class="fas fa-home"></i> Beranda</a>
                    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                    <span class="active">{{ $title }}</span>
                </div>
                <h1 class="category-title">{{ $title }}</h1>
            </div>
        </div>

        @if(count($products) > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3>Koleksi Belum Tersedia</h3>
                <p>Maaf, produk untuk kategori dan pencarian ini belum tersedia atau sedang kosong. Coba kembali beberapa saat lagi!</p>
                <a href="/" class="btn-back-home"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
            </div>
        @endif
    </div>
</div>
@endsection
