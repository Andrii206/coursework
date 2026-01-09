<?php

namespace App\Http\Controllers\Client\Book;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EditController extends Controller
{
    public function __invoke(Book $book)
    {
        if ($book->user_id !== Auth::id()) {
            abort(403, 'Доступ заборонено'); 
        }
        $book->load('tags');
        $tags = Tag::all();
        $authors = Author::all();
        $categories = Category::all();
        return Inertia::render('Client/Edit', [ 
            'book' => $book,
            'tags' => $tags,
            'authors' => $authors,
            'categories' => $categories,
            'currentTagIds' => $book->tags->pluck('id'), 
        ]);
    }
}