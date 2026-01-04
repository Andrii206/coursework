<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Відображає список всіх чатів користувача (зліва)
     */
    public function index()
    {
        $userId = Auth::id();
        
        
        
        $chats = Chat::where('sender_id', $userId)
            ->orWhere('recipient_id', $userId)
            ->with(['sender', 'recipient', 'messages' => function($query) {
                $query->latest()->first(); 
            }])
            ->get();

        return view('chat.index', compact('chats'));
    }

    /**
     * Основна функція SHOW: Відкриває конкретний чат
     */
    public function show(Chat $chat)
    {
        $userId = Auth::id();

        
        
        abort_unless($chat->sender_id === $userId || $chat->recipient_id === $userId, 403);

        
        $messages = $chat->messages()
            ->with('sender') 
            ->oldest() 
            ->get();

        
        
        
        
        $interlocutor = ($chat->sender_id === $userId) ? $chat->recipient : $chat->sender;

        return view('chat.show', compact('chat', 'messages', 'interlocutor'));
    }

    /**
     * Створює або знаходить існуючий чат
     */
    public function checkOrCreate(Request $request)
    {
        $currentUserId = Auth::id();
        $recipientId = $request->recipient_id;

        
        $chat = Chat::where(function($q) use ($currentUserId, $recipientId) {
            $q->where('sender_id', $currentUserId)->where('recipient_id', $recipientId);
        })->orWhere(function($q) use ($currentUserId, $recipientId) {
            $q->where('sender_id', $recipientId)->where('recipient_id', $currentUserId);
        })->first();

        if (!$chat) {
            $chat = Chat::create([
                'sender_id' => $currentUserId,
                'recipient_id' => $recipientId,
                'blocked' => false
            ]);
        }

        return redirect()->route('chats.show', $chat->id);
    }

    /**
     * Блокування / Розблокування чату
     */
    public function toggleBlock(Chat $chat)
    {
        $userId = Auth::id();
        
        
        abort_unless($chat->sender_id === $userId || $chat->recipient_id === $userId, 403);

        
        $chat->update([
            'blocked' => !$chat->blocked
        ]);

        $status = $chat->blocked ? 'заблоковано' : 'розблоковано';
        
        return back()->with('success', "Чат {$status}.");
    }
}