@extends('layouts.admin')

@section('title', 'Manajemen Diskon & Promo - Belanja.ID')
@section('page_title', 'Manajemen Diskon & Promo')

@section('content')
<div class="admin-card">
    <div style="margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; color: #333;">Daftar Produk & Pengaturan Diskon</h3>
        <p style="font-size: 13px; color: #666;">Kelola harga coret, persentase diskon, dan badge promo di sini.</p>
    </div>

    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f8f9fa; color: #888; font-size: 12px; text-transform: uppercase;">
                    <th style="padding: 16px; font-weight: 700;">Produk</th>
                    <th style="padding: 16px; font-weight: 700;">Harga Sekarang</th>
                    <th style="padding: 16px; font-weight: 700;">Harga Coret (Old)</th>
                    <th style="padding: 16px; font-weight: 700;">Diskon (%)</th>
                    <th style="padding: 16px; font-weight: 700;">Badge</th>
                    <th style="padding: 16px; font-weight: 700; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr style="border-bottom: 1px solid #f8f9fa; font-size: 14px;">
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ $product->main_image }}" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                            <span style="font-weight: 600;">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td style="padding: 16px; font-weight: 700;">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <form action="{{ route('admin.promos.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <td style="padding: 16px;">
                            <input type="number" name="old_price" value="{{ $product->old_price ? (int)$product->old_price : '' }}" placeholder="None" style="width: 120px; padding: 8px; border: 1px solid #eee; border-radius: 6px; outline: none;">
                        </td>
                        <td style="padding: 16px;">
                            <input type="text" name="discount" value="{{ $product->discount }}" placeholder="e.g. 40%" style="width: 80px; padding: 8px; border: 1px solid #eee; border-radius: 6px; outline: none;">
                        </td>
                        <td style="padding: 16px;">
                            <input type="text" name="badge" value="{{ $product->badge }}" placeholder="e.g. HOT" style="width: 100px; padding: 8px; border: 1px solid #eee; border-radius: 6px; outline: none;">
                        </td>
                        <td style="padding: 16px; text-align: right;">
                            <button type="submit" class="btn-primary" style="background: #3498db; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                                Update Promo
                            </button>
                        </td>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    .admin-card { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    input:focus { border-color: #3498db !important; }
    .btn-primary:hover { transform: translateY(-1px); opacity: 0.9; }
</style>
@endsection
