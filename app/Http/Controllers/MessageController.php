<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * User Inbox Index
     */
    public function index()
    {
        $conversations = Conversation::where('user_id', Auth::id())
            ->with(['latestMessage', 'messages'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show Conversation Thread
     */
    public function show(Conversation $conversation)
    {
        // Ensure user owns this conversation
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }

        // Mark messages from others as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        return view('messages.show', compact('conversation', 'messages'));
    }

    /**
     * Store new conversation from Contact Page
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengirim pesan.');
        }

        $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $conversation = Conversation::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject ?? 'Pertanyaan dari Kontak',
            'status' => 'open',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('messages.show', $conversation->id)
            ->with('success', 'Pesan Anda telah terkirim! Tim kami akan segera membalas.');
    }

    /**
     * Reply to existing conversation
     */
    public function reply(Request $request, Conversation $conversation)
    {
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->message,
            'is_read' => false,
        ]);

        $conversation->update(['status' => 'open', 'updated_at' => now()]);

        return back()->with('success', 'Balasan terkirim.');
    }
}
