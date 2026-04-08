<!-- Navbar Component -->
<header class="header sticky-nav" id="header">
    <div class="container">
        <div class="header-top">
            <a href="/" class="logo">Belanja<span>.ID</span></a>
            <nav class="nav-links">
                <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('products.new') }}" class="{{ request()->is('products/new') ? 'active' : '' }}">Produk Terbaru</a>
                <a href="{{ route('products.promo') }}" class="{{ request()->is('products/promo') ? 'active' : '' }}">Promo</a>
                <a href="{{ route('contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Kontak</a>
            </nav>
            <form action="{{ route('products.search') }}" method="GET" class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="q" placeholder="Apa Yang Kamu Cari?" value="{{ request('q') }}">
            </form>
            <div class="header-icons">
                <a href="{{ route('wishlist') }}" id="wishlist-icon">
                    <i class="far fa-heart"></i>
                    @php
                        $wishlistItems = session()->get('wishlist', []);
                        $wishlistCount = count((array)$wishlistItems);
                    @endphp
                    @if($wishlistCount > 0)
                        <span class="wishlist-badge">{{ $wishlistCount }}</span>
                    @endif
                </a>
                <a href="{{ route('cart.index') }}" id="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    @php
                        $cartCount = count(session()->get('cart', []));
                    @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                <a href="{{ route('messages.index') }}" id="inbox-icon" title="Kotak Masuk">
                    <i class="far fa-comment-alt"></i>
                    @php
                        $unreadCount = \App\Models\Message::whereHas('conversation', function($q) { $q->where('user_id', Auth::id()); })
                            ->where('sender_id', '!=', Auth::id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="inbox-badge">{{ $unreadCount }}</span>
                    @endif
                </a>
                @endauth
                @auth
                    <div class="user-dropdown" style="position:relative;">
                        <a href="#" id="user-icon">
                            <div id="nav-avatar" style="width: 24px; height: 24px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border: 1px solid #e2e8f0;">
                                @if(Auth::user()->avatar_url)
                                    <img src="{{ Auth::user()->avatar_url }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="far fa-user"></i>
                                @endif
                            </div>
                        </a>
                        <div id="user-menu" class="dropdown-menu-custom">
                            <div class="dropdown-user-info">
                                <strong id="nav-user-name">{{ Auth::user()->name }}</strong>
                                <small>{{ ucfirst(Auth::user()->role) }}</small>
                            </div>
                            <hr style="border:none;border-top:1px solid #eee;margin:8px 0;">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</a>
                            @elseif(Auth::user()->isPetugas())
                                <a href="{{ route('petugas.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard Petugas</a>
                            @endif
                            <a href="{{ route('orders.index') }}"><i class="fas fa-box"></i> Pesanan Saya</a>
                            <a href="{{ route('profile.index') }}"><i class="fas fa-cog"></i> Pengaturan</a>
                            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                @csrf
                                <button type="submit" class="dropdown-logout-btn">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" id="login-link" style="font-size:13px;font-weight:600;white-space:nowrap;">
                        <i class="far fa-user"></i>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>
<style>
    .sticky-nav {
        position: sticky;
        top: 0;
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(12px) saturate(180%);
        -webkit-backdrop-filter: blur(12px) saturate(180%);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8) !important;
        z-index: 1000;
        transition: all 0.3s ease;
    }
    
    .nav-links a {
        position: relative;
        padding-bottom: 4px;
    }
    
    .nav-links a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: var(--primary);
        transition: all 0.3s ease;
        transform: translateX(-50%);
        border-radius: 2px;
    }
    
    .nav-links a:hover::after,
    .nav-links a.active::after {
        width: 100%;
    }
    
    .logo {
        font-size: 24px;
        font-weight: 900;
        letter-spacing: -1px;
    }
    
    .search-box {
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
        background: #f8fafc;
    }
    
    .search-box:focus-within {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
    }
    .wishlist-badge {
        position: absolute;
        top: -6px;
        right: -8px;
        background: #db4444;
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
    #wishlist-icon { position: relative; display: inline-flex; }
    #cart-icon { position: relative; display: inline-flex; }
    #inbox-icon { position: relative; display: inline-flex; }
    #user-icon { position: relative; display: inline-flex; }
    
    .inbox-badge {
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
        border: 2px solid #fff;
    }
    
    .dropdown-menu-custom {
        display: block; /* Always block, use opacity/visibility for transition */
        visibility: hidden;
        pointer-events: none;
        position: absolute;
        top: calc(100% + 15px);
        right: -10px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-width: 240px;
        padding: 10px;
        z-index: 200;
        opacity: 0;
        transform: translateY(15px) scale(0.95);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .dropdown-menu-custom::before {
        content: '';
        position: absolute;
        top: -6px;
        right: 14px;
        width: 12px;
        height: 12px;
        background: #fff;
        transform: rotate(45deg);
        border-top: 1px solid #f1f5f9;
        border-left: 1px solid #f1f5f9;
    }
    /* Hover effect removed to favor click as per user request, but can be added if needed */
    .dropdown-menu-custom.show-menu { 
        visibility: visible;
        pointer-events: auto;
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    
    .dropdown-user-info {
        padding: 12px 16px;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 8px;
    }
    .dropdown-user-info strong { font-size: 15px; font-weight: 800; color: #0f172a; line-height: 1.2; }
    .dropdown-user-info small { font-size: 12px; color: #64748b; font-weight: 500; margin-top: 4px; }
    
    .dropdown-menu-custom a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .dropdown-menu-custom a:hover { 
        background: rgba(231, 76, 60, 0.08); 
        color: var(--primary);
        transform: translateX(4px);
    }
    .dropdown-menu-custom a i { width: 16px; text-align: center; font-size: 15px; color: #94a3b8; transition: color 0.2s;}
    .dropdown-menu-custom a:hover i { color: #e74c3c; }

    .dropdown-logout-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 700;
        color: #e74c3c;
        background: none;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        text-align: left;
        transition: all 0.2s;
        margin-top: 4px;
    }
    .dropdown-logout-btn:hover { 
        background: #fef2f2; 
        color: #dc2626;
        transform: translateX(4px);
    }
    .dropdown-logout-btn i { width: 16px; text-align: center; font-size: 15px; }
</style>

<script>
    // Toggle menu
    document.getElementById('user-icon').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('user-menu').classList.toggle('show-menu');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('user-menu');
        const icon = document.getElementById('user-icon');
        if (menu && icon && !icon.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show-menu');
        }
    });
</script>
