@php
    $layout = (Auth::user()->isAdmin() || Auth::user()->role === 'petugas') ? 'layouts.admin' : 'layouts.app';
@endphp

@extends($layout)

@section('title', 'Halaman Pengaturan - Belanja.ID')
@section('page_title', 'Profil Saya')

@push('styles')
<style>
    /* Profile Page Styling */
    .profile-container { 
        padding: {{ $layout === 'layouts.admin' ? '0' : '60px 0' }}; 
        min-height: {{ $layout === 'layouts.admin' ? 'auto' : '90vh' }}; 
        background-color: {{ $layout === 'layouts.admin' ? 'transparent' : '#f8fafc' }}; 
        font-family: 'Inter', sans-serif; 
    }
    .profile-grid { 
        display: grid; 
        grid-template-columns: {{ $layout === 'layouts.admin' ? '1fr' : '340px 1fr' }}; 
        gap: 40px; 
    }
    
    .profile-sidebar { 
        display: {{ $layout === 'layouts.admin' ? 'none' : 'block' }};
        background: #fff; border-radius: 24px; padding: 40px 24px; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.04); height: fit-content;
        position: sticky; top: 120px; border: 1px solid rgba(0,0,0,0.02);
    }
    .profile-user-card { text-align: center; margin-bottom: 40px; }
    .profile-avatar { 
        width: 100px; height: 100px; background: linear-gradient(135deg, #0f172a 0%, #334155 100%); 
        color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; 
        font-size: 40px; font-weight: 800; margin: 0 auto 20px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
        border: 4px solid #fff;
    }
    
    .profile-nav { display: flex; flex-direction: column; gap: 8px; }
    .profile-nav a { 
        display: flex; align-items: center; gap: 14px; padding: 16px 20px; 
        border-radius: 16px; color: #64748b; font-weight: 600; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none; font-size: 15px; border: 1px solid transparent;
    }
    .profile-nav a:hover { background: #f8fafc; color: #0f172a; border-color: #f1f5f9; transform: translateX(5px); }
    .profile-nav a.active { 
        background: #0f172a; color: #fff; 
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);
    }
    .profile-nav a i { font-size: 18px; width: 24px; text-align: center; }
    .profile-nav a.active i { color: #fff; }

    .profile-content { 
        background: #fff; border-radius: 32px; padding: 48px; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.04); 
        border: 1px solid rgba(0,0,0,0.02);
    }
    .content-header { margin-bottom: 40px; }
    .content-title { font-size: 32px; font-weight: 900; color: #0f172a; letter-spacing: -1px; margin-bottom: 8px; }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
    .form-group { margin-bottom: 28px; }
    .form-group.full { grid-column: span 2; }
    .form-label { display: block; font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 12px; letter-spacing: 0.2px; }
    .form-input { 
        width: 100%; padding: 16px 24px; border-radius: 16px; border: 1px solid #e2e8f0; 
        font-size: 15px; transition: all 0.3s; background: #f8fafc;
        font-family: inherit; color: #0f172a;
    }
    .form-input:focus { 
        border-color: #0f172a; box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05); 
        outline: none; background: #fff; 
    }
    
    .btn-save { 
        background: #0f172a; color: #fff; border: none; padding: 16px 32px; 
        border-radius: 16px; font-weight: 700; cursor: pointer; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: inline-flex; align-items: center; gap: 10px; font-size: 15px;
    }
    .btn-save:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(15, 23, 42, 0.2); }
    .btn-save.primary { background: var(--primary); }
    .btn-save.primary:hover { background: #c0392b; box-shadow: 0 15px 30px rgba(231, 76, 60, 0.25); }
    
    .password-field-wrapper { position: relative; }
    .password-toggle { 
        position: absolute; right: 20px; top: 50%; transform: translateY(-50%); 
        cursor: pointer; color: #94a3b8; transition: all 0.2s; padding: 5px;
        z-index: 10; font-size: 18px;
    }
    .password-toggle:hover { color: #0f172a; }
    
    .animate-in { animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Stats */
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-top: 32px; }
    .stat-card { 
        background: #f8fafc; padding: 24px 16px; border-radius: 20px; text-align: center; 
        border: 1px solid #f1f5f9; transition: all 0.3s;
    }
    .stat-card:hover { background: #fff; border-color: #e2e8f0; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.02); }
    .stat-value { display: block; font-size: 26px; font-weight: 900; color: #0f172a; margin-bottom: 4px; }
    .stat-label { font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; }

    @media (max-width: 1024px) {
        .profile-grid { grid-template-columns: 1fr; }
        .profile-sidebar { position: static; max-width: 600px; margin: 0 auto; }
    }
</style>
@endpush

@section('content')
<div class="profile-container {{ $layout === 'layouts.admin' ? 'admin-profile-view' : '' }}">
    <div class="container">
        @if($layout === 'layouts.admin')
        <div class="profile-nav-tabs" style="display: flex; gap: 24px; margin-bottom: 32px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;">
            <a href="javascript:void(0)" class="tab-trigger active" data-tab="tab-profile" style="font-weight: 700; color: #0f172a; text-decoration: none; padding-bottom: 12px; border-bottom: 2px solid #0f172a;">Informasi Pribadi</a>
            <a href="javascript:void(0)" class="tab-trigger" data-tab="tab-security" style="font-weight: 600; color: #64748b; text-decoration: none; padding-bottom: 12px; border-bottom: 2px solid transparent;">Keamanan Akun</a>
        </div>
        @endif

        <div class="profile-grid">
            {{-- Sidebar --}}
            <aside class="profile-sidebar">
                <div class="profile-user-card">
                    <div class="profile-avatar" id="avatar-sidebar">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="Avatar" id="avatar-img-sidebar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            {{ substr($user->name, 0, 1) }}
                        @endif
                    </div>
                    <h3 class="profile-user-card-name" style="font-weight: 800; color: #0f172a; font-size: 20px; margin-bottom: 4px;">{{ $user->name }}</h3>
                    <p style="font-size: 14px; color: #64748b; font-weight: 500;">Member sejak {{ $user->created_at->format('M Y') }}</p>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-value">{{ $orderCount }}</span>
                            <span class="stat-label">Pesanan</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value">{{ $reviewCount }}</span>
                            <span class="stat-label">Ulasan</span>
                        </div>
                    </div>
                </div>
                
                <nav class="profile-nav">
                    <a href="#" class="profile-tab-btn active" data-tab="tab-profile"><i class="fas fa-user-cog"></i> Edit Profil</a>
                    <a href="#" class="profile-tab-btn" data-tab="tab-security"><i class="fas fa-shield-alt"></i> Keamanan Akun</a>
                    <a href="{{ route('orders.index') }}"><i class="fas fa-box"></i> Pesanan Saya</a>
                    <a href="{{ route('messages.index') }}"><i class="fas fa-envelope"></i> Kotak Masuk 
                        @php
                            $unreadMessagesCount = \App\Models\Message::where('conversation_id', '!=', 0) // Dummy to ensure query works
                                ->whereHas('conversation', function($q) { $q->where('user_id', Auth::id()); })
                                ->where('sender_id', '!=', Auth::id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if($unreadMessagesCount > 0)
                            <span style="background: #ef4444; color: #fff; font-size: 10px; padding: 2px 8px; border-radius: 10px; margin-left: auto;">{{ $unreadMessagesCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('wishlist') }}"><i class="fas fa-heart"></i> Wishlist</a>
                    <hr style="border:none; border-top:1px solid #f1f5f9; margin:16px 0;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background:none; border:none; width:100%; text-align:left; padding:14px 18px; border-radius:14px; color:#ef4444; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:14px; font-size:15px; font-family:'Inter';">
                            <i class="fas fa-sign-out-alt" style="width:24px; text-align:center;"></i> Logout
                        </button>
                    </form>
                </nav>
            </aside>
            
            {{-- Main Content --}}
            <main class="profile-content">
                {{-- Profile Tab --}}
                <div id="tab-profile" class="profile-tab-content animate-in">
                    <form id="profile-form" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="content-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 32px;">
                            <div>
                                <h2 class="content-title">Informasi Pribadi</h2>
                                <p style="color: #64748b; font-size: 15px; font-weight: 500;">Perbarui data profil Belanja.ID Anda untuk kemudahan berbelanja.</p>
                            </div>
                            <button type="submit" class="btn-save primary" style="background: #db4444 !important; color: #fff !important; padding: 12px 28px; min-width: 160px; box-shadow: 0 10px 20px rgba(219, 68, 68, 0.2);">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>

                        <div class="form-grid">
                            <div class="form-group full">
                                <label class="form-label">Foto Profil</label>
                                <div style="display: flex; align-items: center; gap: 24px; padding: 20px; background: #fcfdfe; border-radius: 16px; border: 1px solid #f1f5f9;">
                                    <div id="avatar-preview-container" style="width: 100px; height: 100px; border-radius: 50%; background: #f1f5f9; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 4px solid #fff; box-shadow: 0 8px 16px rgba(0,0,0,0.08);">
                                        @if($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-user" style="color: #cbd5e1; font-size: 24px;"></i>
                                        @endif
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        <input type="file" name="avatar" id="avatar-input" style="display: none;" accept="image/*">
                                        <button type="button" class="btn-save" style="background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; padding: 10px 20px; font-size: 14px;" onclick="document.getElementById('avatar-input').click()">
                                            <i class="fas fa-camera"></i> Ganti Foto Profil
                                        </button>
                                        <span id="file-name" style="font-size: 12px; color: #94a3b8; font-weight: 500;">Format: JPG, PNG. Maksimal 2MB.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-input" value="{{ $user->name }}" required placeholder="Masukkan nama lengkap Anda">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Email</label>
                                <input type="email" name="email" class="form-input" value="{{ $user->email }}" required placeholder="email@contoh.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor WhatsApp / HP</label>
                                <input type="text" name="phone" class="form-input" value="{{ $user->phone }}" placeholder="Contoh: 08123456789">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Provinsi</label>
                                <input type="text" name="province" class="form-input" value="{{ $user->province }}" placeholder="Masukkan provinsi">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kota / Kabupaten</label>
                                <input type="text" name="city" class="form-input" value="{{ $user->city }}" placeholder="Masukkan kota/kabupaten">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kecamatan</label>
                                <input type="text" name="district" class="form-input" value="{{ $user->district }}" placeholder="Masukkan kecamatan">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" name="postal_code" class="form-input" value="{{ $user->postal_code }}" placeholder="Masukkan kode pos">
                            </div>
                            <div class="form-group full">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="address" class="form-input" style="min-height: 100px; resize: vertical;" placeholder="Tuliskan nama jalan, nomor rumah, blok, RT/RW, dsb">{{ $user->address }}</textarea>
                            </div>
                        </div>
                        <div style="margin-top: 32px; border-top: 1px solid #f1f5f9; padding-top: 24px; display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn-save primary" id="btn-save-profile-bottom">
                                <i class="fas fa-check-circle"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Security Tab --}}
                <div id="tab-security" class="profile-tab-content animate-in" style="display: none;">
                    <form id="password-form">
                        @csrf
                        @method('PATCH')
                        
                        <div class="content-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 32px;">
                            <div>
                                <h2 class="content-title">Keamanan Akun</h2>
                                <p style="color: #64748b; font-size: 15px; font-weight: 500;">Lindungi akun Anda dengan memperbarui kata sandi secara berkala.</p>
                            </div>
                            <button type="submit" class="btn-save" style="background: #0f172a !important; color: #fff !important; padding: 12px 28px; min-width: 160px;">
                                <i class="fas fa-shield-alt"></i> Perbarui Password
                            </button>
                        </div>

                        <div class="form-grid">
                            <div class="form-group full">
                                <label class="form-label">Kata Sandi Saat Ini</label>
                                <div class="password-field-wrapper">
                                    <input type="password" name="current_password" class="form-input" placeholder="Masukkan kata sandi lama" required>
                                    <i class="fas fa-eye password-toggle"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kata Sandi Baru</label>
                                <div class="password-field-wrapper">
                                    <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                                    <i class="fas fa-eye password-toggle"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <div class="password-field-wrapper">
                                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi kata sandi baru" required>
                                    <i class="fas fa-eye password-toggle"></i>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 32px; border-top: 1px solid #f1f5f9; padding-top: 24px; display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn-save" id="btn-save-password-bottom" style="background: #0f172a;">
                                <i class="fas fa-shield-alt"></i> Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle Tab Switching
    document.querySelectorAll('.profile-tab-btn, .tab-trigger').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tabId = this.getAttribute('data-tab');
            if (!tabId) return;
            
            // Update UI
            document.querySelectorAll('.profile-tab-btn, .tab-trigger').forEach(l => {
                l.classList.remove('active');
                if (l.classList.contains('tab-trigger')) {
                    l.style.borderBottomColor = 'transparent';
                    l.style.color = '#64748b';
                }
            });
            
            this.classList.add('active');
            if (this.classList.contains('tab-trigger')) {
                this.style.borderBottomColor = '#0f172a';
                this.style.color = '#0f172a';
            }
            
            // Show Tab
            document.querySelectorAll('.profile-tab-content').forEach(tab => tab.style.display = 'none');
            document.getElementById(tabId).style.display = 'block';
        });
    });

    // Password Visibility Toggle Logic
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });

    // Live Preview for Avatar
    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const fileName = document.getElementById('file-name');
        const previewContainer = document.getElementById('avatar-preview-container');
        
        if (file) {
            fileName.textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(event) {
                previewContainer.innerHTML = `<img src="${event.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
            reader.readAsDataURL(file);
        } else {
            fileName.textContent = 'Belum ada file dipilih';
        }
    });

    // Handle Profile Update
    document.getElementById('profile-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');
        const originalContent = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        
        try {
            const formData = new FormData(form);
            const response = await fetch('{{ route("profile.update") }}', {
                method: 'POST', 
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const data = await response.json();
            if (response.ok) {
                showToast(data.message);
                
                // Update UI elements instantly
                if (data.user) {
                    const profileName = document.querySelector('.profile-user-card-name');
                    if (profileName) profileName.textContent = data.user.name;
                    
                    const navName = document.getElementById('nav-user-name');
                    if (navName) navName.textContent = data.user.name;
                    
                    if (data.avatar_url) {
                        const sidebarAvatar = document.getElementById('avatar-sidebar');
                        if (sidebarAvatar) {
                            sidebarAvatar.innerHTML = `<img src="${data.avatar_url}" id="avatar-img-sidebar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                        }
                        
                        const navAvatar = document.getElementById('nav-avatar');
                        if (navAvatar) {
                            navAvatar.innerHTML = `<img src="${data.avatar_url}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                        }
                    }
                }
            } else {
                const errorMsg = data.errors ? Object.values(data.errors).flat()[0] : (data.message || 'Gagal memperbarui profil.');
                showToast(errorMsg, 'error');
            }
        } catch (error) {
            console.error(error);
            showToast('Terjadi kesalahan teknis.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    });

    // Handle Password Update
    document.getElementById('password-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');
        const originalContent = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memperbarui...';
        
        try {
            const formData = new FormData(form);
            const response = await fetch('{{ route("profile.password") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const data = await response.json();
            if (response.ok) {
                showToast(data.message);
                form.reset();
            } else {
                const errorMsg = data.errors ? Object.values(data.errors).flat()[0] : (data.message || 'Gagal memperbarui password.');
                showToast(errorMsg, 'error');
            }
        } catch (error) {
            console.error(error);
            showToast('Terjadi kesalahan teknis.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    });
</script>
@endpush
@endsection
