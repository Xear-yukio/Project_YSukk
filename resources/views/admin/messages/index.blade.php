@extends('layouts.admin')

@section('title', 'Pesan Customer - Admin')

@section('content')
<div class="messages-container" style="font-family: 'Inter', sans-serif;">
    <!-- Stats Cards Grid -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border-left: 5px solid #3b82f6; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="display: block; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Total Pesan</span>
                <span style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ $stats['total'] }}</span>
            </div>
            <div style="width: 48px; height: 48px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #3b82f6;">
                <i class="fas fa-comments" style="font-size: 20px;"></i>
            </div>
        </div>

        <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border-left: 5px solid #e74c3c; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="display: block; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Menunggu Balasan (OPEN)</span>
                <span style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ $stats['open'] }}</span>
            </div>
            <div style="width: 48px; height: 48px; background: #fef2f2; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #e74c3c;">
                <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
            </div>
        </div>

        <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border-left: 5px solid #10b981; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="display: block; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Sudah Dibalas</span>
                <span style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ $stats['replied'] }}</span>
            </div>
            <div style="width: 48px; height: 48px; background: #f0fdf4; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #10b981;">
                <i class="fas fa-check-double" style="font-size: 20px;"></i>
            </div>
        </div>

        <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border-left: 5px solid #8b5cf6; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <span style="display: block; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Belum Dibaca</span>
                <span style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ $stats['unread'] }}</span>
            </div>
            <div style="width: 48px; height: 48px; background: #f5f3ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #8b5cf6;">
                <i class="fas fa-envelope" style="font-size: 20px;"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="admin-card" style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        <div class="card-header" style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; font-size: 18px; font-weight: 800; color: #0f172a;">
                <i class="fas fa-inbox" style="margin-right: 10px; color: #e74c3c;"></i> Daftar Percakapan
            </h5>
            <div style="display: flex; gap: 10px;">
                <button style="padding: 8px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-weight: 600; color: #64748b; cursor: pointer;">
                    <i class="fas fa-filter" style="margin-right: 6px;"></i> Semua Status
                </button>
            </div>
        </div>
        
        <div class="table-container" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left; border-bottom: 2px solid #f1f5f9; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">
                        <th style="padding: 16px 24px; font-weight: 700;">Customer</th>
                        <th style="padding: 16px 24px; font-weight: 700;">Subjek & Pesan Terakhir</th>
                        <th style="padding: 16px 24px; font-weight: 700; text-align: center;">Status</th>
                        <th style="padding: 16px 24px; font-weight: 700; text-align: center;">Update Terakhir</th>
                        <th style="padding: 16px 24px; font-weight: 700; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conversations as $conversation)
                        @php
                            $hasUnread = $conversation->messages()->where('sender_id', '!=', Auth::id())->where('is_read', false)->count() > 0;
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s; cursor: pointer; {{ $hasUnread ? 'background: #fef2f2;' : '' }}" 
                            onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='{{ $hasUnread ? '#fef2f2' : 'transparent' }}'"
                            onclick="window.location='{{ route('admin.messages.show', $conversation->id) }}'">
                            <td style="padding: 20px 24px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #e74c3c; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px;">
                                        {{ substr($conversation->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 14px;">{{ $conversation->user->name }}</div>
                                        <div style="font-size: 12px; color: #94a3b8;">{{ $conversation->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 20px 24px;">
                                <div style="font-weight: 700; color: #334155; font-size: 14px; margin-bottom: 4px;">{{ $conversation->subject }}</div>
                                <div style="font-size: 12px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">
                                    {{ $conversation->latestMessage ? $conversation->latestMessage->body : 'Belum ada pesan.' }}
                                </div>
                            </td>
                            <td style="padding: 20px 24px; text-align: center;">
                                @php
                                    $statusStyle = match($conversation->status) {
                                        'open' => 'background: #fee2e2; color: #991b1b;',
                                        'replied' => 'background: #dcfce7; color: #15803d;',
                                        default => 'background: #f1f5f9; color: #475569;'
                                    };
                                    $statusText = match($conversation->status) {
                                        'open' => 'MENUNGGU',
                                        'replied' => 'DIBALAS',
                                        default => 'SELESAI'
                                    };
                                @endphp
                                <span style="{{ $statusStyle }} font-size: 10px; font-weight: 800; padding: 4px 10px; border-radius: 6px; letter-spacing: 0.5px;">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td style="padding: 20px 24px; text-align: center;">
                                <div style="font-size: 13px; font-weight: 600; color: #0f172a;">{{ $conversation->updated_at->diffForHumans() }}</div>
                                <div style="font-size: 11px; color: #94a3b8;">{{ $conversation->updated_at->format('d M, H:i') }}</div>
                            </td>
                            <td style="padding: 20px 24px; text-align: center;">
                                <a href="{{ route('admin.messages.show', $conversation->id) }}" style="display: inline-flex; width: 36px; height: 36px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; align-items: center; justify-content: center; color: #e74c3c; transition: all 0.2s;" onmouseover="this.style.background='#e74c3c'; this.style.color='#fff'" onmouseout="this.style.background='#fff'; this.style.color='#e74c3c'">
                                    <i class="fas fa-comment-dots"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 60px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 16px; opacity: 0.2;"></i>
                                <p style="font-weight: 600;">Belum ada percakapan masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($conversations->hasPages())
        <div style="padding: 24px; border-top: 1px solid #f1f5f9;">
            {{ $conversations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
