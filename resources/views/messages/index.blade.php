@extends('layouts.app')

@section('title', 'Kotak Masuk - Belanja.ID')

@section('content')
<div class="inbox-page" style="padding: 40px 0; background: #f8fafc; min-height: 80vh;">
    <div class="container">
        <div class="inbox-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: 800; color: #0f172a;">Kotak Masuk</h1>
            <p style="color: #64748b;">Kelola pesan dan pertanyaan Anda kepada tim kami.</p>
        </div>

        <div class="inbox-container" style="background: #fff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; overflow: hidden;">
            @forelse($conversations as $conversation)
                <a href="{{ route('messages.show', $conversation->id) }}" class="conversation-item" style="display: flex; padding: 24px; border-bottom: 1px solid #f1f5f9; text-decoration: none; transition: background 0.2s; align-items: center; gap: 20px;">
                    <div class="status-icon" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: {{ $conversation->status === 'replied' ? '#f0fdf4' : '#f8fafc' }}; color: {{ $conversation->status === 'replied' ? '#16a34a' : '#94a3b8' }};">
                        <i class="fas {{ $conversation->status === 'replied' ? 'fa-reply' : 'fa-envelope' }}"></i>
                    </div>
                    <div class="conv-info" style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">{{ $conversation->subject }}</h3>
                            <span style="font-size: 12px; color: #94a3b8;">{{ $conversation->updated_at->diffForHumans() }}</span>
                        </div>
                        <p style="font-size: 14px; color: #64748b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 600px;">
                            {{ $conversation->latestMessage ? $conversation->latestMessage->body : 'Belum ada pesan.' }}
                        </p>
                    </div>
                    <div class="conv-status">
                        @if($conversation->messages()->where('sender_id', '!=', Auth::id())->where('is_read', false)->count() > 0)
                            <span style="background: #ef4444; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 10px;">BARU</span>
                        @endif
                        <i class="fas fa-chevron-right" style="color: #cbd5e1; margin-left: 10px;"></i>
                    </div>
                </a>
            @empty
                <div style="padding: 60px; text-align: center; color: #94a3b8;">
                    <i class="far fa-comments" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                    <p>Belum ada percakapan. <a href="{{ route('contact') }}" style="color: #e74c3c; font-weight: 600;">Klik di sini</a> untuk menghubungi kami.</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top: 20px;">
            {{ $conversations->links() }}
        </div>
    </div>
</div>

<style>
    .conversation-item:hover {
        background: #fafbfc;
    }
    .conversation-item:last-child {
        border-bottom: none;
    }
</style>
@endsection
