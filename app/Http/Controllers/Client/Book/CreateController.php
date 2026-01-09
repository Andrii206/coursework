<?php

namespace App\Http\Controllers\Client\Book;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CreateController extends Controller
{
    public function __invoke()
    {
        $tags = Tag::all();
        $categories = Category::all();
        $authors = Author::all();

        return Inertia::render('Client/Create', ['categories' => $categories, 'authors' => $authors, 'tags' => $tags]);
    }
}
