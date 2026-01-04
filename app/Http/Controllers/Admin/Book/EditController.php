<?php

namespace App\Http\Controllers\Admin\Book;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Book;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;


class EditController extends Controller
{
    public function __invoke(Book $book)
    {   
        $tags = Tag::all();
        $currentTagIds = old('tags', $book->tags->pluck('id')->toArray());
        $authors = Author::all();
        $categories = Category::all();
        return view('admin.book.edit', compact('book', 'tags', 'authors', 'categories', 'currentTagIds'));
    }
}
