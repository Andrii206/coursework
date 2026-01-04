<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return \App\Models\Chat::where('id', $chatId)
        ->where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->orWhere('recipient_id', $user->id);
        })->exists();
});
