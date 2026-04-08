@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran - Belanja.ID')

@push('styles')
<style>
    :root {
        --primary: #e74c3c;
        --primary-soft: rgba(231, 76, 60, 0.1);
        --text-main: #0f172a;
        --text-sub: #64748b;
        --bg-color: #f8fafc;
        --surface: #ffffff;
        --radius-xl: 24px;
        --radius-lg: 16px;
        --shadow-soft: 0 4px 6px -1px rgba(0,0,0,0.05);
        --shadow-md: 0 20px 40px -10px rgba(0,0,0,0.08);
        --border-light: #e2e8f0;
    }

    .payment-page {
        padding: 60px 0 100px;
        background: var(--bg-color);
        font-family: 'Inter', sans-serif;
    }
    .payment-card-container {
        max-width: 650px;
        margin: 0 auto;
        background: var(--surface);
        padding: 48px;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .payment-card-container::before {
        content: ''; position: absolute;
        top: 0; left: 0; right: 0; height: 6px;
        background: linear-gradient(90deg, #3b82f6, var(--primary));
    }

    .payment-icon-header {
        width: 80px; height: 80px;
        background: var(--primary); color: #fff; font-size: 36px;
        display: flex; align-items: center; justify-content: center;
        border-radius: var(--radius-lg);
        margin: 0 auto 24px;
        box-shadow: 0 10px 20px rgba(231, 76, 60, 0.2);
    }

    /* Brand-colored headers */
    .payment-icon-header.brand-bca { background: #003d79; box-shadow: 0 10px 20px rgba(0,61,121,0.2); }
    .payment-icon-header.brand-bri { background: #00529c; box-shadow: 0 10px 20px rgba(0,82,156,0.2); }
    .payment-icon-header.brand-bni { background: #f26522; box-shadow: 0 10px 20px rgba(242,101,34,0.2); }
    .payment-icon-header.brand-mandiri { background: #003d79; box-shadow: 0 10px 20px rgba(0,61,121,0.2); }
    .payment-icon-header.brand-gopay { background: #00aed6; box-shadow: 0 10px 20px rgba(0,174,214,0.2); }
    .payment-icon-header.brand-dana { background: #108ee9; box-shadow: 0 10px 20px rgba(16,142,233,0.2); }
    .payment-icon-header.brand-seabank { background: #ee4d2d; box-shadow: 0 10px 20px rgba(238,77,45,0.2); }
    .payment-icon-header.brand-qris { background: #ed1a24; box-shadow: 0 10px 20px rgba(237,26,36,0.2); }
    .payment-icon-header.brand-cod { background: #64748b; box-shadow: 0 10px 20px rgba(100,116,139,0.2); }

    .payment-card-container h1 { font-size: 28px; font-weight: 800; margin-bottom: 8px; color: var(--text-main); }
    
    .payment-status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 16px; background: #fef3c7; color: #d97706;
        font-size: 13px; font-weight: 800; border-radius: 20px;
        margin-bottom: 20px; letter-spacing: 0.5px;
    }

    .payment-instruction-box {
        background: #f8fafc; border-radius: var(--radius-lg);
        padding: 32px; margin-bottom: 32px; text-align: left;
        border: 1px solid var(--border-light);
    }
    .instruction-title {
        font-weight: 800; font-size: 16px; color: var(--text-main);
        margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
        padding-bottom: 12px; border-bottom: 1px dashed #cbd5e1;
    }
    
    .instruction-title i.brand-bca { color: #003d79; }
    .instruction-title i.brand-bri { color: #00529c; }
    .instruction-title i.brand-bni { color: #f26522; }
    .instruction-title i.brand-mandiri { color: #003d79; }
    .instruction-title i.brand-gopay { color: #00aed6; }
    .instruction-title i.brand-dana { color: #108ee9; }
    .instruction-title i.brand-seabank { color: #ee4d2d; }
    .instruction-title i.brand-qris { color: #ed1a24; }
    .instruction-title i.brand-cod { color: #64748b; }

    .account-label { font-size: 13px; font-weight: 600; color: var(--text-sub); margin-bottom: 8px; }
    
    .account-box {
        background: #fff; border: 1.5px solid #cbd5e1;
        padding: 16px 20px; border-radius: 12px;
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 12px;
    }
    .account-number {
        font-family: 'SF Mono', 'Consolas', monospace;
        font-size: 22px; font-weight: 800; color: var(--text-main); letter-spacing: 1.5px;
    }
    .copy-btn {
        color: var(--primary); font-weight: 700; font-size: 14px;
        cursor: pointer; padding: 8px 16px; border-radius: 8px;
        background: var(--primary-soft); border: none; transition: 0.2s;
    }
    .copy-btn:hover { background: rgba(231, 76, 60, 0.15); transform: translateY(-1px); }

    .account-name { font-size: 14px; color: var(--text-sub); margin-bottom: 32px; font-weight: 500; }
    .account-name strong { color: var(--text-main); font-weight: 700; }

    /* QRIS Card Redesign */
    .qris-card-wrapper {
        background: #fff; border: 2px solid #ed1a24; border-radius: 16px;
        overflow: hidden; max-width: 280px; margin: 0 auto 32px;
        box-shadow: 0 10px 25px rgba(237, 26, 36, 0.15);
    }
    .qris-header { background: #ed1a24; color: #fff; padding: 14px; display: flex; justify-content: space-between; align-items: center; }
    .qris-header-logo { font-weight: 900; font-size: 22px; font-style: italic; letter-spacing: 1px; }
    .gpn-text { font-size: 11px; font-weight: 900; background: #fff; color: #ed1a24; padding: 4px 8px; border-radius: 6px; }
    .qris-body { padding: 24px; text-align: center; }
    .qris-merchant { font-size: 16px; font-weight: 800; color: #111; margin-bottom: 16px; }
    #qrCanvas { display: block; margin: 0 auto; image-rendering: pixelated; border-radius: 8px;}
    .qris-nmid { font-family: 'SF Mono', monospace; font-size: 12px; color: #64748b; margin-top: 20px; font-weight: 700; }

    /* Steps */
    .steps-header { font-size: 12px; font-weight: 800; color: var(--text-sub); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px;}
    .steps-list { padding-left: 0; list-style: none; margin-bottom: 0; }
    .steps-list li {
        font-size: 14px; color: var(--text-main); font-weight: 500; margin-bottom: 12px;
        padding-left: 36px; position: relative; line-height: 1.5;
    }
    .steps-list li::before {
        content: attr(data-step); position: absolute; left: 0; top: -1px;
        width: 24px; height: 24px; background: #fff; border: 1.5px solid #cbd5e1;
        color: var(--text-main); font-size: 12px; font-weight: 800;
        display: flex; align-items: center; justify-content: center; border-radius: 50%;
    }

    /* Upload Section */
    .upload-section {
        text-align: left; margin-bottom: 32px; padding: 24px;
        background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: var(--radius-lg);
        transition: all 0.2s;
    }
    .upload-section:hover { border-color: var(--primary); background: #fdfdfd; }
    .upload-section label { font-size: 15px; font-weight: 800; color: var(--text-main); margin-bottom: 12px; display: block; }
    .upload-section input[type="file"] { width: 100%; font-size: 14px; color: var(--text-sub); padding: 8px 0; cursor: pointer; }
    .upload-section .upload-hint { font-size: 12px; color: #94a3b8; font-weight: 500; margin-top: 8px; line-height: 1.5; }

    /* Action Buttons */
    .action-buttons { display: flex; flex-direction: column; gap: 16px; }
    .btn-confirm-payment {
        padding: 16px; background: #10b981; color: #fff !important;
        border-radius: 12px; font-weight: 800; font-size: 16px;
        transition: all 0.2s; border: none; cursor: pointer; width: 100%; text-align: center;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }
    .btn-confirm-payment:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); }

    .btn-later {
        color: var(--text-sub); font-size: 14px; font-weight: 600;
        text-decoration: none; text-align: center; display: block; padding: 8px;
        transition: color 0.2s;
    }
    .btn-later:hover { color: var(--primary); }

    .total-summary {
        font-size: 15px; font-weight: 600; color: var(--text-sub);
        margin-top: 32px; border-top: 1px dashed var(--border-light); padding-top: 24px;
        display: flex; justify-content: space-between; align-items: center;
    }
    .total-amount { font-size: 24px; color: var(--primary); font-weight: 900; }

    /* COD Info */
    .cod-info {
        background: #f0fdf4; border: 1.5px solid #bbf7d0;
        border-radius: var(--radius-md); padding: 24px; margin-bottom: 24px;
    }
    .cod-info p { color: #166534; font-size: 14px; font-weight: 600; margin: 0; line-height: 1.5; }

    @media (max-width: 600px) { .payment-card-container { padding: 32px 24px; } }
</style>
@endpush

@section('content')
<div class="payment-page">
    <div class="container">
        <div class="payment-card-container">
            <div class="payment-icon-header brand-{{ $order->payment_method }}">
                @if($order->payment_method == 'qris') <i class="fas fa-qrcode"></i> 
                @elseif($order->payment_method == 'cod') <i class="fas fa-box-open"></i>
                @else <i class="fas fa-wallet"></i> @endif
            </div>
            
            <h1>Instruksi Pembayaran</h1>
            <div class="payment-status-badge"><i class="fas fa-clock"></i> Menunggu Pembayaran</div>
            <p style="color: var(--text-sub); font-size: 14px; font-weight: 500; margin-bottom: 32px;">
                Silakan selesaikan pembayaran untuk pesanan <strong>#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong> sebelum waktu verifikasi berakhir.
            </p>

            <div class="payment-instruction-box">
                <div class="instruction-title">
                    @if($order->payment_method == 'qris') <i class="fas fa-qrcode brand-qris"></i>
                    @elseif($order->payment_method == 'cod') <i class="fas fa-truck brand-cod"></i>
                    @elseif(in_array($order->payment_method, ['gopay', 'dana'])) <i class="fas fa-wallet brand-{{ $order->payment_method }}"></i>
                    @else <i class="fas fa-university brand-{{ $order->payment_method }}"></i> @endif
                    {{ $paymentInfo['title'] }}
                </div>

                {{-- QRIS Section --}}
                @if($order->payment_method == 'qris')
                    <div class="qris-card-wrapper">
                        <div class="qris-header">
                            <span class="qris-header-logo">QRIS</span>
                            <span class="gpn-text">GPN</span>
                        </div>
                        <div class="qris-body">
                            <div class="qris-merchant">Belanja.ID</div>
                            <canvas id="qrCanvas" width="200" height="200"></canvas>
                            <div class="qris-nmid">NMID: ID10203040506070</div>
                        </div>
                    </div>
                @endif

                {{-- Account Number Section (Bank & E-Wallet) --}}
                @if(isset($paymentInfo['account_number']))
                    <p class="account-label">
                        @if(in_array($order->payment_method, ['gopay', 'dana']))
                            Nomor Dompet Digital Tujuan:
                        @else
                            Nomor Virtual Account Tujuan:
                        @endif
                    </p>
                    <div class="account-box">
                        <div class="account-number" id="accountNumber">{{ $paymentInfo['account_number'] }}</div>
                        <button type="button" class="copy-btn" onclick="copyNumber()">
                            <i class="fas fa-copy"></i> Salin
                        </button>
                    </div>
                    <p class="account-name">
                        Atas Nama Pembayaran: <strong>{{ $paymentInfo['account_name'] }}</strong>
                    </p>
                @endif

                {{-- COD Section --}}
                @if($order->payment_method == 'cod')
                    <div class="cod-info">
                        <p><i class="fas fa-info-circle"></i> {{ $paymentInfo['description'] }}</p>
                    </div>
                @endif

                {{-- Payment Steps --}}
                <div class="steps-header">Panduan Pembayaran</div>
                <ul class="steps-list">
                    @foreach($paymentInfo['steps'] as $index => $step)
                        <li data-step="{{ $index + 1 }}">{{ $step }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Payment Confirmation Form --}}
            <form action="{{ route('checkout.confirm', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($order->payment_method !== 'cod')
                    <div class="upload-section">
                        <label>
                            <i class="fas fa-cloud-upload-alt" style="color:var(--primary); margin-right:6px;"></i> 
                            Upload Bukti Transfer
                        </label>
                        <input type="file" name="payment_proof" required accept="image/jpeg,image/png,image/jpg">
                        <p class="upload-hint">Format yang diterima: JPG/PNG (Maks 2MB). Sistem akan memproses pesanan Anda sesegera mungkin setelah bukti valid.</p>
                        @error('payment_proof')
                            <p style="color: #ef4444; font-size: 13px; font-weight: 600; margin-top: 8px;">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="action-buttons">
                    <button type="submit" class="btn-confirm-payment">
                        @if($order->payment_method == 'cod')
                            <i class="fas fa-check-circle"></i> Selesaikan Pesanan COD
                        @else
                            <i class="fas fa-check-double"></i> Konfirmasi Telah Update Bayar
                        @endif
                    </button>
                    <a href="{{ route('orders.index') }}" class="btn-later">Verifikasi Nanti — Kembali ke Pesanan</a>
                </div>
            </form>

            <div class="total-summary">
                <span>Total Jumlah Tagihan:</span>
                <span class="total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- QR Code Generator Script (no external dependency) --}}
@if($order->payment_method == 'qris')
<script>
(function() {
    // Simple QR Code generator using canvas
    const canvas = document.getElementById('qrCanvas');
    const ctx = canvas.getContext('2d');
    const size = 200;
    const moduleCount = 25;
    const moduleSize = size / moduleCount;

    // Generate a deterministic pattern based on order data
    const data = 'BELANJAID-QRIS-ORDER-{{ $order->id }}-{{ $order->total_amount }}';

    function simpleHash(str, row, col) {
        let hash = 0;
        const combined = str + row + '-' + col;
        for (let i = 0; i < combined.length; i++) {
            const char = combined.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash;
        }
        return hash;
    }

    // Draw white background
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, size, size);

    // Draw QR-like modules
    ctx.fillStyle = '#111827';

    for (let row = 0; row < moduleCount; row++) {
        for (let col = 0; col < moduleCount; col++) {
            let isDark = false;

            // Finder patterns (top-left, top-right, bottom-left)
            // Top-left
            if (row < 7 && col < 7) {
                if (row === 0 || row === 6 || col === 0 || col === 6) isDark = true;
                else if (row >= 2 && row <= 4 && col >= 2 && col <= 4) isDark = true;
                else isDark = false;
            }
            // Top-right
            else if (row < 7 && col >= moduleCount - 7) {
                const c = col - (moduleCount - 7);
                if (row === 0 || row === 6 || c === 0 || c === 6) isDark = true;
                else if (row >= 2 && row <= 4 && c >= 2 && c <= 4) isDark = true;
                else isDark = false;
            }
            // Bottom-left
            else if (row >= moduleCount - 7 && col < 7) {
                const r = row - (moduleCount - 7);
                if (r === 0 || r === 6 || col === 0 || col === 6) isDark = true;
                else if (r >= 2 && r <= 4 && col >= 2 && col <= 4) isDark = true;
                else isDark = false;
            }
            // Timing patterns
            else if (row === 6) {
                isDark = col % 2 === 0;
            } else if (col === 6) {
                isDark = row % 2 === 0;
            }
            // Alignment pattern (center area)
            else if (row >= 16 && row <= 20 && col >= 16 && col <= 20) {
                if (row === 16 || row === 20 || col === 16 || col === 20) isDark = true;
                else if (row === 18 && col === 18) isDark = true;
                else isDark = false;
            }
            // Separators (white)
            else if ((row === 7 && col < 8) || (row === 7 && col >= moduleCount - 8) ||
                     (col === 7 && row < 8) || (col === 7 && row >= moduleCount - 8) ||
                     (row >= moduleCount - 8 && col === 7) || (row === moduleCount - 8 && col < 8)) {
                isDark = false;
            }
            // Data modules (pseudo-random based on hash)
            else {
                const hash = simpleHash(data, row, col);
                isDark = (Math.abs(hash) % 3) !== 0;
            }

            if (isDark) {
                ctx.fillRect(
                    Math.floor(col * moduleSize),
                    Math.floor(row * moduleSize),
                    Math.ceil(moduleSize),
                    Math.ceil(moduleSize)
                );
            }
        }
    }
})();
</script>
@endif

<script>
function copyNumber() {
    const number = document.getElementById('accountNumber');
    if (number) {
        navigator.clipboard.writeText(number.innerText).then(() => {
            const btn = document.querySelector('.copy-btn');
            btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
            btn.style.color = '#10b981';
            btn.style.background = '#d1fae5';
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-copy"></i> Salin';
                btn.style.color = 'var(--primary)';
                btn.style.background = 'var(--primary-soft)';
            }, 2000);
        });
    }
}
</script>
@endsection
