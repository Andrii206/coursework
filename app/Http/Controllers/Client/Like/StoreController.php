<?php

namespace App\Http\Controllers\Client\Like;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {   
        $user = Auth::user();
        $bookId = $request->book_id;    
        $existingLike = Like::where('user_id', $user->id)->where('book_id', $bookId)->first();
        if ($existingLike) {
            $existingLike->delete();
            $message = 'Лайк видалено.';
        } else {
            Like::create([
                'user_id' => $user->id,
                'book_id' => $bookId,
            ]);
            $message = 'Лайк додано!';
        }
        return back()->with('success', $message);
    }
}