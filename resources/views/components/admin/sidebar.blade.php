<!-- Sidebar Component -->
<aside class="admin-sidebar" id="admin-sidebar">
    <div class="sidebar-header">
        <a href="/" class="logo">
            <div class="logo-icon"><i class="fas fa-shopping-bag"></i></div>
            <div class="logo-text">Belanja<span>.ID</span></div>
        </a>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-group">
            <span class="nav-label">Utama</span>
            <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('petugas.dashboard') }}" class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-th-large"></i></div>
                <span>Dashboard</span>
            </a>
        </div>

        @if(Auth::user()->isAdmin())
        <div class="nav-group">
            <span class="nav-label">Manajemen Sistem</span>
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-users"></i></div>
                <span>Pengguna</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-chart-bar"></i></div>
                <span>Laporan Transaksi</span>
            </a>
        </div>
        @endif

        @if(Auth::user()->isAdmin() || Auth::user()->role === 'petugas')
        <div class="nav-group">
            <span class="nav-label">Katalog Produk</span>
            <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-box"></i></div>
                <span>Semua Produk</span>
            </a>
            <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-tags"></i></div>
                <span>Kategori</span>
            </a>
            <a href="{{ route('admin.promos') }}" class="nav-link {{ request()->routeIs('admin.promos') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-percent"></i></div>
                <span>Diskon & Promo</span>
            </a>
            <a href="{{ route('admin.reviews') }}" class="nav-link {{ request()->routeIs('admin.reviews') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-comment-dots"></i></div>
                <span>Ulasan Customer</span>
            </a>
            <a href="{{ route('admin.messages') }}" class="nav-link {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-envelope-open-text"></i></div>
                <span>Pesan Customer</span>
                @php
                    $unreadMessagesCount = \App\Models\Message::where('sender_id', '!=', Auth::id())->where('is_read', false)->count();
                @endphp
                @if($unreadMessagesCount > 0)
                    <span class="nav-badge danger">{{ $unreadMessagesCount }}</span>
                @endif
            </a>
        </div>

        <div class="nav-group">
            <span class="nav-label">Transaksi</span>
            <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-shopping-cart"></i></div>
                <span>Pesanan</span>
                @php
                    $newOrdersCount = \App\Models\Order::whereIn('status', ['pending', 'verifying', 'processing'])->count();
                @endphp
                @if($newOrdersCount > 0)
                    <span class="nav-badge">{{ $newOrdersCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.stock_requests') }}" class="nav-link {{ request()->routeIs('admin.stock_requests') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div>
                <span>Permintaan Stok</span>
                @php
                    $pendingRequestsCount = \App\Models\StockRequest::where('status', 'pending')->count();
                @endphp
                @if(Auth::user()->isAdmin() && $pendingRequestsCount > 0)
                    <span class="nav-badge warning">{{ $pendingRequestsCount }}</span>
                @endif
            </a>
        </div>
        @endif

        <div class="nav-group">
            <span class="nav-label">Akun</span>
            <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-user-circle"></i></div>
                <span>Profil Saya</span>
            </a>
            @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <div class="nav-icon"><i class="fas fa-cog"></i></div>
                <span>Pengaturan Sistem</span>
            </a>
            @endif
        </div>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>

<style>
    .admin-sidebar {
        width: var(--sidebar-width, 270px);
        background: var(--sidebar-bg, #0f172a);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
        z-index: 1000;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    /* Header / Logo */
    .sidebar-header {
        padding: 24px 24px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .sidebar-header .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }
    .logo-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 16px;
        flex-shrink: 0;
    }
    .logo-text {
        font-size: 20px;
        font-weight: 800;
        color: #f8fafc;
        letter-spacing: -0.5px;
    }
    .logo-text span { color: #e74c3c; }

    /* Navigation */
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        padding: 16px 0;
    }
    .nav-group {
        margin-bottom: 8px;
    }
    .nav-label {
        display: block;
        padding: 8px 24px 6px;
        font-size: 10px;
        font-weight: 700;
        color: rgba(148, 163, 184, 0.6);
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }
    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 24px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        transition: all 0.2s ease;
        position: relative;
        margin: 2px 12px;
        border-radius: 10px;
    }
    .nav-link:hover {
        background: rgba(255,255,255,0.06);
        color: #e2e8f0;
    }
    .nav-link.active {
        color: #fff;
        background: rgba(231, 76, 60, 0.15);
    }
    .nav-link.active .nav-icon {
        color: #e74c3c;
    }
    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 24px;
        width: 3px;
        background: #e74c3c;
        border-radius: 0 4px 4px 0;
    }
    .nav-icon {
        width: 20px;
        text-align: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .nav-badge {
        margin-left: auto;
        background: #e74c3c;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
        min-width: 20px;
        text-align: center;
        line-height: 16px;
    }
    .nav-badge.warning {
        background: #f59e0b;
    }

    /* Footer / Logout */
    .sidebar-footer {
        padding: 16px 20px;
        border-top: 1px solid rgba(255,255,255,0.06);
    }
    .logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 10px;
        color: #94a3b8;
        font-size: 13px;
        font-weight: 500;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #fca5a5;
        border-color: rgba(239, 68, 68, 0.2);
    }

    /* Scrollbar */
    .sidebar-nav::-webkit-scrollbar { width: 3px; }
    .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
    .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>
