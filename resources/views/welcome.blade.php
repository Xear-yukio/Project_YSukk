@extends('layouts.app')

@section('title', 'Belanja.ID - Belanja Online Terpercaya')
@section('meta_description', 'Belanja.ID - Toko online terpercaya dengan diskon terbaik untuk elektronik, fashion, kecantikan, dan lainnya.')

@push('styles')
<style>
    /* Premium UI Overrides for Homepage */
    :root {
        --primary: #e74c3c;
        --primary-light: #ff7675;
        --secondary: #3b82f6;
        --bg-color: #f8fafc;
        --surface: #ffffff;
        --text-main: #0f172a;
        --text-sub: #64748b;
        --radius-xl: 24px;
        --radius-lg: 16px;
        --radius-md: 12px;
        --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
    }

    body { background-color: var(--bg-color); }

    /* Hero Redesign */
    .hero { padding: 40px 0; }
    .hero-sidebar {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 20px 12px;
        box-shadow: var(--shadow-sm);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .hero-sidebar a {
        padding: 12px 16px;
        border-radius: 10px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .hero-sidebar a:hover {
        background: rgba(231, 76, 60, 0.08);
        transform: translateX(4px);
    }
    .hero-banner {
        border-radius: var(--radius-xl);
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        position: relative;
    }
    .hero-banner::before {
        content: ''; position: absolute; right: -10%; top: -30%; width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(231,76,60,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .hero-banner-content { padding: 60px; max-width: 55%; }
    .hero-banner-content h1 {
        font-size: 48px; font-weight: 900; letter-spacing: -1px; margin-bottom: 16px;
        background: linear-gradient(to right, #fff, #cbd5e1);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .hero-banner-content p { font-size: 16px; color: #94a3b8; font-weight: 400; line-height: 1.6; }
    .hero-banner-content .btn-shop {
        margin-top: 10px; background: var(--primary); padding: 14px 28px;
        border-radius: 12px; border: none; font-size: 15px; font-weight: 700;
        box-shadow: 0 8px 20px rgba(231, 76, 60, 0.25); transition: all 0.3s;
    }
    .hero-banner-content .btn-shop:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(231, 76, 60, 0.35); opacity: 1; }
    .hero-banner-img img { filter: drop-shadow(0 30px 40px rgba(0,0,0,0.4)); transform: scale(1.1); transition: transform 0.5s; }
    .hero-banner:hover .hero-banner-img img { transform: scale(1.15) rotate(-2deg); }

    /* Modern Sections */
    .section { padding: 60px 0; border-bottom: 1px solid var(--border-light); }
    .section:last-of-type { border-bottom: none; }
    .section-header { 
        margin-bottom: 40px; 
        display: flex; 
        align-items: flex-end; 
        justify-content: space-between;
    }
    .section-title { 
        font-size: 32px; 
        font-weight: 800; 
        letter-spacing: -1px; 
        color: var(--text-main);
        line-height: 1;
    }
    .section-badge { 
        display: inline-block;
        background: var(--primary-soft); 
        color: var(--primary); 
        font-size: 12px; 
        font-weight: 800; 
        padding: 6px 16px; 
        border-radius: 30px; 
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .view-all {
        font-weight: 700;
        font-size: 14px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .view-all:hover { gap: 12px; opacity: 0.8; }
    
    .countdown-item { 
        background: var(--text-main); 
        color: #fff;
        border-radius: 12px; 
        font-size: 16px; 
        padding: 10px 14px; 
        font-weight: 800; 
        min-width: 45px;
        text-align: center;
        box-shadow: var(--shadow-md);
    }
    
    /* Hero Sidebar Enhancements */
    .hero-sidebar {
        border-radius: var(--radius-xl);
        padding: 24px 16px;
        box-shadow: var(--shadow-md);
        background: #fff;
    }
    .hero-sidebar a {
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hero-sidebar a i {
        margin-right: 12px;
        color: var(--text-muted);
        transition: color 0.2s;
    }
    .hero-sidebar a:hover i { color: var(--primary); }

    .btn-view-all { 
        border-radius: 14px; 
        padding: 16px 50px; 
        font-size: 15px; 
        font-weight: 700;
        border: 2px solid var(--primary);
        background: transparent;
        color: var(--primary);
        transition: all 0.3s;
    }
    .btn-view-all:hover {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 10px 20px rgba(231, 76, 60, 0.2);
        transform: translateY(-2px);
    }

</style>
@endpush

@section('content')

<!-- ==================== HERO ==================== -->
<section class="hero" id="hero">
    <div class="container">
        <div class="hero-wrapper">
            <aside class="hero-sidebar">
                <a href="{{ route('category.show', 'phones') }}"><i class="fas fa-mobile-alt"></i> Handphone & Aksesoris</a>
                <a href="{{ route('category.show', 'computers') }}"><i class="fas fa-laptop"></i> Komputer & Laptop</a>
                <a href="{{ route('category.show', 'camera') }}"><i class="fas fa-camera"></i> Kamera</a>
                <a href="{{ route('category.show', 'fashion') }}"><i class="fas fa-tshirt"></i> Fashion Pria</a>
                <a href="{{ route('category.show', 'beauty') }}"><i class="fas fa-gem"></i> Kecantikan</a>
                <a href="{{ route('category.show', 'furniture') }}"><i class="fas fa-couch"></i> Perabotan</a>
                <a href="{{ route('category.show', 'sports') }}"><i class="fas fa-futbol"></i> Olahraga</a>
                <a href="{{ route('category.show', 'toys') }}"><i class="fas fa-baby"></i> Bayi & Anak</a>
                <a href="{{ route('category.show', 'health') }}"><i class="fas fa-heartbeat"></i> Kesehatan</a>
            </aside>
            <div class="hero-banner">
                <div class="hero-banner-content">
                    <div class="apple-logo"><i class="fab fa-apple"></i></div>
                    <h1>Diskon Spesial 30%</h1>
                    <p>Temukan koleksi gadget dan elektronik terbaru dengan penawaran harga terbaik. Promo super terbatas, jangan sampai kehabisan!</p>
                    <a href="{{ route('products.promo') }}" class="btn-shop">Belanja Sekarang <i class="fas fa-arrow-right" style="margin-left: 6px;"></i></a>
                </div>
                <div class="hero-banner-img">
                    <img src="{{ asset('images/hero-banner.png') }}" alt="Promo Terbaru">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FLASH SALES ==================== -->
<section class="section" id="flash-sales">
    <div class="container">
        <div class="section-header">
            <div class="section-header-left">
                <div>
                    <span class="section-badge"><i class="fas fa-bolt"></i> Terbatas</span>
                    <h2 class="section-title">Flash Sales</h2>
                </div>
                <div class="countdown" id="countdown">
                    <div class="countdown-item" id="cd-days">03</div>
                    <span class="countdown-sep">:</span>
                    <div class="countdown-item" id="cd-hours">23</div>
                    <span class="countdown-sep">:</span>
                    <div class="countdown-item" id="cd-mins">19</div>
                    <span class="countdown-sep">:</span>
                    <div class="countdown-item" id="cd-secs">56</div>
                </div>
            </div>
            <a href="{{ route('products.promo') }}" class="view-all">Lihat Semua Promo <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="products-scroll pb-2">
            @foreach($flashSales as $product)
                <x-product-card :product="$product" class="flash-sale-card" />
            @endforeach
        </div>
    </div>
</section>

<!-- ==================== PENJUALAN TERLARIS ==================== -->
<section class="section" id="best-sellers">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-badge"><i class="fas fa-fire"></i> Populer</span>
                <h2 class="section-title">Penjualan Terlaris</h2>
            </div>
            <a href="#" class="view-all">Eksplor Sekarang <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="products-grid">
            @foreach($bestSellers as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- ==================== MUSIC PROMO BANNER ==================== -->
<section class="section" id="music-promo">
    <div class="container">
        <div class="music-banner">
            <div class="music-banner-bg">
                <img src="{{ asset('images/music-banner.png') }}" alt="Music Banner">
            </div>
            <div class="music-banner-content">
                <h2>Tingkatkan <br>Pengalaman Audio Anda</h2>
                <a href="{{ route('category.show', 'audio') }}" class="btn-shop" style="background:#22c55e; color:#fff; border: none; box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);">
                    Koleksi Audio <i class="fas fa-headphones" style="margin-left: 6px;"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== LIHAT PRODUK KAMI ==================== -->
<section class="section" id="our-products">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-badge"><i class="fas fa-box-open"></i> Katalog</span>
                <h2 class="section-title">Jelajahi Produk Kami</h2>
            </div>
        </div>
        <div class="products-grid-8">
            @foreach($ourProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <button class="btn-view-all" id="btn-view-all">Muat Lebih Banyak Produk</button>
        </div>
    </div>
</section>

<!-- ==================== BARANG BARU ==================== -->
<section class="section" id="new-arrivals">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-badge"><i class="fas fa-star"></i> Eksklusif</span>
                <h2 class="section-title">Koleksi Terbaru</h2>
            </div>
        </div>
        <div class="new-arrivals-grid">
            @if(isset($newArrivals[0]))
                <div class="new-card new-card-big" onclick="window.location='{{ route('product.show', $newArrivals[0]->id) }}'">
                    <img src="{{ $newArrivals[0]->main_image }}" alt="{{ $newArrivals[0]->name }}">
                    <div class="new-card-text">
                        <h3>{{ $newArrivals[0]->name }}</h3>
                        <p>{{ $newArrivals[0]->description }}</p>
                    </div>
                </div>
            @endif

            @foreach($newArrivals->skip(1)->take(2) as $product)
                <div class="new-card new-card-sm" onclick="window.location='{{ route('product.show', $product->id) }}'">
                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}">
                    <div class="new-card-text">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ Str::limit($product->description, 50) }}</p>
                        <div class="shop-link">Lebih Detail <i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // === Flash Sale Countdown Timer ===
    (function() {
        const targetDate = new Date();
        targetDate.setDate(targetDate.getDate() + 3);
        targetDate.setHours(23, 59, 59, 0);

        function updateCountdown() {
            const now = new Date();
            const diff = targetDate - now;

            if (diff <= 0) {
                ['cd-days', 'cd-hours', 'cd-mins', 'cd-secs'].forEach(id => document.getElementById(id).textContent = '00');
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById('cd-days').textContent = String(days).padStart(2, '0');
            document.getElementById('cd-hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('cd-mins').textContent = String(minutes).padStart(2, '0');
            document.getElementById('cd-secs').textContent = String(seconds).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    })();

    // === Scroll Animations (Intersection Observer) ===
    (function() {
        const sections = document.querySelectorAll('.section, .hero');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        sections.forEach(section => observer.observe(section));
    })();

    // === Load More Products AJAX ===
    (function() {
        let offset = 8;
        const btnLoadMore = document.getElementById('btn-view-all');
        const productsGrid = document.querySelector('.products-grid-8');

        if (btnLoadMore && productsGrid) {
            btnLoadMore.addEventListener('click', function() {
                const originalText = btnLoadMore.textContent;
                
                // Show loading state
                btnLoadMore.disabled = true;
                btnLoadMore.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i> Memuat Produk...';
                btnLoadMore.style.opacity = '0.7';

                fetch(`/api/products?offset=${offset}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) {
                            // Use a temporary container to parse HTML of new cards
                            const temp = document.createElement('div');
                            temp.innerHTML = data.html;
                            const newCards = Array.from(temp.querySelectorAll('.product-card'));
                            
                            // Staggered reveal animation
                            newCards.forEach((card, index) => {
                                card.classList.add('reveal-card');
                                card.style.animationDelay = (index * 100) + 'ms'; // 100ms delay between cards
                                productsGrid.appendChild(card);
                            });

                            offset += data.count;
                            
                            btnLoadMore.disabled = false;
                            btnLoadMore.textContent = originalText;
                            btnLoadMore.style.opacity = '1';

                            if (!data.hasMore || data.count === 0) {
                                btnLoadMore.parentElement.innerHTML = '<p style="color: #64748b; font-weight: 600; font-size: 14px; margin-top: 20px;">Semua produk telah ditampilkan</p>';
                            }
                        } else {
                            btnLoadMore.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading products:', error);
                        btnLoadMore.disabled = false;
                        btnLoadMore.textContent = 'Gagal memuat. Coba lagi?';
                        btnLoadMore.style.opacity = '1';
                    });
            });
        }
    })();
</script>
@endpush
