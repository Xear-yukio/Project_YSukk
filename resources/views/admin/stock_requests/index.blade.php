@extends('layouts.admin')

@section('title', 'Permintaan Stok - Belanja.ID')
@section('page_title', 'Permintaan Stok Produk')

@section('content')
@if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; font-weight: 500; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="admin-card">
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f8f9fa; color: #888; font-size: 12px; text-transform: uppercase;">
                    <th style="padding: 16px; font-weight: 700;">ID</th>
                    <th style="padding: 16px; font-weight: 700;">Produk</th>
                    <th style="padding: 16px; font-weight: 700;">Pemohon</th>
                    <th style="padding: 16px; font-weight: 700;">Jumlah</th>
                    <th style="padding: 16px; font-weight: 700;">Status</th>
                    <th style="padding: 16px; font-weight: 700;">Tanggal</th>
                    @if(Auth::user()->isAdmin())
                    <th style="padding: 16px; font-weight: 700; text-align: right;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($stockRequests as $request)
                <tr style="border-bottom: 1px solid #f8f9fa; font-size: 14px;">
                    <td style="padding: 16px; color: #888;">#REQ-{{ $request->id }}</td>
                    <td style="padding: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ $request->product->main_image }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                            <div>
                                <h4 style="font-weight: 600; margin: 0; color: #333;">{{ $request->product->name }}</h4>
                                <small style="color: #888;">Stok Saat Ini: {{ $request->product->stock }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px;">
                        <span style="font-weight: 500;">{{ $request->user->name }}</span>
                    </td>
                    <td style="padding: 16px; font-weight: 700; color: #2ecc71;">+{{ $request->quantity }}</td>
                    <td style="padding: 16px;">
                        @php
                            $statusColors = [
                                'pending' => ['bg' => '#fff7ed', 'text' => '#9a3412'],
                                'approved' => ['bg' => '#f0fdf4', 'text' => '#166534'],
                                'rejected' => ['bg' => '#fef2f2', 'text' => '#991b1b']
                            ];
                            $color = $statusColors[$request->status] ?? $statusColors['pending'];
                        @endphp
                        <span style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize;">
                            {{ $request->status }}
                        </span>
                    </td>
                    <td style="padding: 16px; color: #888;">{{ $request->created_at->format('d M Y') }}</td>
                    @if(Auth::user()->isAdmin())
                    <td style="padding: 16px; text-align: right;">
                        @if($request->status === 'pending')
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <form action="{{ route('admin.stock_requests.update', $request->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" style="background: #2ecc71; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">Terima</button>
                            </form>
                            <form action="{{ route('admin.stock_requests.update', $request->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" style="background: #e74c3c; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">Tolak</button>
                            </form>
                        </div>
                        @else
                        <span style="color: #ccc; font-size: 12px;">Processed</span>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: #999;">Belum ada permintaan stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 24px;">
        {{ $stockRequests->links() }}
    </div>
</div>

<style>
    .admin-card { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
</style>
@endsection
