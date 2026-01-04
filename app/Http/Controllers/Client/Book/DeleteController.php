<?php

namespace App\Http\Controllers\Client\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{
    public function __invoke(Book $book)
    {
        if ($book->user_id == Auth::id()) {
            $book->delete();
        }
        return redirect()->back();
    }
}
