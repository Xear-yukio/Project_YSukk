<!-- Topbar Component -->
@php
    $pendingOrders = \App\Models\Order::where('status', 'pending')->latest()->take(5)->get();
    $openConversations = \App\Models\Conversation::where('status', 'open')->with(['user', 'latestMessage'])->latest()->take(5)->get();
    $notifCount = $pendingOrders->count() + $openConversations->count();
@endphp
<header class="admin-topbar">
    <div class="topbar-left">
        <button id="sidebar-toggle" class="topbar-btn mobile-only" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="topbar-breadcrumb">
            <h1 class="page-title">@yield('page_title', 'Dashboard')</h1>
        </div>
    </div>

    <div class="topbar-right">
        <form action="{{ url()->current() }}" method="GET" class="topbar-search" id="topbar-search">
            <i class="fas fa-search"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari data..." autocomplete="off">
        </form>
        
        <div class="topbar-actions">
            {{-- Notification Bell --}}
            <div class="notification-wrapper" id="notif-wrapper">
                <div class="topbar-btn notification-btn" id="notif-btn">
                    <i class="far fa-bell"></i>
                    @if($notifCount > 0)
                    <span class="notif-dot">{{ $notifCount > 99 ? '99+' : $notifCount }}</span>
                    @endif
                </div>

                {{-- Notification Dropdown --}}
                <div class="notif-dropdown" id="notif-dropdown">
                    <div class="notif-dropdown-header">
                        <h4>Notifikasi</h4>
                        @if($notifCount > 0)
                        <span class="notif-header-badge">{{ $notifCount }} baru</span>
                        @endif
                    </div>

                    {{-- Tabs --}}
                    <div class="notif-tabs">
                        <button class="notif-tab active" data-tab="all" onclick="switchNotifTab('all')">
                            Semua <span class="tab-count">{{ $notifCount }}</span>
                        </button>
                        <button class="notif-tab" data-tab="orders" onclick="switchNotifTab('orders')">
                            Pesanan <span class="tab-count">{{ $pendingOrders->count() }}</span>
                        </button>
                        <button class="notif-tab" data-tab="messages" onclick="switchNotifTab('messages')">
                            Pesan <span class="tab-count">{{ $openConversations->count() }}</span>
                        </button>
                    </div>

                    {{-- Notification List --}}
                    <div class="notif-list" id="notif-list">
                        @if($notifCount === 0)
                            <div class="notif-empty">
                                <i class="far fa-bell-slash"></i>
                                <p>Tidak ada notifikasi baru</p>
                            </div>
                        @endif

                        {{-- Pending Orders --}}
                        @foreach($pendingOrders as $order)
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="notif-item" data-type="orders">
                            <div class="notif-icon notif-icon-order">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="notif-body">
                                <p class="notif-text"><strong>{{ $order->full_name }}</strong> membuat pesanan baru</p>
                                <span class="notif-meta">
                                    <span class="notif-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    &bull; {{ $order->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <span class="notif-status-dot"></span>
                        </a>
                        @endforeach

                        {{-- Open Conversations --}}
                        @foreach($openConversations as $conv)
                        <a href="{{ route('admin.messages.show', $conv->id) }}" class="notif-item" data-type="messages">
                            <div class="notif-icon notif-icon-message">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="notif-body">
                                <p class="notif-text"><strong>{{ $conv->user->name }}</strong> mengirim pesan</p>
                                <span class="notif-meta">
                                    {{ Str::limit($conv->subject, 30) }}
                                    &bull; {{ $conv->updated_at->diffForHumans() }}
                                </span>
                            </div>
                            <span class="notif-status-dot"></span>
                        </a>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div class="notif-dropdown-footer">
                        <a href="{{ route('admin.orders') }}">
                            <i class="fas fa-shopping-bag"></i> Lihat Semua Pesanan
                        </a>
                        <a href="{{ route('admin.messages') }}">
                            <i class="fas fa-envelope"></i> Lihat Semua Pesan
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="topbar-divider"></div>

            <a href="{{ route('profile.index') }}" class="user-profile" id="user-profile" style="text-decoration: none;">
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
                <div class="user-avatar-wrap">
                    <div id="nav-avatar">
                        @if(Auth::user()->avatar_url)
                            <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="user-avatar">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e74c3c&color=fff&size=80&font-size=0.4&bold=true" alt="Avatar" class="user-avatar">
                        @endif
                    </div>
                    <span class="avatar-status"></span>
                </div>
            </a>
        </div>
    </div>
