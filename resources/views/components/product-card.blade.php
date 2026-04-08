<div class="product-card {{ $attributes->get('class') }}" id="{{ $attributes->get('id') }}">
    <a href="{{ route('product.show', $product['id']) }}" class="product-card-link">
        <div class="product-image-wrap">
            @if(isset($product['badge']) && $product['badge'])
                <span class="badge-tag {{ str_contains($product['badge'], '-') ? 'badge-red' : 'badge-green' }}">
                    {{ $product['badge'] }}
                </span>
            @endif
            
            <div class="product-image-container">
                <img src="{{ $product['image'] ?? $product['main_image'] ?? 'https://via.placeholder.com/300' }}" 
                     alt="{{ $product['name'] }}" 
                     class="product-image">
            </div>
            
            <div class="action-icons">
                @php
                    $wishlist = session()->get('wishlist', []);
                    $isInWishlist = isset($wishlist[$product['id']]);
                @endphp
                <button class="icon-btn wishlist-btn {{ $isInWishlist ? 'wishlisted' : '' }}" 
                        data-product-id="{{ $product['id'] }}"
                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(this, {{ $product['id'] }});">
                    <i class="{{ $isInWishlist ? 'fas' : 'far' }} fa-heart"></i>
                </button>
                <div class="icon-btn preview-btn" onclick="event.preventDefault(); event.stopPropagation();"><i class="far fa-eye"></i></div>
            </div>
            
            <div class="add-to-cart-bar">
                <i class="fas fa-shopping-cart"></i> Tambah Ke Keranjang
            </div>
        </div>
        <div class="product-info">
            <h3 title="{{ $product['name'] }}">{{ $product['name'] }}</h3>
            <div class="price-group">
                <span class="current-price">Rp {{ number_format((float) $product['price'], 0, ',', '.') }}</span>
                @if(isset($product['old_price']) && $product['old_price'] && $product['old_price'] > $product['price'])
                    <span class="old-price">Rp {{ number_format((float) $product['old_price'], 0, ',', '.') }}</span>
                @endif
            </div>
            <div class="rating-group">
                <div class="stars">
                    @php
                        $rating = $product['rating'] ?? 0;
                    @endphp
                    @for($i = 0; $i < 5; $i++)
                        <i class="{{ $i < $rating ? 'fas' : 'far' }} fa-star"></i>
                    @endfor
                </div>
                <span class="reviews">({{ $product['reviews'] ?? 0 }})</span>
            </div>
        </div>
    </a>
</div>

<style>
    .product-card {
        background: #fff;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--border-light);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        padding: 0;
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: var(--border);
    }
    
    .product-image-wrap {
        position: relative;
        padding: 12px;
        background: #f8fafc;
        overflow: hidden;
    }
    
    .product-image-container {
        aspect-ratio: 1/1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: var(--radius-md);
        overflow: hidden;
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 15px;
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    
    .product-card:hover .product-image {
        transform: scale(1.1);
    }
    
    .product-info {
        padding: 20px;
    }
    
    .product-info h3 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--text-main);
    }
    
    .current-price {
        font-size: 18px;
        color: var(--primary);
    }
    
    .badge-tag {
        border-radius: 6px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }
    
    .action-icons {
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.3s ease;
    }
    
    .product-card:hover .action-icons {
        opacity: 1;
        transform: translateX(0);
    }
    
    .add-to-cart-bar {
        background: var(--text-main);
        font-weight: 700;
        padding: 14px;
        letter-spacing: 0.5px;
    }
</style>
