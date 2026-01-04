<?php

namespace App\Http\Controllers\Client\Book;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditController extends Controller
{
    public function __invoke(Book $book)
    {
        if ($book->user_id == Auth::id()) {
            $tags = Tag::all();
            $currentTagIds = old('tags', $book->tags->pluck('id')->toArray());
            $authors = Author::all();
            $categories = Category::all();
            return view('client.edit', compact('book', 'tags', 'authors', 'categories', 'currentTagIds'));
        } else {
            return redirect()->back();
        }
    }
}
