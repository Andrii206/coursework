<?php

namespace App\Http\Controllers\Client\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->withErrors(['msg' => 'Ви не можете залишити відгук самому собі.']);
        }
        
        $data = $request->validate([
            'text' => 'required|string|min:5',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        
        Review::create([
            'text' => $data['text'],
            'rating' => $data['rating'],
            'sender_id' => Auth::id(), 
            'recipent_id' => $user->id,  
            'is_published' => 1,
        ]);

        return redirect()->back()->with('success', 'Ваш відгук додано!');
    }
}
