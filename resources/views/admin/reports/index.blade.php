@extends('layouts.admin')

@section('title', 'Laporan Transaksi - Belanja.ID')
@section('page_title', 'Laporan & Analitik')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
    {{-- Summary Cards --}}
    <div class="report-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <span style="font-size: 13px; font-weight: 600; color: #888;">Total Penjualan (Seluruh Waktu)</span>
            <span style="padding: 2px 8px; border-radius: 4px; background: #dcfce7; color: #166534; font-size: 11px; font-weight: 700;">LIVE</span>
        </div>
        <h2 style="font-size: 28px; font-weight: 800; color: #111; margin-bottom: 20px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
        <div style="height: 60px; background: linear-gradient(90deg, #e74c3c 100%, #f0f0f0 0%); border-radius: 4px; position: relative; overflow: hidden;">
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; background: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(255,255,255,0.1) 5px, rgba(255,255,255,0.1) 10px);"></div>
        </div>
    </div>

    <div class="report-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <span style="font-size: 13px; font-weight: 600; color: #888;">Rata-rata Order</span>
            <span style="padding: 2px 8px; border-radius: 4px; background: #dbeafe; color: #1e40af; font-size: 11px; font-weight: 700;">INFO</span>
        </div>
        <h2 style="font-size: 28px; font-weight: 800; color: #111; margin-bottom: 20px;">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h2>
        <div style="display: flex; gap: 4px; align-items: flex-end; height: 60px;">
            @for($i=1; $i<=10; $i++)
                <div style="flex: 1; height: {{ rand(30, 100) }}%; background: #3498db; border-radius: 2px;"></div>
            @endfor
        </div>
    </div>
</div>

<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 700;">Laporan Performa Kategori</h3>
        <button onclick="window.print()" style="border: none; background: #f8f9fa; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; color: #555; cursor: pointer;">Cetak Laporan</button>
    </div>
    
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f8f9fa; color: #888; font-size: 12px; text-transform: uppercase;">
                    <th style="padding: 12px; font-weight: 700;">Kategori</th>
                    <th style="padding: 12px; font-weight: 700;">Total Produk Terjual</th>
                    <th style="padding: 12px; font-weight: 700;">Pendapatan</th>
                    <th style="padding: 12px; font-weight: 700; text-align: right;">Status Performa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formattedReports as $r)
                <tr style="border-bottom: 1px solid #f8f9fa; font-size: 14px;">
                    <td style="padding: 16px; font-weight: 600; color: #333;">{{ $r['cat'] }}</td>
                    <td style="padding: 16px;">{{ $r['orders'] }} Item</td>
                    <td style="padding: 16px; font-weight: 700; color: #111;">Rp {{ number_format($r['revenue'], 0, ',', '.') }}</td>
                    <td style="padding: 16px; text-align: right;">
                        <span style="font-size: 12px; font-weight: 700; {{ $r['status'] == 'Meningkat' ? 'color: #2ecc71;' : ($r['status'] == 'Menurun' ? 'color: #e74c3c;' : 'color: #f39c12;') }}">
                            {{ $r['status'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 40px; text-align: center; color: #999;">Belum ada data transaksi yang masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .report-card { background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
    .admin-card { background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.03); }
</style>
@endsection
