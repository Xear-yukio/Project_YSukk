@extends('layouts.app')

@section('title', 'Hubungi Kami - Belanja.ID')

@push('styles')
<style>
    :root {
        --primary: #e74c3c;
        --primary-hover: #c0392b;
        --primary-soft: rgba(231, 76, 60, 0.08);
        --text-main: #0f172a;
        --text-sub: #64748b;
        --bg-color: #f8fafc;
        --surface: #ffffff;
        --radius-xl: 20px;
        --radius-lg: 16px;
        --radius-md: 12px;
        --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.05);
        --shadow-md: 0 10px 20px -5px rgba(0,0,0,0.08);
        --border-light: #e2e8f0;
    }

    .contact-page {
        padding: 40px 0 100px;
        background: var(--bg-color);
        font-family: 'Inter', sans-serif;
    }

    .breadcrumb {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-sub);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 32px;
    }
    .breadcrumb a { color: var(--text-sub); text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: var(--primary); }
    .breadcrumb .active { color: var(--primary); }

    .contact-header {
        text-align: center;
        margin-bottom: 50px;
    }
    .contact-header h1 {
        font-size: 36px;
        font-weight: 900;
        color: var(--text-main);
        letter-spacing: -0.5px;
        margin-bottom: 12px;
    }
    .contact-header p {
        font-size: 16px;
        color: var(--text-sub);
        max-width: 500px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .contact-container {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 32px;
    }

    /* Left - Info Cards */
    .contact-info-wrapper {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }
    .info-card {
        background: var(--surface);
        padding: 32px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-light);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: #cbd5e1;
    }
    .info-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }
    .info-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-soft);
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .info-header h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-main);
        margin: 0;
    }
    .info-content p {
        font-size: 14.5px;
        color: var(--text-sub);
        margin-bottom: 10px;
        line-height: 1.5;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-content p:last-child { margin-bottom: 0; }
    .info-content p strong { color: var(--text-main); font-weight: 600; }

    /* Right - Form */
    .contact-form-card {
        background: var(--surface);
        padding: 40px;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-light);
    }
    .form-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 32px;
        padding-bottom: 16px;
        border-bottom: 2px dashed var(--border-light);
    }
    
    .form-group-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 24px;
    }
    .form-group.full { margin-bottom: 32px; }
    .form-group label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-main);
    }
    .form-group label span { color: var(--primary); }
    
    .form-control {
        background: #f8fafc;
        border: 1.5px solid var(--border-light);
        padding: 14px 16px;
        border-radius: var(--radius-md);
        font-size: 14px;
        color: var(--text-main);
        width: 100%;
        transition: all 0.2s;
        font-family: inherit;
        outline: none;
    }
    .form-control:focus {
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-soft);
    }
    .form-control::placeholder { color: #94a3b8; }
    
    textarea.form-control {
        height: 160px;
        resize: vertical;
    }

    .btn-submit {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 16px 32px;
        border-radius: var(--radius-md);
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px var(--primary-soft);
    }
    .btn-submit:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
    }

    @media (max-width: 992px) {
        .contact-container { grid-template-columns: 1fr; }
        .contact-info-wrapper { flex-direction: row; }
        .info-card { flex: 1; }
    }
    @media (max-width: 768px) {
        .contact-info-wrapper { flex-direction: column; }
        .form-group-row { grid-template-columns: 1fr; }
        .contact-form-card { padding: 32px 24px; }
    }
</style>
@endpush

@section('content')
<div class="contact-page">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="/"><i class="fas fa-home"></i> Beranda</a>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span class="active">Hubungi Kami</span>
        </div>

        <div class="contact-header">
            <h1>Sapa Tim Kami</h1>
            <p>Punya pertanyaan terkait pesanan, kendala teknis, atau sekadar memberi masukan? Kami selalu siap membantu Anda kapan saja.</p>
        </div>

        <div class="contact-container">
            <!-- Left Info Cards -->
            <div class="contact-info-wrapper">
                <div class="info-card">
                    <div class="info-header">
                        <div class="info-icon"><i class="fas fa-headset"></i></div>
                        <h3>Bantuan Langsung</h3>
                    </div>
                    <div class="info-content">
                        <p>Kami tersedia 24/7, setiap hari.</p>
                        <p><i class="fab fa-whatsapp" style="color:#25D366; font-size:16px;"></i> <strong>+62 821 1234 5678</strong></p>
                        <p><i class="fas fa-phone-alt" style="color:#64748b; font-size:14px;"></i> <strong>(022) 888 9999</strong></p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-header">
                        <div class="info-icon"><i class="fas fa-envelope-open-text"></i></div>
                        <h3>Alamat Surel</h3>
                    </div>
                    <div class="info-content">
                        <p>Kirimkan masalah atau masukan secara tertulis (Akan dibalas max 24 jam).</p>
                        <p><i class="fas fa-paper-plane" style="color:#64748b; font-size:14px;"></i> <strong>support@belanja.id</strong></p>
                        <p><i class="fas fa-briefcase" style="color:#64748b; font-size:14px;"></i> <strong>kemitraan@belanja.id</strong></p>
                    </div>
                </div>
            </div>

            <!-- Right Contact Form -->
            <div class="contact-form-card">
                <div class="form-title">Kirimkan Pesan Anda</div>
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <div class="form-group-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Nama Lengkap <span>*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ Auth::user() ? Auth::user()->name : '' }}" placeholder="Contoh: Budi Santoso" required {{ Auth::check() ? 'readonly' : '' }}>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Subjek Pesan <span>*</span></label>
                            <input type="text" name="subject" class="form-control" placeholder="Contoh: Pertanyaan Layanan" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Alamat Email <span>*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ Auth::user() ? Auth::user()->email : '' }}" placeholder="nama@email.com" required {{ Auth::check() ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group full" style="margin-bottom: 24px;">
                        <label>Tuliskan Pesan <span>*</span></label>
                        <textarea name="message" class="form-control" placeholder="Jelaskan kebutuhan, masalah, atau umpan balik Anda di sini..." required></textarea>
                    </div>

                    @if(!Auth::check())
                        <div style="background: #fff8eb; border: 1px solid #ffeeba; padding: 12px 16px; border-radius: 12px; margin-bottom: 24px; color: #856404; font-size: 13.5px; display: flex; align-items: flex-start; gap: 12px;">
                            <i class="fas fa-exclamation-triangle" style="margin-top: 3px;"></i>
                            <span>Silakan <a href="{{ route('login') }}" style="color: #e74c3c; font-weight: 700;">Login</a> terlebih dahulu agar pesan Anda dapat dilacak dan dibalas oleh tim kami melalui Inbox.</span>
                        </div>
                    @endif

                    <button type="submit" class="btn-submit" {{ !Auth::check() ? 'disabled' : '' }}>
                        <i class="fas fa-paper-plane"></i> Kirim Pesan Sekarang
                    </button>
                    @if(!Auth::check())
                        <p style="font-size: 13px; color: #94a3b8; margin-top: 12px;">Anda harus login untuk mengirim pesan.</p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
