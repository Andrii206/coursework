<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowBookController extends Controller
{
    public function __invoke(Book $book)
    {   
        $book->load(['category', 'author', 'tags', 'user']);
        return Inertia::render('Show/Book', compact('book'));
    }
}
