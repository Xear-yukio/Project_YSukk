<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Belanja.ID')</title>
    <meta name="description" content="Panel administrasi Belanja.ID - Kelola toko online Anda dengan mudah">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --sidebar-width: 270px;
            --topbar-height: 70px;

            /* Colors */
            --primary: #e74c3c;
            --primary-hover: #d63031;
            --primary-soft: rgba(231, 76, 60, 0.08);

            --accent-blue: #3b82f6;
            --accent-green: #10b981;
            --accent-amber: #f59e0b;
            --accent-purple: #8b5cf6;

            /* Neutrals */
            --sidebar-bg: #0f172a;
            --sidebar-hover: rgba(255,255,255,0.06);
            --sidebar-active: rgba(231, 76, 60, 0.15);

            --surface: #ffffff;
            --surface-hover: #f8fafc;
            --bg: #f1f5f9;
            --border: #e2e8f0;
            --border-light: #f1f5f9;

            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --text-inverse: #f8fafc;

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;

            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.08);
        }

        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /* ===== LAYOUT ===== */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-content {
            padding: 32px;
            flex: 1;
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .admin-main { margin-left: 0; }
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-content { padding: 20px; }
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 998;
                backdrop-filter: blur(4px);
            }
            .sidebar-overlay.show { display: block; }
        }

        @media (max-width: 640px) {
            .admin-content { padding: 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="admin-layout">
        {{-- Sidebar Overlay for Mobile --}}
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        {{-- Sidebar --}}
        @include('components.admin.sidebar')

        <div class="admin-main">
            {{-- Topbar --}}
            @include('components.admin.topbar')

            {{-- Main Content --}}
            <main class="admin-content">
                {{-- Flash Messages --}}
                @if(session('success'))
                <div class="flash-alert flash-success" id="flash-alert">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="flash-close">&times;</button>
                </div>
                @endif

                @if(session('error'))
                <div class="flash-alert flash-error" id="flash-alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="flash-close">&times;</button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <style>
        .flash-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            animation: slideDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .flash-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .flash-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .flash-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: inherit;
            opacity: 0.6;
            line-height: 1;
        }
        .flash-close:hover { opacity: 1; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('.admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                overlay?.classList.toggle('show');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar?.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // Auto-hide flash alerts
        document.querySelectorAll('.flash-alert').forEach(el => {
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-12px)';
                el.style.transition = 'all 0.3s ease';
                setTimeout(() => el.remove(), 300);
            }, 4000);
        });
    </script>
    @stack('scripts')
</body>
</html>
