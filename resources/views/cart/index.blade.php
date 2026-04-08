@extends('layouts.app')

@section('title', 'Keranjang Belanja - Belanja.ID')

@push('styles')
<style>
    :root {
        --primary: #e74c3c;
        --primary-hover: #c0392b;
        --primary-soft: rgba(231, 76, 60, 0.08);
        --text-main: #0f172a;
        --text-muted: #64748b;
        --bg-color: #f8fafc;
        --surface: #ffffff;
        --border-light: #e2e8f0;
        --radius-lg: 16px;
        --radius-md: 12px;
        --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.05);
        --shadow-md: 0 10px 20px -5px rgba(0,0,0,0.08);
    }

    .cart-page {
        padding: 40px 0 100px;
        background: var(--bg-color);
        font-family: 'Inter', sans-serif;
    }
    
    .breadcrumb {
        margin-bottom: 24px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .breadcrumb a { color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: var(--primary); }
    .breadcrumb .active { color: var(--primary); }

    .cart-header { margin-bottom: 32px; }
    .cart-header h1 { font-size: 32px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px; }

    .cart-container {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 32px;
        align-items: start;
    }

    /* Cart Items Section */
    .cart-items-wrapper {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-light);
        padding: 32px;
    }

    .cart-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    .cart-table th {
        text-align: left; padding: 0 0 16px 0;
        font-weight: 700; font-size: 13px; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border-light);
    }
    .cart-table td {
        padding: 24px 0; vertical-align: middle;
        border-bottom: 1px dashed var(--border-light);
    }
    .cart-table tr:last-child td { border-bottom: none; padding-bottom: 0; }
    
    .product-cell { display: flex; align-items: center; gap: 20px; }
    
    .remove-action {
        color: #ef4444; background: #fef2f2;
        width: 28px; height: 28px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s;
    }
    .remove-action:hover { background: #fee2e2; transform: scale(1.1); }

    .product-img {
        width: 72px; height: 72px; object-fit: contain;
        background: #f8fafc; border: 1px solid #f1f5f9;
        border-radius: 10px; padding: 6px;
    }
    .product-name { font-weight: 700; font-size: 15px; color: var(--text-main); line-height: 1.4; display: block; }
    .product-meta { font-size: 12px; color: var(--text-muted); margin-top: 4px; display: block;}

    .price-text { font-weight: 600; font-size: 14px; color: var(--text-main); }
    .subtotal-text { font-weight: 800; font-size: 15px; color: var(--primary); }

    /* Modern Qty Control */
    .qty-control {
        display: inline-flex; align-items: center;
        background: #f8fafc; border: 1px solid var(--border-light);
        border-radius: 8px; overflow: hidden;
    }
    .qty-btn {
        width: 32px; height: 32px;
        background: transparent; border: none;
        color: var(--text-main); font-size: 14px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.2s;
    }
    .qty-btn:hover { background: #e2e8f0; }
    .qty-input-cart {
        width: 40px; height: 32px; border: none;
        background: transparent; text-align: center;
        font-weight: 700; font-size: 14px; color: var(--text-main);
        outline: none; -moz-appearance: textfield;
    }
    .qty-input-cart::-webkit-outer-spin-button,
    .qty-input-cart::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .cart-actions-bar {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-light);
    }
    .btn-outline {
        padding: 12px 24px; background: transparent;
        border: 1.5px solid var(--border-light); border-radius: var(--radius-md);
        font-size: 14px; font-weight: 600; color: var(--text-main);
        text-decoration: none; cursor: pointer; transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }

    /* Summary Section */
    .summary-wrapper {
        display: flex; flex-direction: column; gap: 24px;
        position: sticky; top: 24px;
    }

    .coupon-box {
        background: var(--surface); padding: 24px;
        border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-light);
    }
    .coupon-box h4 { font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;}
    .coupon-input-group { display: flex; gap: 8px; }
    .coupon-input {
        flex: 1; height: 44px; border: 1px solid var(--border-light);
        border-radius: 8px; padding: 0 16px; font-size: 13px; outline: none;
        background: #f8fafc; transition: 0.2s;
    }
    .coupon-input:focus { background: #fff; border-color: var(--primary); }
    .btn-apply {
        background: var(--text-main); color: #fff;
        border: none; border-radius: 8px; padding: 0 20px;
        font-weight: 600; font-size: 13px; cursor: pointer; transition: 0.2s;
    }
    .btn-apply:hover { background: #334155; }

    .cart-total-box {
        background: var(--surface); padding: 32px;
        border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-light);
    }
    .cart-total-box h4 { font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 24px; }
    .total-row {
        display: flex; justify-content: space-between;
        margin-bottom: 16px; font-size: 14px; font-weight: 500; color: var(--text-muted);
    }
    .total-row.grand-total {
        margin-top: 20px; padding-top: 20px; border-top: 2px dashed #cbd5e1;
        font-size: 18px; font-weight: 800; color: var(--text-main);
    }
    .grand-total span:last-child { color: var(--primary); }

    .btn-checkout {
        width: 100%; padding: 16px; background: var(--primary); color: #fff;
        border: none; border-radius: var(--radius-md); font-size: 16px; font-weight: 700;
        cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px var(--primary-soft);
        display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 24px;
        text-decoration: none;
    }
    .btn-checkout:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3); }

    /* Empty Cart State */
    .empty-cart-state {
        text-align: center; padding: 60px 20px;
        background: var(--surface); border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm); border: 1px solid var(--border-light);
    }
    .empty-icon {
        width: 100px; height: 100px; background: #f8fafc; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;
        color: #cbd5e1; font-size: 40px;
    }
    .empty-cart-state h3 { font-size: 24px; font-weight: 800; color: var(--text-main); margin-bottom: 12px; }
    .empty-cart-state p { color: var(--text-muted); font-size: 15px; margin-bottom: 32px; }

    @media (max-width: 992px) {
        .cart-container { grid-template-columns: 1fr; }
        .summary-wrapper { position: static; }
    }
    @media (max-width: 768px) {
        .cart-table thead { display: none; }
        .cart-table tr { display: block; border-bottom: 1px solid var(--border-light); padding-bottom: 24px; margin-bottom: 24px; }
        .cart-table tr:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0;}
        .cart-table td { display: flex; justify-content: space-between; align-items: center; border: none; padding: 8px 0; }
        .cart-table td::before { content: attr(data-label); font-weight: 700; font-size: 13px; color: var(--text-muted); text-transform: uppercase; }
        .product-cell { flex-direction: row-reverse; }
        .cart-items-wrapper, .cart-total-box, .coupon-box { padding: 24px; }
        .cart-actions-bar { flex-direction: column; gap: 16px; }
        .btn-outline { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="cart-page">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="/"><i class="fas fa-home"></i> Beranda</a>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span class="active">Keranjang Belanja</span>
        </div>

        <div class="cart-header">
            <h1>Keranjang Anda</h1>
        </div>

        @if(count($cart) > 0)
            <div class="cart-container">
                <!-- Data List -->
                <div class="cart-items-wrapper">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Produk</th>
                                <th>Harga Satuan</th>
                                <th>Kuantitas</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $details)
                                @php
                                    $price = (float) str_replace('.', '', $details['price']);
                                    $subtotal = $price * $details['quantity'];
                                    $formattedSubtotal = number_format($subtotal, 0, ',', '.');
                                @endphp
                                <tr data-id="{{ $id }}">
                                    <td data-label="Produk">
                                        <div class="product-cell">
                                            <div class="remove-action" onclick="removeFromCart('{{ $id }}')" title="Hapus item">
                                                <i class="fas fa-trash-alt"></i>
                                            </div>
                                            <img src="{{ \Illuminate\Support\Str::startsWith($details['image'], 'http') ? $details['image'] : asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="product-img">
                                            <div>
                                                <span class="product-name">{{ $details['name'] }}</span>
                                                <span class="product-meta">ID: #{{ $id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Harga">
                                        <span class="price-text">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                    </td>
                                    <td data-label="Kuantitas">
                                        <div class="qty-control">
                                            <button class="qty-btn" onclick="updateQty(this, -1)"><i class="fas fa-minus"></i></button>
                                            <input type="number" class="qty-input-cart cart-qty-update" value="{{ $details['quantity'] }}" min="1">
                                            <button class="qty-btn" onclick="updateQty(this, 1)"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </td>
                                    <td data-label="Total" style="text-align: right;">
                                        <span class="subtotal-text">Rp <span class="subtotal-val">{{ $formattedSubtotal }}</span></span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="cart-actions-bar">
                        <a href="/" class="btn-outline"><i class="fas fa-arrow-left"></i> Kembali Belanja</a>
                        <button class="btn-outline" onclick="window.location.reload()"><i class="fas fa-sync-alt"></i> Perbarui Keranjang</button>
                    </div>
                </div>

                <!-- Summary Side -->
                <div class="summary-wrapper">
                    <div class="coupon-box">
                        <h4><i class="fas fa-ticket-alt" style="color:var(--primary);"></i> Punya Kode Voucher?</h4>
                        <div class="coupon-input-group">
                            <input type="text" class="coupon-input" placeholder="Masukkan kode promo">
                            <button class="btn-apply">Pakai</button>
                        </div>
                    </div>

                    <div class="cart-total-box">
                        <h4>Ringkasan Pesanan</h4>
                        <div class="total-row">
                            <span>Total Harga ({{ count($cart) }} barang)</span>
                            <span>Rp <span id="cart-subtotal">{{ $formattedTotal }}</span></span>
                        </div>
                        <div class="total-row">
                            <span>Estimasi Ongkos Kirim</span>
                            <span style="color: #10b981; font-weight: 700;">Gratis</span>
                        </div>
                        <div class="total-row grand-total">
                            <span>Total Belanja</span>
                            <span>Rp <span id="cart-total">{{ $formattedTotal }}</span></span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn-checkout">
                            Lanjut ke Pembayaran <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart-state">
                <div class="empty-icon"><i class="fas fa-shopping-cart"></i></div>
                <h3>Keranjang Anda masih Kosong</h3>
                <p>Sepertinya Anda belum menemukan barang impian. Yuk, mulai cari produk menarik!</p>
                <a href="/" class="btn-checkout" style="display: inline-flex; width: auto; padding: 14px 40px; margin: 0 auto;">Cari Barang Sekarang</a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function updateQty(element, change) {
        const row = element.closest('tr');
        const input = row.querySelector('.qty-input-cart');
        let newVal = parseInt(input.value) + change;
        if (newVal < 1) newVal = 1;
        input.value = newVal;
        
        triggerUpdate(row.dataset.id, newVal);
    }

    document.querySelectorAll('.cart-qty-update').forEach(input => {
        input.addEventListener('change', function() {
            let val = parseInt(this.value);
            if(isNaN(val) || val < 1) val = 1;
            this.value = val;
            triggerUpdate(this.closest('tr').dataset.id, val);
        });
    });

    function triggerUpdate(id, qty) {
        fetch('{{ route("cart.update") }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id, quantity: qty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload on success to reflect new totals (AJAX optional)
                window.location.reload();
            }
        });
    }

    function removeFromCart(id) {
        if(confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
            fetch('{{ route("cart.remove") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            });
        }
    }
</script>
@endpush
@endsection
