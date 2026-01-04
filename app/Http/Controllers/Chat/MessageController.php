<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function store(Request $request, Chat $chat)
    {
        $userId = Auth::id();

       
        abort_unless($chat->sender_id === $userId || $chat->recipient_id === $userId, 403);

       
       
        if ($chat->blocked) {
            return response()->json([
                'message' => 'Цей чат заблоковано. Ви не можете надсилати повідомлення.'
            ], 403); 
           
        }

       
        $data = $request->validate([
            'text' => 'nullable|string|max:1000',
           
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,zip',
        ]);

       
        if (empty($data['text']) && empty($request->file('file'))) {
            return response()->json(['message' => 'Повідомлення не може бути пустим'], 422);
        }

       
        $filePath = null;
        
        if ($request->hasFile('file')) {
           
           
            $filePath = $request->file('file')->store('chat_files', 'public');
        }

       
        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $userId,
            'text' => $data['text'],
            'file' => $filePath,
            'review' => false,
        ]);

       
       
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'success', 
            'message' => $message,
           
            'file_url' => $filePath ? asset('storage/' . $filePath) : null
        ]);
    }
}