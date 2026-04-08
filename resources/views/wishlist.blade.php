@extends('layouts.app')

@section('title', 'Wishlist Tersimpan - Belanja.ID')

@push('styles')
<style>
    :root {
        --primary: #e74c3c;
        --text-main: #0f172a;
        --text-sub: #64748b;
        --bg-color: #f8fafc;
        --surface: #ffffff;
        --radius-lg: 16px;
        --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.05);
        --border-light: #e2e8f0;
    }

    .wishlist-page {
        padding: 40px 0 100px;
        background: var(--bg-color);
        font-family: 'Inter', sans-serif;
    }
    
    .breadcrumb {
        margin-bottom: 24px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-sub);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .breadcrumb a { color: var(--text-sub); text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: var(--primary); }
    .breadcrumb .active { color: var(--primary); }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }
    .section-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-main);
        display: flex; align-items: center; gap: 10px;
    }

    .btn-outline-custom {
        padding: 12px 28px;
        border: 2px solid var(--border-light);
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        text-decoration: none;
        transition: all 0.2s;
        background: var(--surface);
    }
    .btn-outline-custom:hover {
        background: var(--text-main);
        color: #fff;
        border-color: var(--text-main);
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 60px;
    }

    /* Product Card Enhancements (Scoped Override) */
    .product-card {
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        padding: 16px;
        background: var(--surface);
    }
    .product-card:hover { border-color: rgba(226, 232, 240, 1); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.08); }
    .product-image-container { border-radius: 12px; background: #f8fafc; border: none; }
    .badge-tag { border-radius: 8px; font-weight: 800; font-size: 12px; padding: 6px 12px; }

    .section-for-you {
        margin-top: 60px;
        padding-top: 40px;
        border-top: 1px dashed var(--border-light);
    }
    .section-label {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .label-block {
        width: 16px;
        height: 32px;
        background: var(--primary);
        border-radius: 6px;
    }
    .label-text {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-main);
    }

    /* Empty State */
    .empty-wishlist {
        text-align: center; padding: 60px 20px;
        background: var(--surface); border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm); border: 1px solid var(--border-light);
        margin-bottom: 60px;
    }
    .empty-icon {
        width: 100px; height: 100px; background: #fef2f2; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
        color: var(--primary); font-size: 40px;
    }

    @media (max-width: 992px) { .product-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; } }
    @media (max-width: 576px) { .product-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="wishlist-page">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="/"><i class="fas fa-home"></i> Beranda</a>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span class="active">Favorit Saya</span>
        </div>

        <!-- Wishlist Section -->
        <div class="section-header">
            <h1 class="section-title">
                Item Favorit ({{ count($wishlistItems) }})
            </h1>
            <a href="{{ route('cart.index') }}" class="btn-outline-custom"><i class="fas fa-shopping-cart"></i> Lihat Keranjang</a>
        </div>

        @if(count($wishlistItems) > 0)
            <div class="product-grid">
                @foreach($wishlistItems as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @else
            <div class="empty-wishlist">
                <div class="empty-icon"><i class="fas fa-heart"></i></div>
                <h3 style="font-size: 22px; font-weight: 800; color: var(--text-main); margin-bottom: 12px;">Wishlist Anda Kosong</h3>
                <p style="color: var(--text-sub); margin-bottom: 24px;">Silakan jelajahi produk kami dan klik lambang cinta (❤️) pada produk yang Anda sukai.</p>
                <a href="/" class="btn-outline-custom" style="display:inline-block; border-color: var(--primary); background: var(--primary); color: #fff;">Mulai Penjelajahan</a>
            </div>
        @endif

        <!-- Recommendation Section -->
        <div class="section-for-you">
            <div class="section-header" style="margin-bottom: 24px;">
                <div class="section-label">
                    <div class="label-block"></div>
                    <span class="label-text">Rekomendasi Untukmu</span>
                </div>
                <a href="/" class="btn-outline-custom">Lihat Semua</a>
            </div>

            <div class="product-grid">
                @foreach($recommendations as $index => $product)
                    <x-product-card :product="$product" :class="$index == 1 ? 'show-cart' : ''" />
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
