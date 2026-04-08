@extends('layouts.app')

@section('title', 'Daftar - Belanja.ID')
@section('meta_description', 'Buat akun Belanja.ID dan mulai berbelanja produk berkualitas dengan harga terbaik.')

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
        --border-light: #e2e8f0;
        --radius-xl: 24px;
        --radius-lg: 16px;
        --radius-md: 12px;
        --shadow-sm: 0 4px 6px -1px rgba(0,0,0,0.05);
        --shadow-md: 0 10px 30px -5px rgba(0,0,0,0.1);
    }

    .auth-page {
        min-height: calc(100vh - 72px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: var(--bg-color);
        font-family: 'Inter', sans-serif;
    }
    .auth-container {
        display: flex;
        max-width: 960px;
        width: 100%;
        background: var(--surface);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-light);
    }
    .auth-banner {
        flex: 1;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px;
        position: relative;
        overflow: hidden;
    }
    .auth-banner::before {
        content: ''; position: absolute; width: 300px; height: 300px;
        background: radial-gradient(circle, var(--primary) 0%, transparent 70%);
        opacity: 0.15; top: -100px; right: -100px; border-radius: 50%;
    }
    .auth-banner::after {
        content: ''; position: absolute; width: 400px; height: 400px;
        background: radial-gradient(circle, #3b82f6 0%, transparent 70%);
        opacity: 0.1; bottom: -150px; left: -150px; border-radius: 50%;
    }
    .auth-banner-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }
    .auth-banner-content .banner-icon {
        width: 80px; height: 80px; background: rgba(255,255,255,0.05);
        border-radius: 20px; display: flex; align-items: center; justify-content: center;
        font-size: 32px; color: #fff; margin: 0 auto 32px; backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .auth-banner-content h2 {
        font-size: 32px; font-weight: 800; color: #fff;
        margin-bottom: 12px; line-height: 1.3; letter-spacing: -0.5px;
    }
    .auth-banner-content p {
        font-size: 15px; color: rgba(255,255,255,0.7);
        line-height: 1.6; max-width: 280px; margin: 0 auto;
    }
    .auth-form-wrapper {
        flex: 1; padding: 48px; display: flex;
        flex-direction: column; justify-content: center;
    }
    .auth-form-header { margin-bottom: 28px; }
    .auth-form-header h1 {
        font-size: 28px; font-weight: 900; color: var(--text-main); margin-bottom: 8px; letter-spacing: -0.5px;
    }
    .auth-form-header p { font-size: 14.5px; color: var(--text-sub); }
    .auth-form-header p a { color: var(--primary); font-weight: 700; transition: 0.2s; }
    .auth-form-header p a:hover { color: var(--primary-hover); text-decoration: underline; }
    
    .form-group { margin-bottom: 18px; }
    .form-group label {
        display: block; font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;
    }
    .form-control {
        width: 100%; padding: 14px 16px; background: #f8fafc;
        border: 1.5px solid var(--border-light); border-radius: var(--radius-md);
        font-size: 15px; font-family: 'Inter', sans-serif; color: var(--text-main);
        transition: all 0.2s; outline: none;
    }
    .form-control:focus { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-soft); }
    .form-control::placeholder { color: #94a3b8; }
    .form-control.is-invalid { border-color: #ef4444; }
    .form-error { font-size: 12px; color: #ef4444; font-weight: 600; margin-top: 6px; display: block; }
    
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    .btn-auth {
        width: 100%; padding: 16px; background: var(--primary); color: #fff;
        border: none; border-radius: var(--radius-md); font-size: 16px; font-weight: 700;
        cursor: pointer; font-family: 'Inter', sans-serif; transition: all 0.3s;
        box-shadow: 0 4px 15px var(--primary-soft); display: flex; justify-content: center; align-items: center; gap: 10px;
        margin-top: 10px;
    }
    .btn-auth:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3); }

    .auth-divider {
        display: flex; align-items: center; gap: 16px; margin: 28px 0; color: #94a3b8; font-size: 12px; font-weight: 600; text-transform: uppercase;
    }
    .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: var(--border-light); }
    
    .social-login { display: flex; gap: 16px; }
    .social-btn {
        flex: 1; padding: 14px; border: 1.5px solid var(--border-light); border-radius: var(--radius-md); background: #fff;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;
        font-size: 14px; font-weight: 600; color: var(--text-main); font-family: 'Inter', sans-serif; transition: all 0.2s;
    }
    .social-btn:hover { border-color: #cbd5e1; background: #f8fafc; transform: translateY(-1px); }
    .social-btn .fa-google { color: #ea4335; font-size: 18px; }
    .social-btn .fa-facebook-f { color: #1877f2; font-size: 18px; }

    .terms-text {
        font-size: 12px; color: var(--text-sub); text-align: center; margin-top: 24px; line-height: 1.6; font-weight: 500;
    }
    .terms-text a { color: var(--text-main); font-weight: 700; text-decoration: underline; transition: color 0.2s;}
    .terms-text a:hover { color: var(--primary); }

    @media (max-width: 900px) { .auth-banner { display: none; } .auth-container { max-width: 480px; } }
    @media (max-width: 480px) { 
        .auth-form-wrapper { padding: 40px 24px; } 
        .social-login { flex-direction: column; } 
        .form-row { grid-template-columns: 1fr; gap: 0; }
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <!-- Left Banner -->
        <div class="auth-banner">
            <div class="auth-banner-content">
                <div class="banner-icon"><i class="fas fa-layer-group"></i></div>
                <h2>Mulai Langkah<br>Baru Anda</h2>
                <p>Daftar sekarang secara gratis, dan dapatkan akses ke jutaan produk dengan harga terbaik.</p>
            </div>
        </div>

        <!-- Right Form -->
        <div class="auth-form-wrapper">
            <div class="auth-form-header">
                <h1>Buat Akun</h1>
                <p>Sudah memiliki akun? <a href="{{ route('login') }}">Masuk disini</a></p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           placeholder="Contoh: Budi Santoso"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" required autofocus>
                    @error('name')
                        <span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                    @error('email')
                        <span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Kata Sandi</label>
                        <input type="password" id="password" name="password"
                               placeholder="Min. 8 Karakter"
                               class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                        @error('password')
                            <span class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="Ulangi Sandi" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn-auth" id="btn-register">
                    Daftar Sekarang <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="auth-divider">Atau daftar cepat dengan</div>

            <div class="social-login">
                <button class="social-btn" type="button"><i class="fab fa-google"></i> Google</button>
                <button class="social-btn" type="button"><i class="fab fa-facebook-f"></i> Facebook</button>
            </div>

            <p class="terms-text">
                Dengan mendaftar, Anda menyetujui <a href="#">Syarat & Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> kami.
            </p>
        </div>
    </div>
</div>
@endsection
