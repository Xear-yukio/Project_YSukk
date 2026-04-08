@extends('layouts.admin')

@section('title', 'Pengaturan - Belanja.ID')
@section('page_title', 'Pengaturan Sistem')

@section('content')
<div class="admin-card">
    <div style="display: grid; grid-template-columns: 240px 1fr; gap: 40px;">
        {{-- Tabs --}}
        <div style="border-right: 1px solid #eee; padding-right: 20px;">
            <ul style="list-style: none;">
                <li style="margin-bottom: 8px;"><a href="#" style="display: block; padding: 10px 16px; background: #fef2f2; color: #e74c3c; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 14px;">Umum</a></li>
                <li style="margin-bottom: 8px;"><a href="#" style="display: block; padding: 10px 16px; color: #666; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 14px;">Tampilan UI</a></li>
                <li style="margin-bottom: 8px;"><a href="#" style="display: block; padding: 10px 16px; color: #666; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 14px;">Pembayaran</a></li>
                <li style="margin-bottom: 8px;"><a href="#" style="display: block; padding: 10px 16px; color: #666; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 14px;">Email & Notifikasi</a></li>
                <li style="margin-bottom: 8px;"><a href="#" style="display: block; padding: 10px 16px; color: #666; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 14px;">Backup Data</a></li>
            </ul>
        </div>

        {{-- Form --}}
        <div>
            <div style="margin-bottom: 32px;">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Informasi Toko</h3>
                <p style="font-size: 13px; color: #888;">Kelola identitas publik toko Anda di platform.</p>
            </div>

            <form>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #555; margin-bottom: 8px;">Nama Toko</label>
                        <input type="text" value="Belanja.ID" style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #555; margin-bottom: 8px;">Email Toko</label>
                        <input type="email" value="cs@belanja.id" style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none; font-size: 14px;">
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #555; margin-bottom: 8px;">Alamat Kantor Pusat</label>
                    <textarea style="width: 100%; padding: 12px; border: 1px solid #eee; border-radius: 8px; outline: none; font-size: 14px; min-height: 80px;">Jl. Sudirman No. 123, Jakarta Pusat, Indonesia</textarea>
                </div>

                <div style="border-top: 1px solid #eee; padding-top: 24px; margin-top: 32px; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" style="padding: 12px 24px; border: 1px solid #eee; background: #fff; border-radius: 8px; font-weight: 700; color: #666; cursor: pointer;">Batal</button>
                    <button type="button" style="padding: 12px 24px; border: none; background: #e74c3c; color: #fff; border-radius: 8px; font-weight: 700; cursor: pointer;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-card { background: #fff; padding: 40px; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
    input:focus, textarea:focus { border-color: #e74c3c !important; box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.05); }
</style>
@endsection
