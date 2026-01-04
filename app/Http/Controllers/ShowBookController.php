<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class ShowBookController extends Controller
{
    public function __invoke(Book $book)
    {   
        
        return view('show-book', compact('book'));
    }
}
