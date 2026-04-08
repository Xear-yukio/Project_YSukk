@extends('layouts.admin')

@section('title', 'Percakapan dengan ' . $conversation->user->name)

@section('content')
<div class="conversation-layout" style="display: flex; gap: 30px; height: calc(100vh - 150px); font-family: 'Inter', sans-serif;">
    
    <!-- Main Chat Area (Left) -->
    <div class="chat-main" style="flex: 2; display: flex; flex-direction: column; background: #fff; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); overflow: hidden; border: 1px solid #f1f5f9;">
        
        <!-- Chat Header -->
        <div class="chat-header" style="padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #fff;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="{{ route('admin.messages') }}" style="width: 36px; height: 36px; border-radius: 50%; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #64748b; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;">{{ $conversation->subject }}</h3>
                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $conversation->status === 'open' ? '#ef4444' : '#10b981' }};"></span>
                        <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $conversation->status === 'open' ? 'Menunggu Balasan' : 'Sudah Dibalas' }}
                        </span>
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button style="padding: 8px 16px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 12px; font-weight: 700; color: #64748b; cursor: pointer;">
                    Tandai Selesai
                </button>
            </div>
        </div>

        <!-- Chat Body (Scrollable) -->
        <div id="admin-chat-container" style="flex: 1; overflow-y: auto; padding: 30px; background: #fdfdfd; display: flex; flex-direction: column; gap: 20px;">
            @foreach($messages as $message)
                @php $isMe = $message->sender_id === Auth::id(); @endphp
                <div style="display: flex; flex-direction: column; align-items: {{ $isMe ? 'flex-end' : 'flex-start' }};">
                    <div style="max-width: 70%; padding: 14px 18px; border-radius: 16px; font-size: 14px; line-height: 1.5; 
                        {{ $isMe ? 'background: #e74c3c; color: #fff; border-bottom-right-radius: 4px;' : 'background: #f1f5f9; color: #334155; border-bottom-left-radius: 4px; border: 1px solid #e2e8f0;' }}">
                        {{ $message->body }}
                    </div>
                    <div style="margin-top: 6px; display: flex; align-items: center; gap: 6px; opacity: 0.6;">
                        <small style="font-size: 10px; font-weight: 600; color: #94a3b8;">{{ $message->created_at->format('H:i') }} • {{ $message->created_at->diffForHumans() }}</small>
                        @if($isMe)
                            <i class="fas {{ $message->is_read ? 'fa-check-double text-info' : 'fa-check' }}" style="font-size: 10px;"></i>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reply Area -->
        <div class="chat-footer" style="padding: 24px; border-top: 1px solid #f1f5f9; background: #fff;">
            <form action="{{ route('admin.messages.reply', $conversation->id) }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px;">
                    <textarea name="message" placeholder="Tulis balasan Anda untuk {{ $conversation->user->name }}..." required 
                        style="width: 100%; min-height: 80px; border: none; background: transparent; outline: none; font-family: inherit; font-size: 14px; color: #334155; resize: none;"></textarea>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" style="background: #e74c3c; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s;">
                            <i class="fas fa-paper-plane"></i> Kirim Pesan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Info Sidebar (Right) -->
    <div class="chat-sidebar" style="flex: 0 0 320px; background: #fff; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); padding: 30px; border: 1px solid #f1f5f9; height: fit-content;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #fef2f2; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800; color: #e74c3c;">
                {{ substr($conversation->user->name, 0, 1) }}
            </div>
            <h4 style="margin: 0; font-size: 18px; font-weight: 800; color: #0f172a;">{{ $conversation->user->name }}</h4>
            <p style="margin: 4px 0 0; font-size: 13px; color: #94a3b8;">{{ $conversation->user->email }}</p>
            <div style="margin-top: 15px; display: flex; justify-content: center; gap: 8px;">
                <span style="background: #f1f5f9; color: #475569; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 20px; border: 1px solid #e2e8f0;">#USER-{{ $conversation->user->id }}</span>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 20px 0;">

        <div class="info-list">
            <div style="margin-bottom: 20px;">
                <span style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Informasi Customer</span>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between; font-size: 13px;">
                        <span style="color: #64748b;">Member Sejak:</span>
                        <span style="font-weight: 700; color: #0f172a;">{{ $conversation->user->created_at->format('d M Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px;">
                        <span style="color: #64748b;">Total Kontribusi:</span>
                        <span style="font-weight: 700; color: #e74c3c;">12 Pesanan</span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 0;">
                <span style="display: block; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Ringkasan Subjek</span>
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9; font-size: 13px; color: #475569; line-height: 1.6;">
                    Customer menanyakan perihal <strong>"{{ $conversation->subject }}"</strong>. 
                    Percakapan ini dimulai pada {{ $conversation->created_at->format('d M, H:i') }}.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        var chatContainer = document.getElementById('admin-chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    };
</script>
@endsection
