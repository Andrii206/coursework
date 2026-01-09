<?php

namespace App\Http\Controllers;

use App\Http\Filters\BooksFilter;
use App\Http\Requests\FilterRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MainController extends Controller
{
    public function __invoke(FilterRequest $request)
    {
        $data = $request->validated();
        $tags = Tag::withCount('books')->orderBy('books_count', 'desc')->take(50)->get();
        $categories = Category::withCount('books')->orderBy('books_count', 'desc')->take(10)->get();
        $authors = Author::withCount('books')->orderBy('books_count', 'desc')->take(4)->get();
        $filter = app()->make(BooksFilter::class, ['queryParams' => array_filter($data)]);
        $books = Book::with('author')->filter($filter)->paginate(36);
        return Inertia::render('Index', compact('books', 'tags', 'categories', 'authors'));
    }
}
