@extends('layouts.app')

@php
    $isConfirmed = !in_array($order->status, ['pending', 'verifying', 'cancelled']);
@endphp

@section('title', ($isConfirmed ? 'Lacak' : 'Detail') . ' Pesanan #' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' - Belanja.ID')

@push('styles')
<style>
    :root {
        --primary: #e74c3c;
        --primary-soft: rgba(231, 76, 60, 0.1);
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --bg-color: #f1f5f9;
        --surface: #ffffff;
        --border-light: #e2e8f0;
        --radius-lg: 20px;
        --radius-md: 14px;
        --shadow-soft: 0 4px 25px rgba(0,0,0,0.03);
    }

    body { background-color: var(--bg-color); font-family: 'Inter', sans-serif; }

    .tracking-page { padding: 40px 0 100px; }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 24px;
        transition: color 0.2s;
    }
    .back-btn:hover { color: var(--primary); }

    .tracking-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
        margin-bottom: 24px;
    }

    /* Header */
    .tracking-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #fff;
        padding: 32px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }
    .header-left { position: relative; z-index: 2; }
    .header-left h1 { font-size: 24px; font-weight: 800; margin: 0 0 8px; letter-spacing: -0.5px; }
    .header-left p { margin: 0; color: #94a3b8; font-size: 14px; display: flex; align-items: center; gap: 6px; }
    
    .header-right { text-align: right; position: relative; z-index: 2; }
    .header-right p { margin: 0; color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .header-right .eta { font-size: 22px; font-weight: 800; color: #fff; margin-top: 4px; }

    /* Detail Grid */
    .show-grid { display: grid; grid-template-columns: 1fr 350px; gap: 24px; }
    @media (max-width: 1024px) { .show-grid { grid-template-columns: 1fr; } }

    /* Content Cards */
    .content-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 32px;
        border: 1px solid var(--border-light);
        margin-bottom: 24px;
        box-shadow: var(--shadow-soft);
    }
    .card-title {
        font-size: 17px; font-weight: 800; color: var(--text-dark); margin-bottom: 20px;
        display: flex; align-items: center; gap: 10px;
    }
    .card-title i { color: var(--primary); }

    /* Items List */
    .items-list { display: flex; flex-direction: column; gap: 16px; }
    .item-row {
        display: flex; align-items: center; gap: 16px; padding: 12px;
        background: #f8fafc; border-radius: 12px; border: 1px solid var(--border-light);
    }
    .item-img { width: 56px; height: 56px; border-radius: 8px; object-fit: contain; background: #fff; border: 1px solid #eee; padding: 4px; }
    .item-info { flex: 1; }
    .item-name { display: block; font-size: 14px; font-weight: 700; color: var(--text-dark); }
    .item-qty { font-size: 12px; color: var(--text-muted); font-weight: 600; }
    .item-price { font-weight: 800; color: var(--text-dark); font-size: 14px; }

    /* Address & Summary */
    .info-group { margin-bottom: 20px; }
    .info-label { font-size: 11px; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block; }
    .info-text { font-size: 14px; font-weight: 600; color: var(--text-dark); line-height: 1.6; }

    .total-row {
        display: flex; justify-content: space-between; align-items: center;
        padding-top: 16px; border-top: 2px solid #f1f5f9; margin-top: 16px;
    }
    .total-label { font-weight: 700; color: var(--text-muted); }
    .total-value { font-size: 20px; font-weight: 900; color: var(--primary); }

    /* Timeline */
    .tracking-timeline { position: relative; padding-left: 20px; margin-left: 10px; }
    .tracking-timeline::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: #e2e8f0; }
    .timeline-item { position: relative; padding-left: 30px; margin-bottom: 32px; }
    .timeline-item::before {
        content: ''; position: absolute; left: -6px; top: 2px; width: 14px; height: 14px;
        background: #cbd5e1; border: 3px solid #fff; border-radius: 50%; z-index: 1;
    }
    .timeline-item.active::before { background: var(--primary); box-shadow: 0 0 0 4px var(--primary-soft); }
    .timeline-time { font-size: 11px; color: var(--primary); font-weight: 800; margin-bottom: 4px; }
    .timeline-status { font-size: 14px; font-weight: 700; color: var(--text-dark); }

    .courier-card {
        background: #f8fafc; border: 1px solid var(--border-light); padding: 20px; border-radius: 16px;
        display: flex; align-items: center; gap: 16px; margin-bottom: 24px;
    }
    .courier-icon { width: 48px; height: 48px; background: var(--primary-soft); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .courier-text h4 { margin: 0; font-size: 14px; font-weight: 700; }
    .courier-text p { margin: 0; font-size: 12px; color: var(--text-muted); }

</style>
@endpush

@section('content')
<div class="tracking-page">
    <div class="container">
        <a href="{{ route('orders.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali ke Pesanan Saya
        </a>

        {{-- Main Header Card --}}
        <div class="tracking-card">
            <div class="tracking-header">
                <div class="header-left">
                    <h1>{{ $isConfirmed ? 'Lacak' : 'Detail' }} Pesanan #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
                    <p>
                        <i class="fas fa-barcode"></i> Resi: 
                        <strong>{{ $isConfirmed ? ($order->tracking_number ?? 'Sedang disiapkan') : 'Menunggu Konfirmasi' }}</strong>
                    </p>
                </div>
                @if($isConfirmed && $order->status != 'success')
                <div class="header-right">
                    <p>Estimasi Tiba</p>
                    <div class="eta">{{ $order->estimated_arrival ?? 'Sedang Proses' }}</div>
                </div>
                @endif
                @if($order->status == 'success')
                <div class="header-right">
                    <div class="status-badge" style="background: rgba(34, 197, 94, 0.2); color: #fff; padding: 8px 16px; border-radius: 12px; font-weight: 800;">
                        <i class="fas fa-check-double"></i> TERIMA KASIH
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="show-grid">
            <div class="show-main">
                {{-- Conditional Tracking Section --}}
                @if($isConfirmed && $order->status != 'success')
                <div class="content-card animate-in">
                    <h3 class="card-title"><i class="fas fa-shipping-fast"></i> Status Pengiriman</h3>
                    
                    @if($order->status == 'shipped')
                    <div class="courier-card">
                        <div class="courier-icon"><i class="fas fa-truck"></i></div>
                        <div class="courier-text">
                            <h4>{{ $order->courier_name ?? 'Kurir Mitra' }}</h4>
                            <p>Paket Anda sedang dalam perjalanan ke alamat tujuan.</p>
                        </div>
                    </div>
                    @endif

                    <div class="tracking-timeline">
                        @php
                            $history = json_decode($order->shipping_history, true) ?: [];
                        @endphp
                        @foreach(array_reverse($history) as $index => $item)
                            <div class="timeline-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="timeline-time">{{ $item['time'] }}</div>
                                <div class="timeline-status">{{ $item['status'] }}</div>
                                <div class="timeline-location" style="font-size: 12px; color: var(--text-muted);">
                                    <i class="fas fa-map-marker-alt"></i> {{ $item['location'] }}
                                </div>
                            </div>
                        @endforeach
                        @if(empty($history))
                            <div class="timeline-item active">
                                <div class="timeline-time">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                <div class="timeline-status">Mulai Diproses</div>
                                <div class="timeline-location" style="font-size: 12px; color: var(--text-muted);">Gudang Belanja.ID</div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Product List (Always Visible) --}}
                <div class="content-card">
                    <h3 class="card-title"><i class="fas fa-shopping-bag"></i> Rincian Produk</h3>
                    <div class="items-list">
                        @foreach($order->items as $item)
                        <div class="item-row">
                            <img src="{{ Str::startsWith($item->product_image, 'http') ? $item->product_image : asset('storage/' . $item->product_image) }}" class="item-img" alt="">
                            <div class="item-info">
                                <span class="item-name">{{ $item->product_name }}</span>
                                <span class="item-qty">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="item-price">Rp {{ number_format($item->total_price, 0, ',', '.') }}</div>
                        </div>
                        @endforeach
                    </div>
                    <div class="total-row">
                        <span class="total-label">Total Pembayaran</span>
                        <span class="total-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="show-sidebar">
                {{-- Status Card --}}
                <div class="content-card" style="padding: 24px;">
                    <div class="info-group">
                        <span class="info-label">Status Pesanan</span>
                        @php
                            $statusMap = [
                                'pending' => ['label' => 'Menunggu Pembayaran', 'color' => '#f59e0b'],
                                'verifying' => ['label' => 'Verifikasi', 'color' => '#ec4899'],
                                'processing' => ['label' => 'Diproses', 'color' => '#3b82f6'],
                                'shipped' => ['label' => 'Dikirim', 'color' => '#10b981'],
                                'success' => ['label' => 'Selesai', 'color' => '#22c55e'],
                                'cancelled' => ['label' => 'Dibatalkan', 'color' => '#ef4444'],
                            ];
                            $s = $statusMap[$order->status] ?? ['label' => $order->status, 'color' => '#64748b'];
                        @endphp
                        <p class="info-text" style="color: {{ $s['color'] }}; font-weight: 800;">{{ $s['label'] }}</p>
                    </div>
                    <div class="info-group">
                        <span class="info-label">Metode Pembayaran</span>
                        <p class="info-text" style="text-transform: uppercase;">{{ $order->payment_method }}</p>
                        @if($order->payment_proof)
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" style="font-size: 11px; font-weight: 700; color: #3b82f6; text-decoration: none;">
                            <i class="fas fa-file-image"></i> Lihat Bukti Bayar
                        </a>
                        @endif
                    </div>
                    <div class="info-group">
                        <span class="info-label">Tanggal Transaksi</span>
                        <p class="info-text">{{ $order->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Shipping Info Card --}}
                <div class="content-card" style="padding: 24px;">
                    <h3 class="card-title" style="font-size: 15px;"><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</h3>
                    <div class="info-text" style="font-size: 13px;">
                        <strong>{{ $order->full_name }}</strong><br>
                        {{ $order->phone }}<br>
                        {{ $order->address }}<br>
                        {{ $order->district }}, {{ $order->city }}<br>
                        {{ $order->province }}, {{ $order->postal_code }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
