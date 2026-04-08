@extends('layouts.admin')

@section('title', 'Admin Dashboard - Belanja.ID')
@section('page_title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    {{-- Welcome Banner --}}
    <div class="welcome-banner">
        <div class="welcome-content">
            <div class="welcome-greeting">
                <h1>Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                <p>Berikut ringkasan kinerja toko Anda hari ini.</p>
            </div>
            <div class="welcome-meta">
                <div class="date-badge">
                    <i class="far fa-calendar-alt"></i>
                    <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-wrap blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Total Pengguna</span>
                <h3 class="stat-value">{{ number_format($totalUsers) }}</h3>
            </div>
            <div class="stat-footer">
                <span class="stat-tag blue"><i class="fas fa-user-check"></i> Aktif</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrap amber">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Pesanan Baru</span>
                <h3 class="stat-value">{{ number_format($newOrdersCount) }}</h3>
            </div>
            <div class="stat-footer">
                <span class="stat-tag amber"><i class="fas fa-clock"></i> Perlu diproses</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrap green">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Pendapatan</span>
                <h3 class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="stat-footer">
                <span class="stat-tag green"><i class="fas fa-check-circle"></i> Terverifikasi</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-wrap purple">
                <i class="fas fa-box-open"></i>
            </div>
            <div class="stat-body">
                <span class="stat-label">Produk Terjual</span>
                <h3 class="stat-value">{{ number_format($totalProductsSold) }}</h3>
            </div>
            <div class="stat-footer">
                <span class="stat-tag purple"><i class="fas fa-chart-line"></i> Total item</span>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="content-grid">
        {{-- Recent Transactions --}}
        <div class="card transactions-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Transaksi Terbaru</h3>
                    <p class="card-subtitle">5 pesanan terakhir yang masuk</p>
                </div>
                <a href="{{ route('admin.orders') }}" class="btn-action">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th style="text-align:right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <span class="order-id">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="customer-cell">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->full_name) }}&background=f1f5f9&color=64748b&size=64&font-size=0.4" alt="" class="customer-thumb">
                                    <span class="customer-name">{{ $order->full_name }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'pending'    => ['label' => 'Menunggu',  'class' => 'warning'],
                                        'verifying'  => ['label' => 'Verifikasi','class' => 'info'],
                                        'processing' => ['label' => 'Proses',    'class' => 'info'],
                                        'shipped'    => ['label' => 'Dikirim',   'class' => 'info'],
                                        'success'    => ['label' => 'Selesai',   'class' => 'success'],
                                        'cancelled'  => ['label' => 'Batal',     'class' => 'danger'],
                                    ];
                                    $s = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'info'];
                                @endphp
                                <span class="status-pill {{ $s['class'] }}">{{ $s['label'] }}</span>
                            </td>
                            <td style="text-align:right">
                                <span class="amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty-row">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada transaksi terbaru</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="side-column">
            {{-- Category Stats --}}
            <div class="card category-card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Kategori Terlaris</h3>
                        <p class="card-subtitle">Berdasarkan jumlah terjual</p>
                    </div>
                </div>
                
                <div class="category-list">
                    @forelse($categoryStats as $index => $stat)
                    <div class="category-row" style="animation-delay: {{ $index * 100 }}ms">
                        <div class="category-meta">
                            <div class="category-dot" style="background: {{ $stat['color'] }}"></div>
                            <span class="category-name">{{ $stat['name'] }}</span>
                            <span class="category-pct">{{ $stat['percentage'] }}%</span>
                        </div>
                        <div class="category-bar">
                            <div class="category-fill" style="width: {{ $stat['percentage'] }}%; background: {{ $stat['color'] }};"></div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state mini">
                        <i class="fas fa-chart-pie"></i>
                        <p>Data belum tersedia</p>
                    </div>
                    @endforelse
                </div>

                <div class="card-footnote">
                    <i class="fas fa-info-circle"></i>
                    Data dihitung dari pesanan yang selesai
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card quick-actions-card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Aksi Cepat</h3>
                    </div>
                </div>
                <div class="quick-actions-grid">
                    <a href="{{ route('admin.products') }}" class="quick-action">
                        <div class="qa-icon blue"><i class="fas fa-plus"></i></div>
                        <span>Tambah Produk</span>
                    </a>
                    <a href="{{ route('admin.orders') }}" class="quick-action">
                        <div class="qa-icon amber"><i class="fas fa-clipboard-check"></i></div>
                        <span>Kelola Pesanan</span>
                    </a>
                    <a href="{{ route('admin.categories') }}" class="quick-action">
                        <div class="qa-icon green"><i class="fas fa-tags"></i></div>
                        <span>Kategori</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="quick-action">
                        <div class="qa-icon purple"><i class="fas fa-chart-bar"></i></div>
                        <span>Laporan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* ===== DASHBOARD STYLES ===== */

    .dashboard-container {
        animation: fadeUp 0.5s ease-out;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: var(--radius-xl);
        padding: 32px 36px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(231,76,60,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: 20%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(59,130,246,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .welcome-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .welcome-greeting h1 {
        font-size: 22px;
        font-weight: 800;
        color: #f8fafc;
        margin-bottom: 4px;
        letter-spacing: -0.3px;
    }
    .welcome-greeting p {
        color: #94a3b8;
        font-size: 14px;
        font-weight: 400;
    }
    .date-badge {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(8px);
        padding: 10px 18px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .date-badge i { color: #e74c3c; }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

    .stat-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 22px;
        border: 1px solid var(--border-light);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    .stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .stat-icon-wrap.blue { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .stat-icon-wrap.amber { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .stat-icon-wrap.green { background: rgba(16,185,129,0.1); color: #10b981; }
    .stat-icon-wrap.purple { background: rgba(139,92,246,0.1); color: #8b5cf6; }

    .stat-body { display: flex; flex-direction: column; gap: 2px; }
    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.5px;
        margin: 0;
    }
    .stat-footer {
        padding-top: 12px;
        border-top: 1px solid var(--border-light);
    }
    .stat-tag {
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 6px;
    }
    .stat-tag.blue { color: #3b82f6; background: rgba(59,130,246,0.08); }
    .stat-tag.amber { color: #d97706; background: rgba(245,158,11,0.08); }
    .stat-tag.green { color: #059669; background: rgba(16,185,129,0.08); }
    .stat-tag.purple { color: #7c3aed; background: rgba(139,92,246,0.08); }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 1.7fr 1fr;
        gap: 24px;
    }
    @media (max-width: 1024px) {
        .content-grid { grid-template-columns: 1fr; }
    }

    /* Card */
    .card {
        background: var(--surface);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 24px 0;
        margin-bottom: 20px;
    }
    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    .card-subtitle {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 500;
    }
    .btn-action {
        font-size: 12px;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: var(--primary-soft);
        border-radius: var(--radius-sm);
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-action:hover {
        background: var(--primary);
        color: #fff;
    }

    /* Table */
    .table-wrap {
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table th {
        text-align: left;
        padding: 12px 24px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-light);
        background: #fafbfc;
    }
    .data-table td {
        padding: 16px 24px;
        font-size: 13.5px;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tbody tr { transition: background 0.15s; }
    .data-table tbody tr:hover td { background: var(--surface-hover); }

    .order-id {
        font-weight: 700;
        color: var(--primary);
        font-size: 13px;
        font-family: 'SF Mono', 'Fira Code', monospace;
    }
    .customer-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .customer-thumb {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .customer-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 13px;
    }
    .amount {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 13px;
    }

    /* Status Pills */
    .status-pill {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
    }
    .status-pill.success { background: #dcfce7; color: #15803d; }
    .status-pill.warning { background: #fef3c7; color: #92400e; }
    .status-pill.info { background: #dbeafe; color: #1d4ed8; }
    .status-pill.danger { background: #fee2e2; color: #b91c1c; }

    /* Side Column */
    .side-column {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Category Card */
    .category-list {
        padding: 0 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .category-row {
        animation: fadeUp 0.5s ease-out both;
    }
    .category-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .category-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .category-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-primary);
        flex: 1;
    }
    .category-pct {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-secondary);
    }
    .category-bar {
        height: 8px;
        background: var(--border-light);
        border-radius: 20px;
        overflow: hidden;
    }
    .category-fill {
        height: 100%;
        border-radius: 20px;
        transition: width 1.2s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .card-footnote {
        padding: 16px 24px;
        margin-top: 20px;
        border-top: 1px solid var(--border-light);
        font-size: 11px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-footnote i { color: var(--accent-blue); }

    /* Quick Actions */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding: 0 24px 24px;
    }
    .quick-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 20px 12px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-light);
        text-decoration: none;
        transition: all 0.25s ease;
        text-align: center;
    }
    .quick-action:hover {
        border-color: var(--border);
        background: var(--surface-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }
    .quick-action span {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-secondary);
    }
    .qa-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }
    .qa-icon.blue { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .qa-icon.amber { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .qa-icon.green { background: rgba(16,185,129,0.1); color: #10b981; }
    .qa-icon.purple { background: rgba(139,92,246,0.1); color: #8b5cf6; }

    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 32px 20px;
        color: var(--text-muted);
    }
    .empty-state i {
        font-size: 32px;
        margin-bottom: 12px;
        display: block;
        opacity: 0.4;
    }
    .empty-state p { font-size: 13px; font-weight: 500; }
    .empty-state.mini { padding: 28px 20px; }
    .empty-row { padding: 0 !important; }

    /* Responsive polish */
    @media (max-width: 640px) {
        .welcome-banner { padding: 24px; }
        .welcome-greeting h1 { font-size: 18px; }
        .card-header { padding: 20px 20px 0; }
        .data-table th, .data-table td { padding: 12px 16px; }
        .category-list { padding: 0 20px; }
        .quick-actions-grid { padding: 0 20px 20px; }
    }
</style>
@endpush
@endsection
