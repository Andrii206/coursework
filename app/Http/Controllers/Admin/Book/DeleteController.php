<?php

namespace App\Http\Controllers\Admin\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function __invoke(Book $book)
    {
        $book->delete();
        return redirect()->route(route: 'book.index');
    }
}