</header>

<style>
    .admin-topbar {
        height: var(--topbar-height, 70px);
        background: rgba(255,255,255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--border, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
        position: sticky;
        top: 0;
        z-index: 900;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .topbar-btn {
        background: none;
        border: none;
        color: var(--text-secondary, #64748b);
        font-size: 18px;
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-radius: var(--radius-sm, 8px);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .topbar-btn:hover {
        background: var(--surface-hover, #f8fafc);
        color: var(--primary, #e74c3c);
    }

    .mobile-only { display: none; }
    @media (max-width: 1024px) { .mobile-only { display: flex; } }

    .page-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary, #0f172a);
        letter-spacing: -0.3px;
    }
    
    .topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .topbar-search {
        background: var(--bg, #f1f5f9);
        border-radius: var(--radius-md, 12px);
        padding: 9px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        width: 260px;
        border: 1px solid transparent;
        transition: all 0.25s ease;
    }
    .topbar-search:focus-within {
        background: #fff;
        border-color: var(--primary, #e74c3c);
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.08);
    }
    .topbar-search input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 13px;
        width: 100%;
        font-family: inherit;
        color: var(--text-primary, #0f172a);
    }
    .topbar-search input::placeholder { color: var(--text-muted, #94a3b8); }
    .topbar-search i { color: var(--text-muted, #94a3b8); font-size: 14px; }

    .topbar-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .topbar-divider {
        width: 1px;
        height: 32px;
        background: var(--border, #e2e8f0);
        margin: 0 8px;
    }

    /* === Notification Wrapper === */
    .notification-wrapper { position: relative; }
    .notification-btn { position: relative; }
    .notification-btn.active {
        background: var(--primary-soft, rgba(231, 76, 60, 0.08));
        color: var(--primary, #e74c3c);
    }
    .notif-dot {
        position: absolute;
        top: 6px;
        right: 6px;
        background: #e74c3c;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        min-width: 16px;
        height: 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* === Notification Dropdown === */
    .notif-dropdown {
        position: absolute;
        top: calc(100% + 12px);
        right: -60px;
        width: 400px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 12px 48px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.04);
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px) scale(0.98);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .notif-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }
    .notif-dropdown::before {
        content: '';
        position: absolute;
        top: -6px;
        right: 72px;
        width: 12px;
        height: 12px;
        background: #fff;
        transform: rotate(45deg);
        border-top: 1px solid rgba(0,0,0,0.04);
        border-left: 1px solid rgba(0,0,0,0.04);
    }

    .notif-dropdown-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .notif-dropdown-header h4 {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .notif-header-badge {
        background: #fef2f2;
        color: #e74c3c;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* Tabs */
    .notif-tabs {
        display: flex;
        padding: 0 20px;
        gap: 4px;
        border-bottom: 1px solid #f1f5f9;
    }
    .notif-tab {
        background: none;
        border: none;
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: inherit;
    }
    .notif-tab:hover { color: #64748b; }
    .notif-tab.active {
        color: #e74c3c;
        border-bottom-color: #e74c3c;
    }
    .tab-count {
        background: #f1f5f9;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
        min-width: 18px;
        text-align: center;
    }
    .notif-tab.active .tab-count {
        background: #fef2f2;
        color: #e74c3c;
    }

    /* Notification List */
    .notif-list {
        max-height: 340px;
        overflow-y: auto;
    }
    .notif-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 20px;
        text-decoration: none;
        transition: background 0.15s;
        border-bottom: 1px solid #f8fafc;
        position: relative;
    }
    .notif-item:hover { background: #f8fafc; }
    .notif-item:last-child { border-bottom: none; }

    .notif-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .notif-icon-order {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
    }
    .notif-icon-message {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #2563eb;
    }

    .notif-body { flex: 1; min-width: 0; }
    .notif-text {
        font-size: 13px;
        color: #334155;
        margin: 0;
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .notif-text strong { color: #0f172a; font-weight: 700; }
    .notif-meta {
        font-size: 11px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }
    .notif-amount {
        font-weight: 700;
        color: #e74c3c;
    }
    .notif-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #e74c3c;
        flex-shrink: 0;
        animation: pulse 2s ease-in-out infinite;
    }

    /* Empty State */
    .notif-empty {
        padding: 40px 20px;
        text-align: center;
        color: #94a3b8;
    }
    .notif-empty i { font-size: 32px; margin-bottom: 10px; }
    .notif-empty p { font-size: 13px; margin: 0; }

    /* Footer */
    .notif-dropdown-footer {
        display: flex;
        border-top: 1px solid #f1f5f9;
    }
    .notif-dropdown-footer a {
        flex: 1;
        text-align: center;
        padding: 12px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .notif-dropdown-footer a:first-child { border-right: 1px solid #f1f5f9; }
    .notif-dropdown-footer a:hover {
        background: #f8fafc;
        color: #e74c3c;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 6px 8px 6px 12px;
        border-radius: var(--radius-md, 12px);
        transition: background 0.2s;
    }
    .user-profile:hover { background: var(--surface-hover, #f8fafc); }
    .user-info {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .user-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary, #0f172a);
        line-height: 1.3;
    }
    .user-role {
        font-size: 11px;
        color: var(--text-muted, #94a3b8);
        font-weight: 500;
    }
    .user-avatar-wrap { position: relative; }
    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm, 8px);
        object-fit: cover;
    }
    .avatar-status {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
        background: #10b981;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    @media (max-width: 768px) {
        .admin-topbar { padding: 0 16px; }
        .topbar-search { display: none; }
        .user-info { display: none; }
        .topbar-divider { display: none; }
        .notif-dropdown {
            position: fixed;
            top: 70px;
            right: 10px;
            left: 10px;
            width: auto;
        }
        .notif-dropdown::before { display: none; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifBtn = document.getElementById('notif-btn');
        const notifDropdown = document.getElementById('notif-dropdown');

        // Toggle dropdown
        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = notifDropdown.classList.contains('show');
            notifDropdown.classList.toggle('show');
            notifBtn.classList.toggle('active');
            if (!isOpen) {
                // Add a subtle ring animation on the bell when opening
                notifBtn.querySelector('i').style.animation = 'none';
                requestAnimationFrame(() => {
                    notifBtn.querySelector('i').style.animation = 'bellRing 0.5s ease';
                });
            }
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!document.getElementById('notif-wrapper').contains(e.target)) {
                notifDropdown.classList.remove('show');
                notifBtn.classList.remove('active');
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                notifDropdown.classList.remove('show');
                notifBtn.classList.remove('active');
            }
        });
    });

    // Tab switching
    function switchNotifTab(tab) {
        // Update active tab
        document.querySelectorAll('.notif-tab').forEach(t => t.classList.remove('active'));
        document.querySelector(`.notif-tab[data-tab="${tab}"]`).classList.add('active');

        // Filter items
        document.querySelectorAll('.notif-item').forEach(item => {
            if (tab === 'all') {
                item.style.display = 'flex';
            } else {
                item.style.display = item.dataset.type === tab ? 'flex' : 'none';
            }
        });

        // Show empty state if no visible items
        const visibleItems = document.querySelectorAll('.notif-item[style*="display: flex"], .notif-item:not([style*="display"])');
        let visible = 0;
        document.querySelectorAll('.notif-item').forEach(item => {
            if (item.style.display !== 'none') visible++;
        });

        const emptyEl = document.querySelector('.notif-empty');
        if (emptyEl) {
            emptyEl.style.display = visible === 0 ? 'block' : 'none';
        }
    }
</script>
