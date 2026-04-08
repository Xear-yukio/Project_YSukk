@extends('layouts.app')

@section('title', 'Checkout - Belanja.ID')

@push('styles')
    <style>
        :root {
            --primary: #e74c3c;
            --primary-hover: #c0392b;
            --primary-soft: rgba(231, 76, 60, 0.08);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-color: #f1f5f9;
            --surface: #ffffff;
            --border-light: #e2e8f0;
            --radius-xl: 20px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 20px -5px rgba(0,0,0,0.08);
        }

        .checkout-page {
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

        .checkout-header {
            margin-bottom: 32px;
        }
        .checkout-header h1 {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 440px;
            gap: 32px;
            align-items: start;
        }

        /* Forms Section */
        .billing-section {
            background: var(--surface);
            padding: 40px;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
        }

        .form-group-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 20px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border-light);
        }
        .form-group-title i { 
            color: var(--primary); 
            background: var(--primary-soft);
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 32px;
        }
        .full-width { grid-column: span 2; }

        .form-group {
            position: relative;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .form-group label span { color: var(--primary); }
        
        .form-control {
            width: 100%;
            height: 48px;
            background: #f8fafc;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 0 16px;
            font-size: 14.5px;
            color: var(--text-main);
            transition: all 0.2s;
            outline: none;
            font-family: inherit;
        }
        .form-control:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }
        .form-control::placeholder { color: #94a3b8; }
        
        textarea.form-control {
            height: auto;
            padding: 16px;
            resize: vertical;
            min-height: 100px;
        }

        /* Order Summary Section */
        .order-summary-wrapper {
            position: sticky;
            top: 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        
        .order-summary-box, .payment-methods-box {
            background: var(--surface);
            padding: 32px;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
        }

        .summary-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 24px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-items {
            margin-bottom: 24px;
            max-height: 280px;
            overflow-y: auto;
            padding-right: 8px;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .summary-items::-webkit-scrollbar { width: 6px; }
        .summary-items::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 16px;
            margin-bottom: 16px;
            border-bottom: 1px dashed var(--border-light);
        }
        .summary-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        
        .item-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .item-img {
            width: 54px;
            height: 54px;
            object-fit: contain;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            padding: 4px;
        }
        .item-details { display: flex; flex-direction: column; gap: 4px; }
        .item-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.3;
        }
        .item-qty { font-size: 12px; color: var(--text-muted); font-weight: 500; }
        
        .item-price { font-weight: 700; font-size: 14px; color: var(--text-main); }

        .summary-totals {
            background: #f8fafc;
            border-radius: var(--radius-md);
            padding: 20px;
            margin-bottom: 24px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-muted);
        }
        .total-row:last-child { margin-bottom: 0; }
        .total-row.grand-total {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px dashed #cbd5e1;
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
        }
        .grand-total span:last-child { color: var(--primary); }

        /* Payment Methods Grid */
        .payment-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .payment-group-header {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
            margin: 16px 0 4px;
        }
        .payment-group-header:first-child { margin-top: 0; }

        .payment-card {
            background: #fff;
            border: 1.5px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 16px;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
        }
        .payment-card:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .payment-card.active {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 4px 15px var(--primary-soft);
        }
        
        /* Custom styled radio button */
        .payment-card input[type="radio"] { display: none; }
        .radio-custom {
            width: 20px; height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .payment-card.active .radio-custom { border-color: var(--primary); }
        .radio-custom::after {
            content: ''; width: 10px; height: 10px;
            background: var(--primary); border-radius: 50%;
            transform: scale(0); transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .payment-card.active .radio-custom::after { transform: scale(1); }

        /* Brand Colors for Icons */
        .brand-icon { font-size: 24px; width: 40px; text-align: center; flex-shrink: 0; }
        .payment-card[data-brand="qris"] .brand-icon { color: #ed1a24; }
        .payment-card[data-brand="bca"] .brand-icon, .payment-card[data-brand="mandiri"] .brand-icon { color: #003d79; }
        .payment-card[data-brand="bri"] .brand-icon { color: #00529c; }
        .payment-card[data-brand="bni"] .brand-icon { color: #f26522; }
        .payment-card[data-brand="gopay"] .brand-icon { color: #00aed6; }
        .payment-card[data-brand="dana"] .brand-icon { color: #108ee9; }
        .payment-card[data-brand="seabank"] .brand-icon { color: #ee4d2d; }
        .payment-card[data-brand="cod"] .brand-icon { color: #64748b; }

        .payment-info { flex: 1; }
        .payment-info .name { font-size: 14px; font-weight: 700; color: var(--text-main); display: block; margin-bottom: 2px; }
        .payment-info .desc { font-size: 12px; font-weight: 500; color: var(--text-muted); }

        .btn-place-order {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px var(--primary-soft);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .btn-place-order:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
        }
        .btn-place-order i { font-size: 18px; }

        /* Save info banner */
        .save-info-banner {
            display: flex; align-items: flex-start; gap: 12px;
            background: #fffcf0; border: 1px solid #fef08a; padding: 16px;
            border-radius: var(--radius-md); margin-top: 16px;
        }
        .save-info-banner input { width: 18px; height: 18px; margin-top: 2px; accent-color: #ca8a04; cursor: pointer;}
        .save-info-banner label { margin: 0; color: #a16207; font-size: 13px; font-weight: 500; line-height: 1.5; cursor: pointer;}

        /* Alert */
        .error-alert {
            background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
            padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 24px;
            font-size: 14px; font-weight: 500;
        }
        .error-alert ul { margin: 0; padding-left: 20px; }
        
        @media (max-width: 992px) {
            .checkout-content { grid-template-columns: 1fr; }
            .order-summary-wrapper { position: static; }
        }
        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; gap: 16px; }
            .full-width { grid-column: span 1; }
            .billing-section, .order-summary-box, .payment-methods-box { padding: 24px; }
        }
        .profile-notice {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 16px 20px;
            border-radius: var(--radius-lg);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            animation: fadeIn 0.5s ease-out;
        }
        .profile-notice i {
            color: #3b82f6;
            font-size: 20px;
        }
        .profile-notice-content {
            flex: 1;
        }
        .profile-notice-content p {
            margin: 0;
            font-size: 14px;
            color: #1e40af;
            font-weight: 600;
        }
        .profile-notice-content a {
            color: #2563eb;
            text-decoration: underline;
            margin-left: 4px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush

@section('content')
<div class="checkout-page">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="/"><i class="fas fa-home"></i> Beranda</a>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <a href="{{ route('cart.index') }}">Keranjang Belanja</a>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span class="active">Checkout</span>
        </div>

        <div class="checkout-header">
            <h1>Selesaikan Pesanan Anda</h1>
        </div>

        @if ($errors->any())
            <div class="error-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            <div class="checkout-content">
                <!-- Left Column: Forms -->
                <div class="billing-section">
                    @if(!$isProfileComplete)
                    <div class="profile-notice">
                        <i class="fas fa-info-circle"></i>
                        <div class="profile-notice-content">
                            <p>💡 Tips: Lengkapi profil Anda agar belanja berikutnya jauh lebih cepat! <a href="{{ route('profile.index') }}">Lengkapi Sekarang</a></p>
                        </div>
                    </div>
                    @endif

                    <div class="form-group-title">
                        <i class="fas fa-user"></i> Data Diri Penerima
                    </div>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Nama Lengkap <span>*</span></label>
                            <input type="text" class="form-control" name="full_name" placeholder="Masukkan nama penerima paket" value="{{ old('full_name', auth()->user()->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor WhatsApp / HP <span>*</span></label>
                            <input type="tel" class="form-control" name="phone" placeholder="Contoh: 08123456789" value="{{ old('phone', auth()->user()->phone) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat Email <span>*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="email@contoh.com" value="{{ old('email', auth()->user()->email) }}" required>
                        </div>
                    </div>

                    <div class="form-group-title" style="margin-top: 40px;">
                        <i class="fas fa-map-marker-alt"></i> Detail Alamat Pengiriman
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Provinsi <span>*</span></label>
                            <input type="text" class="form-control" name="province" placeholder="Contoh: Jawa Barat" value="{{ old('province', auth()->user()->province) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Kota / Kabupaten <span>*</span></label>
                            <input type="text" class="form-control" name="city" placeholder="Contoh: Bandung" value="{{ old('city', auth()->user()->city) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Kecamatan <span>*</span></label>
                            <input type="text" class="form-control" name="district" placeholder="Contoh: Lengkong" value="{{ old('district', auth()->user()->district) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Kode Pos <span>*</span></label>
                            <input type="text" class="form-control" name="postal_code" placeholder="Contoh: 40263" value="{{ old('postal_code', auth()->user()->postal_code) }}" required>
                        </div>
                        <div class="form-group full-width">
                            <label>Alamat Lengkap <span>*</span></label>
                            <textarea class="form-control" name="address" placeholder="Tuliskan nama jalan, nomor rumah, blok, RT/RW, atau patokan rumah Anda..." required>{{ old('address', auth()->user()->address) }}</textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Catatan Kurir (Opsional)</label>
                            <input type="text" class="form-control" name="notes" placeholder="Contoh: Titipkan di pos satpam / letakkan di teras" value="{{ old('notes') }}">
                        </div>
                    </div>

                    <div class="save-info-banner">
                        <input type="checkbox" id="save-info">
                        <label for="save-info">Simpan informasi ini untuk berbelanja lebih mudah dan cepat di masa mendatang. Data Anda akan terenkripsi dengan aman sistem kami.</label>
                    </div>
                </div>

                <!-- Right Column: Summary & Payment -->
                <div class="order-summary-wrapper">
                    <!-- Summary Box -->
                    <div class="order-summary-box">
                        <div class="summary-title"><i class="fas fa-receipt" style="color:var(--primary);"></i> Ringkasan Pesanan</div>
                        
                        <div class="summary-items">
                            @foreach($cart as $id => $details)
                                <div class="summary-item">
                                    <div class="item-info">
                                        <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="item-img">
                                        <div class="item-details">
                                            <span class="item-name">{{ $details['name'] }}</span>
                                            <span class="item-qty">Jumlah: {{ $details['quantity'] }}</span>
                                        </div>
                                    </div>
                                    <span class="item-price">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="summary-totals">
                            <div class="total-row">
                                <span>Subtotal Belanja</span>
                                <span>Rp {{ $formattedTotal }}</span>
                            </div>
                            <div class="total-row">
                                <span>Biaya Pengiriman</span>
                                <span style="color: #10b981; font-weight: 700;">Gratis</span>
                            </div>
                            <div class="total-row">
                                <span>Biaya Layanan</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="total-row grand-total">
                                <span>Total Pembayaran</span>
                                <span>Rp {{ $formattedTotal }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Box -->
                    <div class="payment-methods-box">
                        <div class="summary-title"><i class="fas fa-shield-alt" style="color:var(--primary);"></i> Metode Pembayaran</div>
                        
                        <div class="payment-grid">
                            <div class="payment-group-header">Pembayaran Instan</div>
                            <label class="payment-card active" data-brand="qris">
                                <input type="radio" name="payment" value="qris" checked>
                                <div class="radio-custom"></div>
                                <i class="fas fa-qrcode brand-icon"></i>
                                <div class="payment-info">
                                    <span class="name">QRIS Tepat</span>
                                    <span class="desc">Bisa via GoPay, Dana, ShopeePay</span>
                                </div>
                            </label>

                            <div class="payment-group-header">Transfer Virtual Account</div>
                            <label class="payment-card" data-brand="bca">
                                <input type="radio" name="payment" value="bca">
                                <div class="radio-custom"></div>
                                <i class="fas fa-university brand-icon"></i>
                                <div class="payment-info">
                                    <span class="name">BCA Virtual Account</span>
                                    <span class="desc">Otomatis Terverifikasi</span>
                                </div>
                            </label>
                            <label class="payment-card" data-brand="bri">
                                <input type="radio" name="payment" value="bri">
                                <div class="radio-custom"></div>
                                <i class="fas fa-university brand-icon"></i>
                                <div class="payment-info">
                                    <span class="name">BRI Virtual Account</span>
                                    <span class="desc">Otomatis Terverifikasi</span>
                                </div>
                            </label>
                            <label class="payment-card" data-brand="bni">
                                <input type="radio" name="payment" value="bni">
                                <div class="radio-custom"></div>
                                <i class="fas fa-university brand-icon"></i>
                                <div class="payment-info">
                                    <span class="name">BNI Virtual Account</span>
                                    <span class="desc">Otomatis Terverifikasi</span>
                                </div>
                            </label>
                            <label class="payment-card" data-brand="mandiri">
                                <input type="radio" name="payment" value="mandiri">
                                <div class="radio-custom"></div>
                                <i class="fas fa-university brand-icon"></i>
                                <div class="payment-info">
                                    <span class="name">Mandiri Virtual Account</span>
                                    <span class="desc">Otomatis Terverifikasi</span>
                                </div>
                            </label>

                            <div class="payment-group-header">Dompet Digital (E-Wallet)</div>
                            <label class="payment-card" data-brand="gopay">
                                <input type="radio" name="payment" value="gopay">
                                <div class="radio-custom"></div>
                                <i class="fas fa-wallet brand-icon"></i>
                                <div class="payment-info">
                                    <span class="name">GoPay / GoPayLater</span>
                                    <span class="desc">Aplikasi Gojek / Tokopedia</span>
                                </div>
                            </label>
                            <label class="payment-card" data-brand="dana">
                                <input type="radio" name="payment" value="dana">
                                <div class="radio-custom"></div>
                                <i class="fas fa-wallet brand-icon"></i>
                                <div class="payment-info">
                                    <span class="name">DANA</span>
                                    <span class="desc">Pembayaran praktis dengan saldo DANA</span>
                                </div>
                            </label>
                            <label class="payment-card" data-brand="seabank">
                                <input type="radio" name="payment" value="seabank">
                                <div class="radio-custom"></div>
                                <i class="fas fa-piggy-bank brand-icon"></i>
                                <div class="payment-info">
                                    <span class="name">SeaBank</span>
                                    <span class="desc">Transfer instan dari aplikasi SeaBank</span>
                                </div>
                            </label>
                            
                            <div class="payment-group-header">Pembayaran Lainnya</div>
                            <label class="payment-card" data-brand="cod">
                                <input type="radio" name="payment" value="cod">
                                <div class="radio-custom"></div>
                                <i class="fas fa-box-open brand-icon" style="color: #64748b;"></i>
                                <div class="payment-info">
                                    <span class="name">Bayar di Tempat (COD)</span>
                                    <span class="desc">Sediakan uang pas & bayar ke kurir</span>
                                </div>
                            </label>
                        </div>
                        
                        <div style="margin-top: 32px;">
                            <button type="submit" class="btn-place-order">
                                Bayar Pesanan <i class="fas fa-lock"></i>
                            </button>
                            <p style="text-align: center; font-size: 12px; color: var(--text-muted); margin-top: 12px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                <i class="fas fa-shield-alt"></i> Pembayaran Anda dijamin aman
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Handle payment method selection styling
    document.querySelectorAll('.payment-card').forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all
            document.querySelectorAll('.payment-card').forEach(c => c.classList.remove('active'));
            // Add active class to clicked
            this.classList.add('active');
            // Check the hidden radio input
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
</script>
@endpush
@endsection
