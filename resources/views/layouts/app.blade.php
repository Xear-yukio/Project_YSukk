<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Belanja.ID - Toko online terpercaya dengan diskon terbaik untuk elektronik, fashion, kecantikan, dan lainnya.')">
    <title>@yield('title', 'Belanja.ID - Belanja Online Terpercaya')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        :root {
            --primary: #e74c3c;
            --primary-soft: #fef2f2;
            --secondary: #1e293b;
            --accent: #3b82f6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-color: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
            --border-light: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --radius-xl: 24px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        a { text-decoration: none; color: inherit; transition: all 0.2s; }
        img { max-width: 100%; height: auto; display: block; }
        ul { list-style: none; }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 32px;
        }

        /* ===== HEADER ===== */
        .header {
            background: #fff;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            gap: 24px;
        }
        .logo {
            font-size: 22px;
            font-weight: 800;
            color: #111;
            white-space: nowrap;
            letter-spacing: -0.5px;
        }
        .logo span { color: #e74c3c; }
        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }
        .nav-links a {
            font-size: 13px;
            font-weight: 500;
            color: #555;
            transition: color 0.2s;
            white-space: nowrap;
        }
        .nav-links a:hover { color: #e74c3c; }
        .search-box {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 8px;
            padding: 8px 14px;
            gap: 8px;
            min-width: 240px;
        }
        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 13px;
            width: 100%;
            font-family: 'Inter', sans-serif;
            margin-top: 1px;
        }
        .search-box i { color: #999; font-size: 14px; margin-top: 1px; }
        .header-icons {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .header-icons a {
            color: #555;
            font-size: 18px;
            position: relative;
            transition: color 0.2s;
        }
        .header-icons a:hover { color: #e74c3c; }
        .cart-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background: #e74c3c;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== HERO ===== */
        .hero {
            padding: 24px 0;
        }
        .hero-wrapper {
            display: flex;
            gap: 20px;
            align-items: stretch;
        }
        .hero-sidebar {
            min-width: 220px;
            background: #fff;
            border-radius: 12px;
            padding: 16px 0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .hero-sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            font-size: 13px;
            color: #444;
            transition: all 0.2s;
        }
        .hero-sidebar a:hover {
            background: #fef2f2;
            color: #e74c3c;
        }
        .hero-sidebar a i { width: 18px; text-align: center; font-size: 14px; }
        .hero-banner {
            flex: 1;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #1a0533 0%, #4a1a8a 40%, #7c3aed 70%, #a855f7 100%);
            min-height: 340px;
            display: flex;
            align-items: center;
        }
        .hero-banner-content {
            padding: 40px 48px;
            position: relative;
            z-index: 2;
            max-width: 50%;
        }
        .hero-banner-content .apple-logo {
            font-size: 28px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 8px;
        }
        .hero-banner-content h1 {
            font-size: 42px;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 12px;
        }
        .hero-banner-content p {
            color: rgba(255,255,255,0.7);
            font-size: 14px;
            margin-bottom: 20px;
        }
        .hero-banner-content .btn-shop {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 2px solid #fff;
            padding-bottom: 4px;
            transition: opacity 0.2s;
        }
        .hero-banner-content .btn-shop:hover { opacity: 0.8; }
        .hero-banner-img {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            width: 300px;
            z-index: 1;
        }
        .hero-banner-img img {
            width: 100%;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));
        }

        /* ===== SECTION STYLES ===== */
        .section {
            padding: 32px 0;
        }
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .section-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .section-badge {
            background: #e74c3c;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #111;
        }
        .section-header .view-all {
            font-size: 13px;
            color: #e74c3c;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: gap 0.2s;
        }
        .section-header .view-all:hover { gap: 8px; }

        /* ===== COUNTDOWN ===== */
        .countdown {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .countdown-item {
            background: #111;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 10px;
            border-radius: 6px;
            min-width: 36px;
            text-align: center;
        }
        .countdown-sep {
            color: #e74c3c;
            font-weight: 700;
            font-size: 16px;
        }

        /* ===== PRODUCT CARDS ===== */
        .products-scroll {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 8px;
            scrollbar-width: none;
        }
        .products-scroll::-webkit-scrollbar { display: none; }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }
        .products-grid-8 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        /* ===== PRODUCT CARDS (UNIFIED) ===== */
        .product-card {
            background: #fff;
            position: relative;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .product-card:hover {
            transform: translateY(-4px);
        }
        .product-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .product-image-container {
            position: relative;
            background: #fff;
            border-radius: 8px;
            margin-bottom: 16px;
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
            transition: border-color 0.3s ease;
        }
        .product-card:hover .product-image-container {
            border-color: rgba(231, 76, 60, 0.15);
        }
        .product-image {
            width: 100%;
            height: 100%;
            padding: 10px;
            object-fit: contain;
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .product-card:hover .product-image {
            transform: scale(1.08);
        }
        .badge-tag {
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 4px;
            color: #fff;
            z-index: 3;
            text-transform: uppercase;
        }
        .badge-green { background: #00ff66; }
        .badge-red { background: #db4444; }

        .action-icons {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 10;
        }
        .icon-btn {
            width: 34px;
            height: 34px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            text-decoration: none !important;
            font-size: 14px;
            transition: all 0.2s;
        }
        .icon-btn:hover {
            background: #db4444;
            color: #fff;
        }
        .icon-btn.wishlisted {
            color: #e74c3c;
        }
        .icon-btn.wishlisted i {
            color: #e74c3c;
        }
        
        .add-to-cart-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #000;
            color: #fff;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            opacity: 0;
            transform: translateY(100%);
            transition: all 0.3s;
            z-index: 5;
        }
        .product-image-container:hover .add-to-cart-bar {
            opacity: 1;
            transform: translateY(0);
        }
        .product-card.show-cart .add-to-cart-bar {
            opacity: 1;
            transform: translateY(0);
        }

        .product-info {
            padding: 12px 4px;
        }
        .product-info h3 {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            color: #111;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.4;
        }
        .price-group {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }
        .current-price {
            font-size: 16px;
            font-weight: 700;
            color: #db4444;
            display: flex;
            align-items: baseline;
            gap: 2px;
        }
        .old-price {
            font-size: 11px;
            color: rgba(0,0,0,0.4);
            text-decoration: line-through;
            font-weight: 500;
        }
        .rating-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .stars {
            color: #ffad33;
            font-size: 10px;
            display: flex;
            gap: 1px;
        }
        .reviews {
            font-size: 11px;
            font-weight: 500;
            color: rgba(0,0,0,0.4);
        }

        /* ===== MUSIC BANNER ===== */
        .music-banner {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: #111;
            min-height: 280px;
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .music-banner-bg {
            position: absolute;
            inset: 0;
            z-index: 1;
        }
        .music-banner-bg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.6;
        }
        .music-banner-content {
            position: relative;
            z-index: 2;
            padding: 48px 56px;
        }
        .music-banner-content h2 {
            font-size: 36px;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 20px;
            max-width: 320px;
        }
        .music-banner-content .btn-green {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #22c55e;
            color: #fff;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s, transform 0.2s;
            border: none;
            cursor: pointer;
        }
        .music-banner-content .btn-green:hover {
            background: #16a34a;
            transform: translateY(-1px);
        }

        /* ===== LIHAT PRODUK KAMI (PRODUCT CATALOG) ===== */
        .btn-view-all {
            display: block;
            margin: 28px auto 0;
            padding: 12px 48px;
            border: 2px solid #e74c3c;
            color: #e74c3c;
            background: transparent;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s;
            font-family: 'Inter', sans-serif;
        }
        .btn-view-all:hover {
            background: #e74c3c;
            color: #fff;
        }

        /* ===== BARANG BARU (NEW ARRIVALS) ===== */
        .new-arrivals-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto auto;
            gap: 16px;
        }
        .new-card {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: transform 0.25s;
        }
        .new-card:hover { transform: translateY(-4px); }
        .new-card-big {
            grid-row: span 2;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
        }
        .new-card-big img {
            max-height: 350px;
            object-fit: contain;
            filter: drop-shadow(0 10px 30px rgba(0,0,0,0.4));
        }
        .new-card-sm {
            background: #f0f0f0;
            display: flex;
            align-items: center;
            padding: 24px 32px;
            gap: 20px;
            min-height: 190px;
        }
        .new-card-sm img {
            max-height: 130px;
            object-fit: contain;
        }
        .new-card-text {
            position: absolute;
            bottom: 24px;
            left: 24px;
            z-index: 2;
        }
        .new-card-text h3 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }
        .new-card-text p {
            font-size: 13px;
            color: rgba(255,255,255,0.7);
        }
        .new-card-sm .new-card-text {
            position: relative;
            bottom: auto;
            left: auto;
        }
        .new-card-sm .new-card-text h3 { color: #111; }
        .new-card-sm .new-card-text p { color: #666; }
        .new-card-sm .new-card-text .shop-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #111;
            font-size: 13px;
            font-weight: 600;
            margin-top: 8px;
            border-bottom: 1.5px solid #111;
            padding-bottom: 2px;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #111;
            color: #ccc;
            padding: 48px 0 24px;
            margin-top: 40px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr 1fr;
            gap: 32px;
            margin-bottom: 40px;
        }
        .footer h5 {
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .footer p, .footer a {
            font-size: 13px;
            color: #888;
            line-height: 2;
            transition: color 0.2s;
        }
        .footer a:hover { color: #fff; }
        .footer-brand p {
            margin-bottom: 16px;
            line-height: 1.6;
        }
        .footer-social {
            display: flex;
            gap: 14px;
            margin-top: 12px;
        }
        .footer-social a {
            width: 36px;
            height: 36px;
            background: #222;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #888;
            transition: all 0.2s;
        }
        .footer-social a:hover {
            background: #e74c3c;
            color: #fff;
        }
        .footer-bottom {
            border-top: 1px solid #222;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #555;
        }
        .footer-links ul { display: flex; flex-direction: column; }
        .footer-links li { line-height: 2; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero-sidebar { display: none; }
            .products-grid { grid-template-columns: repeat(4, 1fr); }
            .footer-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .search-box { min-width: 160px; }
            .hero-banner-content { max-width: 60%; }
            .hero-banner-content h1 { font-size: 28px; }
            .hero-banner-img { width: 200px; right: 10px; }
            .products-grid { grid-template-columns: repeat(3, 1fr); }
            .products-grid-8 { grid-template-columns: repeat(2, 1fr); }
            .new-arrivals-grid { grid-template-columns: 1fr; }
            .new-card-big { grid-row: span 1; min-height: 280px; }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
            .music-banner-content h2 { font-size: 26px; }
        }
        @media (max-width: 480px) {
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-banner-content h1 { font-size: 22px; }
            .hero-banner-img { width: 140px; }
            .header-top { gap: 12px; }
            .footer-grid { grid-template-columns: 1fr; }
        }

        .animate-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes revealCard {
            from { opacity: 0; transform: translateY(30px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .reveal-card {
            opacity: 0;
            animation: revealCard 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* ===== TOAST NOTIFICATION ===== */
        .toast-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        .toast {
            background: #1e293b;
            color: #fff;
            padding: 12px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 10px;
            transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-weight: 600;
            font-size: 14px;
            border-left: 4px solid var(--primary);
        }
        .toast.show {
            transform: translateX(0);
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    @include('components.footer')

    <div class="toast-container" id="toast-container"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}" style="color: ${type === 'success' ? '#10b981' : '#ef4444'}"></i>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => toast.classList.add('show'), 10);
            
            // Remove toast
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        async function toggleWishlist(button, productId) {
            try {
                const response = await fetch('{{ route("wishlist.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ product_id: productId })
                });

                if (response.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    return;
                }

                const data = await response.json();
                
                if (data.status === 'added') {
                    button.classList.add('wishlisted');
                    button.querySelector('i').classList.remove('far');
                    button.querySelector('i').classList.add('fas');
                    showToast(data.message);
                } else {
                    button.classList.remove('wishlisted');
                    button.querySelector('i').classList.remove('fas');
                    button.querySelector('i').classList.add('far');
                    showToast(data.message);
                }

                // Update navbar wishlist badge
                updateWishlistBadge(data.count);

            } catch (error) {
                console.error('Error toggling wishlist:', error);
                showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        }

        function updateWishlistBadge(count) {
            const wishlistIcon = document.getElementById('wishlist-icon');
            if (!wishlistIcon) return;

            let badge = wishlistIcon.querySelector('.wishlist-badge');
            
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'wishlist-badge';
                    wishlistIcon.appendChild(badge);
                }
                badge.textContent = count;
            } else if (badge) {
                badge.remove();
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
