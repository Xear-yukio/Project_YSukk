@extends('layouts.app')

@section('title', $product->name . ' - Belanja.ID')

@push('styles')
<style>
    :root {
        --primary: #e74c3c;
        --primary-soft: rgba(231, 76, 60, 0.08);
        --text-main: #0f172a;
        --text-sub: #64748b;
        --surface: #ffffff;
        --bg-light: #f8fafc;
        --border-color: #e2e8f0;
        --radius-lg: 16px;
        --radius-md: 12px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .product-detail-page {
        padding: 40px 0 80px;
        background: var(--bg-light);
        min-height: 100vh;
    }

    /* Breadcrumb */
    .breadcrumb-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 32px;
        font-size: 14px;
        color: var(--text-sub);
    }
    .breadcrumb-custom a { color: var(--text-sub); transition: color 0.2s; }
    .breadcrumb-custom a:hover { color: var(--primary); }
    .breadcrumb-custom .active { color: var(--text-main); font-weight: 600; }
    .breadcrumb-custom i { font-size: 10px; opacity: 0.5; }

    /* Layout Wrapper */
    .product-main-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: 40px;
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 60px;
        margin-bottom: 60px;
        border: 1px solid var(--border-color);
    }

    /* Gallery Section */
    .gallery-container {
        display: flex;
        gap: 20px;
    }
    .thumbnail-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .thumb-item {
        width: 80px;
        height: 80px;
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 8px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fff;
    }
    .thumb-item:hover, .thumb-item.active {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.15);
    }
    .thumb-item img { width: 100%; height: 100%; object-fit: contain; }

    .main-preview {
        flex: 1;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
        min-height: 480px;
        position: relative;
        overflow: hidden;
    }
    .main-preview img {
        max-width: 100%;
        max-height: 420px;
        object-fit: contain;
        transition: transform 0.5s ease;
    }
    .main-preview:hover img {
        transform: scale(1.05);
    }

    /* Info Panel */
    .info-panel { display: flex; flex-direction: column; }
    
    .product-header { margin-bottom: 24px; }
    .product-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -1px;
        margin-bottom: 12px;
        line-height: 1.2;
    }
    
    .meta-row {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .rating-badge {
        display: flex;
        align-items: center;
        gap: 4px;
        background: #fff8e6;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid #fee2e2;
    }
    .rating-badge i { color: #f59e0b; font-size: 13px; }
    .rating-badge span { font-weight: 700; font-size: 14px; color: #b45309; }
    .review-count { font-size: 14px; color: var(--text-sub); }
    .stock-pill {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #10b981;
        background: #ecfdf5;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .info-panel .price-section {
        margin-bottom: 24px;
        padding: 20px;
        background: var(--bg-light);
        border-radius: var(--radius-md);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .info-panel .current-price {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: -1px;
    }
    .info-panel .old-price-row { display: flex; align-items: center; gap: 10px; }
    .info-panel .old-price { font-size: 14px; text-decoration: line-through; color: var(--text-sub); }
    .info-panel .discount-tag {
        background: var(--primary);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .info-panel .description-box {
        margin-bottom: 24px;
        color: var(--text-sub);
        font-size: 14px;
        line-height: 1.6;
        max-width: 100%;
    }

    .variant-section { margin-bottom: 32px; }
    .variant-label {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 12px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .color-options { display: flex; gap: 12px; }
    .color-item {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid #fff;
        box-shadow: 0 0 0 1px var(--border-color);
        transition: all 0.2s;
    }
    .color-item.active { box-shadow: 0 0 0 2px var(--primary); transform: scale(1.1); }

    .action-section {
        display: flex;
        gap: 16px;
        align-items: center;
        margin-bottom: 40px;
    }
    .qty-control {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        height: 54px;
        overflow: hidden;
    }
    .qty-btn {
        width: 50px;
        height: 100%;
        border: none;
        background: transparent;
        color: var(--text-main);
        font-size: 18px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .qty-btn:hover { background: var(--bg-light); color: var(--primary); }
    .qty-input {
        width: 60px;
        text-align: center;
        border: none;
        font-weight: 800;
        font-size: 18px;
        color: var(--text-main);
        background: transparent;
        pointer-events: none;
    }

    .btn-checkout {
        flex: 1;
        height: 54px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 14px rgba(231, 76, 60, 0.3);
    }
    .btn-checkout:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
    }

    .btn-action-outline {
        width: 54px;
        height: 54px;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-main);
        font-size: 20px;
        transition: all 0.2s;
        background: #fff;
        cursor: pointer;
    }
    .btn-action-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-soft);
    }
    .btn-action-outline.wishlisted { color: var(--primary); border-color: var(--primary); }

    /* Trust Badges */
    .trust-badges {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        padding-top: 32px;
        border-top: 1px solid var(--border-color);
    }
    .trust-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .trust-icon {
        width: 40px;
        height: 40px;
        background: var(--bg-light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-main);
        font-size: 16px;
    }
    .trust-text { display: flex; flex-direction: column; }
    .trust-title { font-size: 13px; font-weight: 700; color: var(--text-main); }
    .trust-desc { font-size: 11px; color: var(--text-sub); }

    /* Reviews Section (Compact Version) */
    .product-reviews-section {
        margin-bottom: 60px;
        padding-top: 40px;
        border-top: 1px solid var(--border-color);
    }
    .reviews-card {
        background: #fff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        padding: 30px;
        box-shadow: none;
    }
    .reviews-header {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 40px;
        margin-bottom: 32px;
        padding-bottom: 32px;
        border-bottom: 1px solid var(--border-color);
    }
    .rating-summary {
        text-align: center;
        background: var(--bg-light);
        padding: 24px;
        border-radius: var(--radius-md);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
    }
    .summary-score {
        font-size: 48px;
        font-weight: 900;
        color: var(--text-main);
        line-height: 1;
        letter-spacing: -1.5px;
    }
    .summary-stars {
        color: #f59e0b;
        font-size: 14px;
        margin: 12px 0 6px;
        display: flex;
        gap: 3px;
    }
    .summary-total {
        font-size: 12px;
        color: var(--text-sub);
        font-weight: 600;
    }

    .rating-bars {
        display: flex;
        flex-direction: column;
        gap: 10px;
        justify-content: center;
    }
    .bar-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .bar-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-main);
        width: 65px;
        white-space: nowrap;
    }
    .progress-bar-bg {
        flex: 1;
        height: 6px;
        background: var(--bg-light);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.02);
    }
    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(to right, #f59e0b, #fbbf24);
        border-radius: 20px;
    }
    .bar-count {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-sub);
        width: 30px;
        text-align: right;
    }

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    .review-item {
        display: flex;
        gap: 20px;
        padding: 24px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .review-item:last-child { border-bottom: none; padding-bottom: 0; }
    .review-item:first-child { padding-top: 0; }

    .user-avatar {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--primary-soft) 0%, rgba(231, 76, 60, 0.15) 100%);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 14px;
        flex-shrink: 0;
        border: 1.5px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .review-content { flex: 1; }
    .review-user-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6px;
    }
    .review-user-info { display: flex; flex-direction: column; gap: 2px; }
    .review-user-name { font-weight: 800; color: var(--text-main); font-size: 14px; display: flex; align-items: center; gap: 8px; }
    .verified-badge {
        font-size: 10px;
        font-weight: 700;
        color: #10b981;
        background: #ecfdf5;
        padding: 1px 8px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .review-date { font-size: 12px; color: var(--text-sub); font-weight: 500; }
    .review-stars { color: #f59e0b; font-size: 10px; margin-bottom: 8px; display: flex; gap: 2px; }
    .review-text {
        font-size: 13px;
        color: #475569;
        line-height: 1.6;
        font-weight: 400;
    }

    /* Star Rating Form */
    .star-rating-form {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 6px;
        margin-bottom: 12px;
    }
    .star-rating-form input { display: none; }
    .star-rating-form label {
        font-size: 24px;
        color: #cbd5e1;
        cursor: pointer;
        transition: color 0.2s;
    }
    .star-rating-form input:checked ~ label,
    .star-rating-form label:hover,
    .star-rating-form label:hover ~ label {
        color: #f59e0b;
    }

    .review-form-container {
        background: var(--bg-light);
        padding: 24px;
        border-radius: var(--radius-md);
        margin-bottom: 32px;
        border: 1px dashed var(--border-color);
    }
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-weight: 700; margin-bottom: 6px; font-size: 13px; color: var(--text-main); }
    .form-textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        font-size: 13px;
        min-height: 100px;
        resize: vertical;
        outline: none;
        transition: border-color 0.2s;
    }
    .form-textarea:focus { border-color: var(--primary); }
    .btn-submit-review {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-submit-review:hover { background: #c0392b; transform: translateY(-1px); }

    /* Related Products Section */
    .related-container {
        margin-top: 60px;
        padding-top: 40px;
        border-top: 2px solid var(--bg-light);
    }
    .related-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-transform: uppercase;
    }
    .related-title::before {
        content: '';
        width: 5px;
        height: 24px;
        background: var(--primary);
        border-radius: 10px;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    @media (max-width: 1024px) {
        .product-main-card { grid-template-columns: 1fr; padding: 30px; gap: 40px; }
        .gallery-container { flex-direction: column-reverse; }
        .thumbnail-list { flex-direction: row; }
        .thumb-item { width: 70px; height: 70px; }
        .main-preview { min-height: 400px; }
        .products-grid { grid-template-columns: repeat(2, 1fr); }
        .reviews-header { grid-template-columns: 1fr; gap: 30px; }
    }

    @media (max-width: 640px) {
        .meta-row { gap: 8px; }
        .product-title { font-size: 24px; }
        .trust-badges { grid-template-columns: 1fr; }
        .action-section { flex-direction: column; align-items: stretch; }
        .qty-control { justify-content: center; }
    }

</style>
@endpush

@section('content')
<div class="product-detail-page">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav class="breadcrumb-custom">
            <a href="/">Beranda</a>
            @if(isset($product->breadcrumbs) && is_array($product->breadcrumbs))
                @foreach($product->breadcrumbs as $crumb)
                    <i class="fas fa-chevron-right"></i>
                    <a href="#">{{ $crumb }}</a>
                @endforeach
            @endif
            <i class="fas fa-chevron-right"></i>
            <span class="active">{{ $product->name }}</span>
        </nav>

        <div class="product-main-card">
            <!-- Left: Gallery -->
            <div class="gallery-container">
                <div class="thumbnail-list">
                    <div class="thumb-item active" onclick="changeImage(this, '{{ $product->main_image }}')">
                        <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                    </div>
                    @if(isset($product->gallery) && is_array($product->gallery))
                        @foreach($product->gallery as $img)
                            <div class="thumb-item" onclick="changeImage(this, '{{ $img }}')">
                                <img src="{{ $img }}" alt="Gallery Item">
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="main-preview">
                    <img id="main-product-img" src="{{ $product->main_image }}" alt="{{ $product->name }}">
                </div>
            </div>

            <!-- Right: Info -->
            <div class="info-panel">
                <div class="product-header">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <div class="meta-row">
                        <div style="display: flex; align-items: center; gap: 4px; background: #fff8e6; padding: 2px 8px; border-radius: 12px; border: 1px solid #fee2e2;">
                            <i class="fas fa-star" style="color: #f59e0b; font-size: 12px;"></i>
                            <span style="font-weight: 700; font-size: 13px; color: #b45309;">{{ $avgRating }}</span>
                        </div>
                        <span class="review-count" style="font-size: 13px;">({{ $totalReviews }} ulasan)</span>
                        <div class="stock-pill" style="font-size: 11px;">Tersedia</div>
                    </div>
                </div>

                <div class="price-section">
                    @if($product->old_price)
                        <div class="old-price-row">
                            <span class="old-price">Rp {{ number_format((float) $product->old_price, 0, ',', '.') }}</span>
                            @php
                                $discount = 0;
                                if ($product->old_price > 0) {
                                    $discount = round((($product->old_price - $product->price) / $product->old_price) * 100);
                                }
                            @endphp
                            @if($discount > 0)
                                <span class="discount-tag">-{{ $discount }}%</span>
                            @endif
                        </div>
                    @endif
                    <span class="current-price">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</span>
                </div>

                <div class="description-box">
                    {{ $product->description }}
                </div>

                @if(isset($product->colors) && is_array($product->colors) && count($product->colors) > 0)
                <div class="variant-section">
                    <span class="variant-label">Pilih Warna :</span>
                    <div class="color-options">
                        @foreach($product->colors as $index => $color)
                            <div class="color-item {{ $index == 0 ? 'active' : '' }}" 
                                 style="background: {{ $color }};"
                                 onclick="selectVariant(this)"></div>
                        @endforeach
                    </div>
                </div>
                @endif

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="action-section">
                        <div class="qty-control">
                            <button type="button" class="qty-btn" onclick="updateQty(-1)"><i class="fas fa-minus"></i></button>
                            <input type="number" name="quantity" id="qty-display" value="1" min="1" class="qty-input">
                            <button type="button" class="qty-btn" onclick="updateQty(1)"><i class="fas fa-plus"></i></button>
                        </div>
                        <button type="submit" class="btn-checkout">Tambah ke Keranjang</button>
                        @php
                            $wishlist = session()->get('wishlist', []);
                            $isInWishlist = isset($wishlist[$product->id]);
                        @endphp
                        <button type="button" 
                                class="btn-action-outline wishlist-btn {{ $isInWishlist ? 'wishlisted' : '' }}" 
                                onclick="toggleWishlist(this, {{ $product->id }})">
                            <i class="{{ $isInWishlist ? 'fas' : 'far' }} fa-heart"></i>
                        </button>
                    </div>
                </form>

                <div class="trust-badges">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fas fa-truck-fast"></i></div>
                        <div class="trust-text">
                            <span class="trust-title">Pengiriman Cepat</span>
                            <span class="trust-desc">Gratis ongkir min. Rp 300rb</span>
                        </div>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fas fa-rotate-left"></i></div>
                        <div class="trust-text">
                            <span class="trust-title">Retur Mudah</span>
                            <span class="trust-desc">Kebijakan retur dalam 30 hari</span>
                        </div>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fas fa-shield-halved"></i></div>
                        <div class="trust-text">
                            <span class="trust-title">Pembayaran Aman</span>
                            <span class="trust-desc">Enkripsi 100% aman</span>
                        </div>
                    </div>
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fas fa-headset"></i></div>
                        <div class="trust-text">
                            <span class="trust-title">Layanan 24/7</span>
                            <span class="trust-desc">Dukungan teknis siap membantu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="product-reviews-section">
            <h2 class="related-title">Ulasan Pelanggan</h2>
            <div class="reviews-card">
                <div class="reviews-header">
                    <div class="rating-summary">
                        <div class="summary-score">{{ $avgRating }}</div>
                        <div class="summary-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <div class="summary-total">Berdasarkan {{ $totalReviews }} Ulasan</div>
                    </div>
                    <div class="rating-bars">
                        @foreach(range(5, 1) as $star)
                        <div class="bar-item">
                            <span class="bar-label">{{ $star }} Bintang</span>
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" style="width: {{ $starStats[$star]['percent'] }}%;"></div>
                            </div>
                            <span class="bar-count">{{ $starStats[$star]['count'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                @auth
                <div class="review-form-container">
                    <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 24px; color: var(--text-main);">Tulis Ulasan Anda</h3>
                    <form action="{{ route('review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="form-group">
                            <label class="form-label">Rating Produk</label>
                            <div class="star-rating-form">
                                <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ulasan Anda</label>
                            <textarea name="comment" class="form-textarea" placeholder="Bagikan pengalaman Anda menggunakan produk ini..." required></textarea>
                        </div>

                        <button type="submit" class="btn-submit-review">Kirim Ulasan</button>
                    </form>
                </div>
                @else
                <div class="review-form-container" style="text-align: center;">
                    <p style="color: var(--text-sub);">Anda harus <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700;">Login</a> untuk memberikan ulasan.</p>
                </div>
                @endauth

                <div class="reviews-list">
                    @forelse($product->allReviews->sortByDesc('created_at') as $review)
                    <div class="review-item">
                        <div class="user-avatar">
                            {{ strtoupper(substr($review->user->name, 0, 2)) }}
                        </div>
                        <div class="review-content">
                            <div class="review-user-row">
                                <div class="review-user-info">
                                    <span class="review-user-name">
                                        {{ $review->user->name }}
                                        <span class="verified-badge"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                        @if($review->is_liked)
                                            <span class="verified-badge" style="background: #fef2f2; color: #ef4444; border-color: #fee2e2;">
                                                <i class="fas fa-heart"></i> Disukai Admin
                                            </span>
                                        @endif
                                    </span>
                                    <div class="review-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="review-text">{{ $review->comment }}</p>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 40px; color: var(--text-sub);">
                        <i class="far fa-comment-dots" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                        <p>Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <div class="related-container">
            <h2 class="related-title">Produk Terkait</h2>
            <div class="products-grid">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function changeImage(el, src) {
        document.getElementById('main-product-img').src = src;
        document.querySelectorAll('.thumb-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
    }

    function selectVariant(el) {
        document.querySelectorAll('.color-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
    }

    function updateQty(delta) {
        const input = document.getElementById('qty-display');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        input.value = val;
    }
</script>
@endpush
@endsection
