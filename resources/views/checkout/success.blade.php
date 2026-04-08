@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Belanja.ID')

@push('styles')
<style>
    .success-page {
        padding: 80px 0 120px;
        text-align: center;
        background: #fdfdfd;
    }
    .success-card {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 60px 40px;
        border-radius: 24px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
    }
    .check-icon-wrapper {
        width: 100px;
        height: 100px;
        background: #e8f5e9;
        color: #4caf50;
        font-size: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 30px;
        animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes popIn {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .check-icon-wrapper.verifying {
        background: #fff8e1;
        color: #ffb300;
    }
    .success-card h1 {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 16px;
        color: #1a1a1a;
    }
    .success-card .message {
        font-size: 18px;
        color: #4caf50;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .success-card .message.verifying { color: #ffb300; }
    
    .success-card .sub-message {
        color: #666;
        font-size: 15px;
        margin-bottom: 40px;
        line-height: 1.6;
    }
    .order-tag {
        display: inline-block;
        padding: 10px 24px;
        background: #f5f5f5;
        border-radius: 30px;
        font-weight: 700;
        color: #333;
        margin-bottom: 40px;
        font-size: 14px;
    }
    .order-tag span { color: #db4444; }

    .thank-you-buttons {
        display: flex;
        gap: 16px;
        justify-content: center;
    }
    .btn-return-home {
        padding: 16px 36px;
        background: #db4444;
        color: #fff !important;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(219, 68, 68, 0.2);
    }
    .btn-return-home:hover { background: #c13e3e; transform: translateY(-2px); }
    
    .btn-view-invoice {
        padding: 16px 36px;
        background: #fff;
        color: #555 !important;
        border: 1.5px solid #eee;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        transition: 0.3s;
    }
    .btn-view-invoice:hover { border-color: #db4444; color: #db4444 !important; }

    .delivery-illustration {
        font-size: 60px;
        color: #eee;
        margin-top: 40px;
    }
</style>
@endpush

@section('content')
<div class="success-page">
    <div class="container">
        <div class="success-card">
            @if($order->status == 'verifying')
                <div class="check-icon-wrapper verifying">
                    <i class="fas fa-search-dollar"></i>
                </div>
                <h1>Menunggu Verifikasi!</h1>
                <p class="message verifying">Bukti transfer Anda telah kami terima.</p>
                <p class="sub-message">Admin kami sedang melakukan verifikasi pembayaran Anda. Pesanan akan segera diproses setelah pembayaran dikonfirmasi sah.</p>
            @else
                <div class="check-icon-wrapper">
                    <i class="fas fa-check"></i>
                </div>
                <h1>Hore, Pesanan Berhasil!</h1>
                <p class="message">Terimakasih sudah memesan, pesanan akan kami proses secepatnya.</p>
                @if($order->payment_method === 'cod')
                    <p class="sub-message">Anda memilih Bayar di Tempat (COD). Siapkan uang tunai Anda saat kurir tiba di alamat tujuan.</p>
                @else
                    <p class="sub-message">Kami telah menerima konfirmasi pesanan Anda. Kami akan segera mengirimkannya ke alamat Anda.</p>
                @endif
            @endif
            
            <div class="order-tag">
                NOMOR PESANAN: <span>#{{ $order->id }}</span>
            </div>

            <div class="thank-you-buttons">
                <a href="/" class="btn-return-home">Lanjut Belanja</a>
                <a href="{{ route('orders.index') }}" class="btn-view-invoice">Lihat Detail Pesanan</a>
            </div>

            <div class="delivery-illustration">
                <i class="fas fa-truck-loading"></i>
            </div>
        </div>
    </div>
</div>
@endsection
