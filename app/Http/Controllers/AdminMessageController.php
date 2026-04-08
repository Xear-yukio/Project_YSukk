<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class AdminMessageController extends Controller
{
    /**
     * Admin/Staff Inbox Index
     */
    public function index()
    {
        $conversations = Conversation::with(['user', 'latestMessage'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => Conversation::count(),
            'open' => Conversation::where('status', 'open')->count(),
            'replied' => Conversation::where('status', 'replied')->count(),
            'unread' => Message::where('is_read', false)
                ->where('sender_id', '!=', Auth::id())
                ->count()
        ];

        return view('admin.messages.index', compact('conversations', 'stats'));
    }

    /**
     * Show Conversation Thread for Staff
     */
    public function show(Conversation $conversation)
    {
        // Mark user messages as read by staff
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        return view('admin.messages.show', compact('conversation', 'messages'));
    }

    /**
     * Staff Reply to Customer
     */
    public function reply(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->message,
            'is_read' => false,
        ]);

        $conversation->update(['status' => 'replied', 'updated_at' => now()]);

        return back()->with('success', 'Balasan dikirim ke customer.');
    }
}
