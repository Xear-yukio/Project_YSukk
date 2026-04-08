@extends('layouts.admin')

@section('title', 'Detail Pesanan #ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' - Belanja.ID')
@section('page_title', 'Detail Pesanan')

@section('content')
    <div class="order-detail-page">
        {{-- Back Button --}}
        <a href="{{ route('admin.orders') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>

        {{-- Order Header --}}
        <div class="order-header-card">
            <div class="order-header-left">
                <h1 class="order-title">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
                <span class="order-timestamp"><i class="far fa-clock"></i> {{ $order->created_at->format('d M Y, H:i') }}
                    WIB</span>
            </div>
            <div class="order-header-right">
                @php
                    $statusMap = [
                        'pending' => ['label' => 'Menunggu Pembayaran', 'class' => 'warning', 'icon' => 'fa-clock'],
                        'verifying' => ['label' => 'Menunggu Verifikasi', 'class' => 'verify', 'icon' => 'fa-search'],
                        'processing' => ['label' => 'Sedang Diproses', 'class' => 'info', 'icon' => 'fa-cog'],
                        'shipped' => ['label' => 'Sedang Dikirim', 'class' => 'shipped', 'icon' => 'fa-truck'],
                        'success' => ['label' => 'Selesai', 'class' => 'success', 'icon' => 'fa-check-circle'],
                        'cancelled' => ['label' => 'Dibatalkan', 'class' => 'danger', 'icon' => 'fa-times-circle'],
                    ];
                    $s = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'info', 'icon' => 'fa-info'];
                @endphp
                <span class="status-big {{ $s['class'] }}">
                    <i class="fas {{ $s['icon'] }}"></i> {{ $s['label'] }}
                </span>
            </div>
        </div>

        <div class="detail-grid">
            {{-- Left: Order Info --}}
            <div class="detail-main">
                {{-- Customer Info --}}
                <div class="detail-card">
                    <h3 class="card-heading"><i class="fas fa-user"></i> Informasi Pelanggan</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value">{{ $order->full_name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $order->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">No. Telepon</span>
                            <span class="info-value">{{ $order->phone }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Metode Bayar</span>
                            <span class="info-value"
                                style="text-transform: uppercase; font-weight: 700;">{{ $order->payment_method }}</span>
                        </div>
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="detail-card">
                    <h3 class="card-heading"><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</h3>
                    <div class="address-block">
                        <p><strong>{{ $order->full_name }}</strong></p>
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->district }}, {{ $order->city }}</p>
                        <p>{{ $order->province }}, {{ $order->postal_code }}</p>
                    </div>
                    @if($order->notes)
                        <div class="notes-block">
                            <span class="info-label">Catatan:</span>
                            <p>{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Order Items --}}
                <div class="detail-card">
                    <h3 class="card-heading"><i class="fas fa-shopping-bag"></i> Produk Pesanan</h3>
                    <div class="items-list">
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}">
                                    @else
                                        <div class="item-placeholder"><i class="fas fa-box"></i></div>
                                    @endif
                                </div>
                                <div class="item-info">
                                    <span class="item-name">{{ $item->product_name }}</span>
                                    <span class="item-qty">{{ $item->quantity }}x @ Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="item-total">
                                    Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="order-total-row">
                        <span>Total Pembayaran</span>
                        <span class="total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Right: Payment Proof & Actions --}}
            <div class="detail-sidebar">
                {{-- Payment Proof --}}
                <div class="detail-card proof-card">
                    <h3 class="card-heading"><i class="fas fa-file-image"></i> Bukti Pembayaran</h3>
                    @if($order->payment_proof)
                        <div class="proof-image-wrap">
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran"
                                onclick="showProofFull(this.src)">
                            <span class="proof-hint"><i class="fas fa-search-plus"></i> Klik untuk memperbesar</span>
                        </div>
                    @elseif($order->payment_method == 'cod')
                        <div class="proof-empty">
                            <i class="fas fa-truck"></i>
                            <p>Bayar di Tempat (COD)</p>
                            <span>Tidak memerlukan bukti pembayaran</span>
                        </div>
                    @else
                        <div class="proof-empty">
                            <i class="fas fa-image"></i>
                            <p>Belum ada bukti</p>
                            <span>Pelanggan belum mengunggah bukti pembayaran</span>
                        </div>
                    @endif
                </div>

                {{-- Admin Actions --}}
                <div class="detail-card actions-card">
                    <h3 class="card-heading"><i class="fas fa-tools"></i> Aksi</h3>

                    @if($order->status === 'verifying')
                        {{-- Primary: Confirm Payment → Shipped --}}
                        <div class="action-highlight">
                            <div class="action-info">
                                <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                                <p>Pelanggan telah mengirim bukti pembayaran. Silakan verifikasi dan konfirmasi.</p>
                            </div>
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="shipped">
                                <button type="submit" class="btn-full btn-confirm-big">
                                    <i class="fas fa-check-circle"></i> Konfirmasi & Kirim Pesanan
                                </button>
                            </form>
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                                style="margin-top: 8px;">
                                @csrf
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="btn-full btn-reject-big">
                                    <i class="fas fa-times-circle"></i> Tolak Pembayaran
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Generic Status Change --}}
                        <p class="action-subtitle">Ubah status pesanan:</p>
                        <div class="status-actions">
                            @php
                                $transitions = [
                                    'pending' => ['processing' => 'Proses', 'cancelled' => 'Batalkan'],
                                    'processing' => ['shipped' => 'Kirim', 'cancelled' => 'Batalkan'],
                                    'shipped' => ['success' => 'Selesai'],
                                    'success' => [],
                                    'cancelled' => ['pending' => 'Aktifkan Kembali'],
                                ];
                                $available = $transitions[$order->status] ?? [];
                            @endphp
                            @if(count($available) > 0)
                                @foreach($available as $key => $label)
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $key }}">
                                        <button type="submit"
                                            class="btn-full btn-status-action {{ $key === 'cancelled' ? 'btn-danger-outline' : '' }}">
                                            {{ $label }}
                                        </button>
                                    </form>
                                @endforeach
                            @else
                                <p class="no-actions">Tidak ada aksi tersedia untuk status ini.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Fullscreen Image Modal --}}
    <div class="fullscreen-modal" id="fullscreenModal" onclick="closeFullscreen()">
        <img id="fullscreenImage" src="" alt="Bukti Pembayaran">
    </div>

    @push('styles')
        <style>
            .order-detail-page {
                animation: fadeUp 0.4s ease-out;
            }

            @keyframes fadeUp {
                from {
                    opacity: 0;
                    transform: translateY(12px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                font-weight: 600;
                color: var(--text-secondary);
                text-decoration: none;
                margin-bottom: 20px;
                transition: color 0.2s;
            }

            .back-link:hover {
                color: var(--primary);
            }

            /* Header Card */
            .order-header-card {
                background: linear-gradient(135deg, #0f172a, #1e293b);
                border-radius: var(--radius-xl);
                padding: 28px 32px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 24px;
                flex-wrap: wrap;
                gap: 16px;
            }

            .order-title {
                font-size: 24px;
                font-weight: 800;
                color: #f8fafc;
                letter-spacing: -0.5px;
                margin-bottom: 4px;
            }

            .order-timestamp {
                font-size: 13px;
                color: #94a3b8;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .status-big {
                padding: 8px 20px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .status-big.warning {
                background: rgba(245, 158, 11, 0.15);
                color: #fbbf24;
            }

            .status-big.verify {
                background: rgba(236, 72, 153, 0.15);
                color: #f472b6;
            }

            .status-big.info {
                background: rgba(59, 130, 246, 0.15);
                color: #60a5fa;
            }

            .status-big.shipped {
                background: rgba(16, 185, 129, 0.15);
                color: #34d399;
            }

            .status-big.success {
                background: rgba(34, 197, 94, 0.15);
                color: #4ade80;
            }

            .status-big.danger {
                background: rgba(239, 68, 68, 0.15);
                color: #f87171;
            }

            /* Grid */
            .detail-grid {
                display: grid;
                grid-template-columns: 1.6fr 1fr;
                gap: 24px;
            }

            @media (max-width: 1024px) {
                .detail-grid {
                    grid-template-columns: 1fr;
                }
            }

            /* Cards */
            .detail-card {
                background: var(--surface);
                border-radius: var(--radius-lg);
                border: 1px solid var(--border-light);
                padding: 24px;
                margin-bottom: 20px;
            }

            .card-heading {
                font-size: 15px;
                font-weight: 700;
                color: var(--text-primary);
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .card-heading i {
                color: var(--primary);
                font-size: 14px;
            }

            /* Info Grid */
            .info-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }

            .info-item {}

            .info-label {
                display: block;
                font-size: 11px;
                font-weight: 700;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 4px;
            }

            .info-value {
                font-size: 14px;
                font-weight: 600;
                color: var(--text-primary);
            }

            /* Address */
            .address-block p {
                font-size: 14px;
                color: var(--text-secondary);
                line-height: 1.7;
            }

            .address-block p strong {
                color: var(--text-primary);
            }

            .notes-block {
                margin-top: 16px;
                padding-top: 16px;
                border-top: 1px dashed var(--border-light);
            }

            .notes-block p {
                font-size: 13px;
                color: var(--text-secondary);
                margin-top: 4px;
            }

            /* Items */
            .items-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 16px;
            }

            .order-item {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 12px;
                border-radius: var(--radius-sm);
                background: #fafbfc;
                border: 1px solid var(--border-light);
            }

            .item-image {
                width: 52px;
                height: 52px;
                border-radius: 8px;
                overflow: hidden;
                flex-shrink: 0;
                background: #fff;
                border: 1px solid var(--border-light);
            }

            .item-image img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                padding: 4px;
            }

            .item-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #cbd5e1;
                font-size: 20px;
            }

            .item-info {
                flex: 1;
            }

            .item-name {
                display: block;
                font-size: 13px;
                font-weight: 600;
                color: var(--text-primary);
                margin-bottom: 2px;
            }

            .item-qty {
                font-size: 12px;
                color: var(--text-muted);
            }

            .item-total {
                font-size: 14px;
                font-weight: 700;
                color: var(--text-primary);
                white-space: nowrap;
            }

            .order-total-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-top: 16px;
                border-top: 2px solid var(--border-light);
            }

            .order-total-row span:first-child {
                font-size: 14px;
                font-weight: 600;
                color: var(--text-secondary);
            }

            .total-amount {
                font-size: 20px;
                font-weight: 800;
                color: var(--primary);
            }

            /* Proof Card */
            .proof-image-wrap {
                text-align: center;
            }

            .proof-image-wrap img {
                width: 100%;
                border-radius: var(--radius-md);
                cursor: pointer;
                transition: transform 0.2s;
                border: 1px solid var(--border-light);
            }

            .proof-image-wrap img:hover {
                transform: scale(1.02);
            }

            .proof-hint {
                display: block;
                margin-top: 10px;
                font-size: 11px;
                color: var(--text-muted);
            }

            .proof-empty {
                text-align: center;
                padding: 32px 16px;
                color: var(--text-muted);
            }

            .proof-empty i {
                font-size: 36px;
                margin-bottom: 12px;
                display: block;
                opacity: 0.3;
            }

            .proof-empty p {
                font-weight: 600;
                font-size: 14px;
                margin-bottom: 4px;
                color: var(--text-secondary);
            }

            .proof-empty span {
                font-size: 12px;
            }

            /* Actions Card */
            .action-highlight {
                background: #fffbeb;
                border: 1px solid #fef3c7;
                border-radius: var(--radius-md);
                padding: 16px;
            }

            .action-info {
                display: flex;
                gap: 10px;
                margin-bottom: 16px;
                align-items: flex-start;
            }

            .action-info i {
                flex-shrink: 0;
                margin-top: 2px;
            }

            .action-info p {
                font-size: 13px;
                color: #92400e;
                line-height: 1.5;
            }

            .btn-full {
                width: 100%;
                padding: 12px;
                border-radius: var(--radius-sm);
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                border: none;
                font-family: inherit;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                transition: all 0.2s;
            }

            .btn-confirm-big {
                background: #10b981;
                color: #fff;
            }

            .btn-confirm-big:hover {
                background: #059669;
            }

            .btn-reject-big {
                background: transparent;
                color: #ef4444;
                border: 1px solid #fecaca;
            }

            .btn-reject-big:hover {
                background: #fef2f2;
            }

            .action-subtitle {
                font-size: 12px;
                color: var(--text-muted);
                margin-bottom: 12px;
            }

            .status-actions {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .btn-status-action {
                background: var(--primary);
                color: #fff;
            }

            .btn-status-action:hover {
                background: var(--primary-hover);
            }

            .btn-danger-outline {
                background: transparent;
                color: #ef4444;
                border: 1px solid #fecaca;
            }

            .btn-danger-outline:hover {
                background: #fef2f2;
            }

            .no-actions {
                font-size: 13px;
                color: var(--text-muted);
                text-align: center;
                padding: 16px;
            }

            /* Fullscreen Modal */
            .fullscreen-modal {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.85);
                z-index: 9999;
                align-items: center;
                justify-content: center;
                padding: 40px;
                cursor: zoom-out;
            }

            .fullscreen-modal.show {
                display: flex;
            }

            .fullscreen-modal img {
                max-width: 100%;
                max-height: 85vh;
                border-radius: var(--radius-md);
                box-shadow: 0 0 60px rgba(0, 0, 0, 0.5);
            }

            .detail-sidebar {
                display: flex;
                flex-direction: column;
            }

            .detail-sidebar .detail-card {
                margin-bottom: 20px;
            }
        </style>
    @endpush

    <script>
        function showProofFull(src) {
            const modal = document.getElementById('fullscreenModal');
            const img = document.getElementById('fullscreenImage');
            img.src = src;
            modal.classList.add('show');
        }

        function closeFullscreen() {
            document.getElementById('fullscreenModal').classList.remove('show');
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeFullscreen();
        });
    </script>
@endsection