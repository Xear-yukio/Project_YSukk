@extends('layouts.admin')

@section('title', 'Manajemen Pesanan - Belanja.ID')
@section('page_title', 'Manajemen Pesanan')

@section('content')
    <div class="orders-admin">
        {{-- Header & Filters --}}
        <div class="orders-header">
            <div class="orders-header-left">
                <h2 class="section-title">Daftar Pesanan</h2>
                <span class="order-count">{{ $orders->count() }} pesanan</span>
            </div>
            <div class="orders-filters">
                <form action="{{ route('admin.orders') }}" method="GET" class="filter-form">
                    <div class="filter-tabs">
                        <a href="{{ route('admin.orders') }}"
                            class="filter-tab {{ !request('status') ? 'active' : '' }}">Semua</a>
                        <a href="{{ route('admin.orders', ['status' => 'pending']) }}"
                            class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
                        <a href="{{ route('admin.orders', ['status' => 'verifying']) }}"
                            class="filter-tab {{ request('status') == 'verifying' ? 'active' : '' }}">
                            Verifikasi
                            @php $verifyCount = \App\Models\Order::where('status', 'verifying')->count(); @endphp
                            @if($verifyCount > 0)<span class="tab-badge">{{ $verifyCount }}</span>@endif
                        </a>
                        <a href="{{ route('admin.orders', ['status' => 'processing']) }}"
                            class="filter-tab {{ request('status') == 'processing' ? 'active' : '' }}">Proses</a>
                        <a href="{{ route('admin.orders', ['status' => 'shipped']) }}"
                            class="filter-tab {{ request('status') == 'shipped' ? 'active' : '' }}">Dikirim</a>
                        <a href="{{ route('admin.orders', ['status' => 'success']) }}"
                            class="filter-tab {{ request('status') == 'success' ? 'active' : '' }}">Selesai</a>
                        <a href="{{ route('admin.orders', ['status' => 'cancelled']) }}"
                            class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="orders-table-card">
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Metode Bayar</th>
                            <th>Total Harga</th>
                            <th>Bukti Bayar</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <span class="order-id">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->full_name) }}&background=f1f5f9&color=64748b&size=64"
                                            alt="" class="customer-avatar">
                                        <div>
                                            <span class="customer-name">{{ $order->full_name }}</span>
                                            <span class="customer-email">{{ $order->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="payment-method">
                                        @if($order->payment_method == 'qris')
                                            <i class="fas fa-qrcode"></i> QRIS
                                        @elseif(in_array($order->payment_method, ['bca', 'bri', 'bni', 'mandiri']))
                                            <i class="fas fa-university"></i> {{ strtoupper($order->payment_method) }}
                                        @elseif(in_array($order->payment_method, ['gopay', 'dana', 'seabank']))
                                            <i class="fas fa-wallet"></i> {{ ucfirst($order->payment_method) }}
                                        @else
                                            <i class="fas fa-truck"></i> COD
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="order-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @if($order->payment_proof)
                                        <button class="btn-proof"
                                            onclick="showProof('{{ asset('storage/' . $order->payment_proof) }}')">
                                            <i class="fas fa-image"></i> Lihat Bukti
                                        </button>
                                    @elseif($order->payment_method == 'cod')
                                        <span class="no-proof">COD</span>
                                    @else
                                        <span class="no-proof">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'pending' => ['label' => 'Menunggu', 'class' => 'warning'],
                                            'verifying' => ['label' => 'Verifikasi', 'class' => 'verify'],
                                            'processing' => ['label' => 'Diproses', 'class' => 'info'],
                                            'shipped' => ['label' => 'Dikirim', 'class' => 'shipped'],
                                            'success' => ['label' => 'Selesai', 'class' => 'success'],
                                            'cancelled' => ['label' => 'Batal', 'class' => 'danger'],
                                        ];
                                        $s = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'info'];
                                    @endphp
                                    <span class="status-pill {{ $s['class'] }}">{{ $s['label'] }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- View Detail --}}
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-icon btn-view"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Confirm Payment: show only when status is 'verifying' --}}
                                        @if($order->status === 'verifying')
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                                                class="inline-form">
                                                @csrf
                                                <input type="hidden" name="status" value="shipped">
                                                <button type="submit" class="btn-icon btn-confirm"
                                                    title="Konfirmasi Pembayaran → Kirim">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST"
                                                class="inline-form">
                                                @csrf
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="btn-icon btn-reject" title="Tolak Pembayaran">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Status Update Dropdown for non-verifying --}}
                                        @if($order->status !== 'verifying')
                                            <div class="status-dropdown-wrap">
                                                <button class="btn-icon btn-status" title="Ubah Status"
                                                    onclick="toggleDropdown(this)">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                                <div class="status-dropdown">
                                                    @php
                                                        $allStatuses = [
                                                            'pending' => 'Menunggu',
                                                            'processing' => 'Diproses',
                                                            'shipped' => 'Dikirim',
                                                            'success' => 'Selesai',
                                                            'cancelled' => 'Batal',
                                                        ];
                                                    @endphp
                                                    @foreach($allStatuses as $key => $label)
                                                        @if($key !== $order->status)
                                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="status" value="{{ $key }}">
                                                                <button type="submit" class="dropdown-item">{{ $label }}</button>
                                                            </form>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-orders">
                                        <i class="fas fa-inbox"></i>
                                        <p>Tidak ada pesanan ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Payment Proof Modal --}}
    <div class="proof-modal" id="proofModal">
        <div class="proof-modal-overlay" onclick="closeModal()"></div>
        <div class="proof-modal-content">
            <div class="proof-modal-header">
                <h3><i class="fas fa-file-image"></i> Bukti Pembayaran</h3>
                <button onclick="closeModal()" class="proof-modal-close">&times;</button>
            </div>
            <div class="proof-modal-body">
                <img id="proofImage" src="" alt="Bukti Pembayaran">
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* ===== ORDERS ADMIN STYLES ===== */

            .orders-admin {
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

            .orders-header {
                margin-bottom: 24px;
            }

            .orders-header-left {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 16px;
            }

            .section-title {
                font-size: 20px;
                font-weight: 800;
                color: var(--text-primary);
            }

            .order-count {
                font-size: 12px;
                font-weight: 600;
                color: var(--text-muted);
                background: var(--bg);
                padding: 4px 12px;
                border-radius: 20px;
            }

            /* Filter Tabs */
            .filter-tabs {
                display: flex;
                gap: 4px;
                overflow-x: auto;
                padding-bottom: 4px;
            }

            .filter-tab {
                padding: 8px 16px;
                font-size: 13px;
                font-weight: 600;
                color: var(--text-secondary);
                text-decoration: none;
                border-radius: var(--radius-sm);
                white-space: nowrap;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .filter-tab:hover {
                background: var(--surface);
                color: var(--text-primary);
            }

            .filter-tab.active {
                background: var(--primary);
                color: #fff;
            }

            .tab-badge {
                background: #fff;
                color: var(--primary);
                font-size: 10px;
                font-weight: 800;
                padding: 1px 6px;
                border-radius: 8px;
                min-width: 18px;
                text-align: center;
            }

            .filter-tab.active .tab-badge {
                background: rgba(255, 255, 255, 0.3);
                color: #fff;
            }

            /* Table Card */
            .orders-table-card {
                background: var(--surface);
                border-radius: var(--radius-xl);
                border: 1px solid var(--border-light);
                overflow: hidden;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .orders-table {
                width: 100%;
                border-collapse: collapse;
                min-width: 900px;
            }

            .orders-table th {
                text-align: left;
                padding: 14px 20px;
                font-size: 11px;
                font-weight: 700;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border-bottom: 1px solid var(--border);
                background: #fafbfc;
            }

            .orders-table td {
                padding: 16px 20px;
                font-size: 13.5px;
                border-bottom: 1px solid var(--border-light);
                vertical-align: middle;
            }

            .orders-table tbody tr {
                transition: background 0.15s;
            }

            .orders-table tbody tr:hover td {
                background: var(--surface-hover);
            }

            .orders-table tbody tr:last-child td {
                border-bottom: none;
            }

            /* Cells */
            .order-id {
                display: block;
                font-weight: 700;
                color: var(--primary);
                font-family: 'SF Mono', 'Fira Code', monospace;
                font-size: 13px;
            }

            .order-date {
                display: block;
                font-size: 11px;
                color: var(--text-muted);
                margin-top: 2px;
            }

            .customer-info {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .customer-avatar {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                flex-shrink: 0;
            }

            .customer-name {
                display: block;
                font-weight: 600;
                color: var(--text-primary);
                font-size: 13px;
            }

            .customer-email {
                display: block;
                font-size: 11px;
                color: var(--text-muted);
            }

            .payment-method {
                font-size: 12px;
                font-weight: 700;
                color: var(--text-secondary);
                text-transform: uppercase;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .order-amount {
                font-weight: 800;
                color: var(--text-primary);
            }

            /* Proof Button */
            .btn-proof {
                background: rgba(59, 130, 246, 0.08);
                color: #3b82f6;
                border: none;
                padding: 6px 12px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.2s;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                font-family: inherit;
            }

            .btn-proof:hover {
                background: #3b82f6;
                color: #fff;
            }

            .no-proof {
                font-size: 11px;
                color: var(--text-muted);
                font-weight: 500;
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
                white-space: nowrap;
            }

            .status-pill.warning {
                background: #fef3c7;
                color: #92400e;
            }

            .status-pill.verify {
                background: #fce7f3;
                color: #9d174d;
            }

            .status-pill.info {
                background: #dbeafe;
                color: #1d4ed8;
            }

            .status-pill.shipped {
                background: #d1fae5;
                color: #065f46;
            }

            .status-pill.success {
                background: #dcfce7;
                color: #15803d;
            }

            .status-pill.danger {
                background: #fee2e2;
                color: #b91c1c;
            }

            /* Action Buttons */
            .action-buttons {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }

            .inline-form {
                display: inline;
            }

            .btn-icon {
                width: 34px;
                height: 34px;
                border-radius: 8px;
                border: 1px solid var(--border);
                background: var(--surface);
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 13px;
                transition: all 0.2s;
                text-decoration: none;
            }

            .btn-view {
                color: #3b82f6;
            }

            .btn-view:hover {
                background: #3b82f6;
                color: #fff;
                border-color: #3b82f6;
            }

            .btn-confirm {
                color: #10b981;
                border-color: #a7f3d0;
            }

            .btn-confirm:hover {
                background: #10b981;
                color: #fff;
                border-color: #10b981;
            }

            .btn-reject {
                color: #ef4444;
                border-color: #fecaca;
            }

            .btn-reject:hover {
                background: #ef4444;
                color: #fff;
                border-color: #ef4444;
            }

            .btn-status {
                color: var(--text-secondary);
            }

            .btn-status:hover {
                background: var(--bg);
                color: var(--text-primary);
            }

            /* Status Dropdown */
            .status-dropdown-wrap {
                position: relative;
                display: inline-block;
            }

            .status-dropdown {
                display: none;
                position: absolute;
                right: 0;
                top: 100%;
                margin-top: 4px;
                background: var(--surface);
                border: 1px solid var(--border);
                border-radius: var(--radius-sm);
                box-shadow: var(--shadow-lg);
                z-index: 100;
                min-width: 140px;
                overflow: hidden;
            }

            .status-dropdown.show {
                display: block;
            }

            .dropdown-item {
                display: block;
                width: 100%;
                padding: 8px 14px;
                font-size: 12px;
                font-weight: 600;
                color: var(--text-secondary);
                background: none;
                border: none;
                cursor: pointer;
                text-align: left;
                font-family: inherit;
                transition: all 0.15s;
            }

            .dropdown-item:hover {
                background: var(--surface-hover);
                color: var(--text-primary);
            }

            /* Empty State */
            .empty-orders {
                text-align: center;
                padding: 60px 20px;
                color: var(--text-muted);
            }

            .empty-orders i {
                font-size: 40px;
                margin-bottom: 12px;
                display: block;
                opacity: 0.3;
            }

            .empty-orders p {
                font-size: 14px;
                font-weight: 500;
            }

            /* Proof Modal */
            .proof-modal {
                display: none;
                position: fixed;
                inset: 0;
                z-index: 9999;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .proof-modal.show {
                display: flex;
            }

            .proof-modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(4px);
            }

            .proof-modal-content {
                position: relative;
                background: var(--surface);
                border-radius: var(--radius-xl);
                max-width: 600px;
                width: 100%;
                max-height: 90vh;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                animation: modalIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            @keyframes modalIn {
                from {
                    opacity: 0;
                    transform: scale(0.95) translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }

            .proof-modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px 24px;
                border-bottom: 1px solid var(--border-light);
            }

            .proof-modal-header h3 {
                font-size: 16px;
                font-weight: 700;
                color: var(--text-primary);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .proof-modal-header h3 i {
                color: #3b82f6;
            }

            .proof-modal-close {
                background: none;
                border: none;
                font-size: 24px;
                color: var(--text-muted);
                cursor: pointer;
                width: 36px;
                height: 36px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
            }

            .proof-modal-close:hover {
                background: var(--bg);
                color: var(--text-primary);
            }

            .proof-modal-body {
                padding: 24px;
                text-align: center;
                max-height: 70vh;
                overflow-y: auto;
            }

            .proof-modal-body img {
                max-width: 100%;
                border-radius: var(--radius-md);
                box-shadow: var(--shadow-md);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .filter-tabs {
                    flex-wrap: nowrap;
                }

                .orders-table th,
                .orders-table td {
                    padding: 12px 14px;
                }
            }
        </style>
    @endpush

    <script>
        function showProof(url) {
            const modal = document.getElementById('proofModal');
            const img = document.getElementById('proofImage');
            img.src = url;
            modal.classList.add('show');
        }

        function closeModal() {
            document.getElementById('proofModal').classList.remove('show');
        }

        function toggleDropdown(btn) {
            // Close all other dropdowns first
            document.querySelectorAll('.status-dropdown.show').forEach(d => d.classList.remove('show'));
            const dropdown = btn.parentElement.querySelector('.status-dropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdowns on click outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.status-dropdown-wrap')) {
                document.querySelectorAll('.status-dropdown.show').forEach(d => d.classList.remove('show'));
            }
            if (e.target === document.getElementById('proofModal')) {
                closeModal();
            }
        });
    </script>
@endsection