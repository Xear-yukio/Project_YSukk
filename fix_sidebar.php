<?php

$file = 'c:/Users/Admin/Projek_YSukk/resources/views/components/admin/sidebar.blade.php';
$content = file_get_contents($file);

// 1. Pengguna
$content = str_replace(
    '<a href="#" class="nav-link">
                <i class="fas fa-users"></i>
                <span>Pengguna</span>
            </a>',
    '<a href="{{ route(\'admin.users\') }}" class="nav-link {{ request()->routeIs(\'admin.users\') ? \'active\' : \'\' }}">
                <i class="fas fa-users"></i>
                <span>Pengguna</span>
            </a>',
    $content
);

// 2. Laporan Transaksi
$content = str_replace(
    '<a href="#" class="nav-link">
                <i class="fas fa-chart-line"></i>
                <span>Laporan Transaksi</span>
            </a>',
    '<a href="{{ route(\'admin.reports\') }}" class="nav-link {{ request()->routeIs(\'admin.reports\') ? \'active\' : \'\' }}">
                <i class="fas fa-chart-line"></i>
                <span>Laporan Transaksi</span>
            </a>',
    $content
);

// 3. Semua Produk
$content = str_replace(
    '<a href="#" class="nav-link">
                <i class="fas fa-box"></i>
                <span>Semua Produk</span>
            </a>',
    '<a href="{{ route(\'admin.products\') }}" class="nav-link {{ request()->routeIs(\'admin.products\') ? \'active\' : \'\' }}">
                <i class="fas fa-box"></i>
                <span>Semua Produk</span>
            </a>',
    $content
);

// 4. Kategori
$content = str_replace(
    '<a href="#" class="nav-link">
                <i class="fas fa-tags"></i>
                <span>Kategori</span>
            </a>',
    '<a href="{{ route(\'admin.categories\') }}" class="nav-link {{ request()->routeIs(\'admin.categories\') ? \'active\' : \'\' }}">
                <i class="fas fa-tags"></i>
                <span>Kategori</span>
            </a>',
    $content
);

// 5. Pesanan Baru (link to admin.orders)
$content = str_replace(
    '<a href="#" class="nav-link">
                <i class="fas fa-shopping-cart"></i>
                <span>Pesanan Baru</span>
                <span class="badge">5</span>
            </a>',
    '<a href="{{ route(\'admin.orders\') }}" class="nav-link {{ request()->routeIs(\'admin.orders\') ? \'active\' : \'\' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Pesanan Baru</span>
                <span class="badge">5</span>
            </a>',
    $content
);

// 6. Riwayat Pesanan (link to admin.orders)
$content = str_replace(
    '<a href="#" class="nav-link">
                <i class="fas fa-history"></i>
                <span>Riwayat Pesanan</span>
            </a>',
    '<a href="{{ route(\'admin.orders\') }}" class="nav-link">
                <i class="fas fa-history"></i>
                <span>Riwayat Pesanan</span>
            </a>',
    $content
);

// 7. Pengaturan
$content = str_replace(
    '<a href="#" class="nav-link">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>',
    '<a href="{{ route(\'admin.settings\') }}" class="nav-link {{ request()->routeIs(\'admin.settings\') ? \'active\' : \'\' }}">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>',
    $content
);

file_put_contents($file, $content);
echo "Sidebar routes fixed\n";
