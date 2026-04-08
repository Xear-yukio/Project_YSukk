@extends('layouts.app')

@section('title', $conversation->subject . ' - Belanja.ID')

@section('content')
<div class="thread-page" style="padding: 40px 0; background: #f1f5f9; min-height: 100vh;">
    <div class="container" style="max-width: 800px;">
        <div class="thread-nav" style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ route('messages.index') }}" style="color: #64748b; font-weight: 600; font-size: 14px; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i> Kembali ke Inbox
            </a>
            <span style="font-size: 12px; color: #94a3b8; background: #fff; padding: 4px 12px; border-radius: 20px; border: 1px solid #e2e8f0; font-weight: 700; text-transform: uppercase;">
                STATUS: {{ $conversation->status }}
            </span>
        </div>

        <div class="thread-card" style="background: #fff; border-radius: 24px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; display: flex; flex-direction: column; overflow: hidden; height: 75vh;">
            <div class="thread-header" style="padding: 24px 32px; border-bottom: 1px solid #f1f5f9; background: #fff; z-index: 10;">
                <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;">{{ $conversation->subject }}</h1>
                <p style="font-size: 13px; color: #94a3b8; margin: 4px 0 0;">ID Percakapan: #{{ str_pad($conversation->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="chat-container" id="chat-container" style="flex: 1; overflow-y: auto; padding: 32px; display: flex; flex-direction: column; gap: 20px; background: #fdfdfd;">
                @foreach($messages as $message)
                    <div class="message-wrapper {{ $message->sender_id === Auth::id() ? 'message-sent' : 'message-received' }}" style="display: flex; flex-direction: column; {{ $message->sender_id === Auth::id() ? 'align-items: flex-end;' : 'align-items: flex-start;' }}">
                        <div class="message-bubble" style="max-width: 80%; padding: 14px 20px; border-radius: 20px; font-size: 14.5px; line-height: 1.5; {{ $message->sender_id === Auth::id() ? 'background: #e74c3c; color: #fff; border-bottom-right-radius: 4px;' : 'background: #f1f5f9; color: #334155; border-bottom-left-radius: 4px; border: 1px solid #e2e8f0;' }}">
                            {{ $message->body }}
                        </div>
                        <span style="font-size: 11px; color: #94a3b8; margin-top: 6px; padding: 0 4px;">{{ $message->created_at->format('H:i') }} • {{ $message->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>

            <div class="reply-box" style="padding: 24px; border-top: 1px solid #f1f5f9; background: #fff;">
                <form action="{{ route('messages.reply', $conversation->id) }}" method="POST">
                    @csrf
                    <div style="display: flex; gap: 12px;">
                        <textarea name="message" class="reply-input" placeholder="Tulis balasan Anda di sini..." required style="flex: 1; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 16px; padding: 12px 18px; font-size: 14px; outline: none; transition: all 0.2s; min-height: 50px; max-height: 150px; resize: none; font-family: inherit;"></textarea>
                        <button type="submit" class="btn-send" style="background: #e74c3c; color: #fff; border: none; width: 48px; height: 48px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 18px; cursor: pointer; transition: all 0.3s; flex-shrink: 0;">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        var chatContainer = document.getElementById('chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    };
</script>

<style>
    .reply-input:focus {
        border-color: #e74c3c;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
    }
    .btn-send:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }
</style>
@endsection
