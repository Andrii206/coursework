<?php

namespace App\Http\Controllers\Admin\Book;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Author;

use App\Models\Tag;

class CreateController extends Controller
{
    protected $tags;
    protected $authors;
    protected $categories;

    public function __construct()
    {
        $this->tags = Tag::all();
        $this->authors = Author::all();
        $this->categories = Category::all();
    }

    public function admin() 
    {
        return view('admin.book.create', [
            'tags' => $this->tags,
            'authors' => $this->authors,
            'categories' => $this->categories
        ]);
    }
    public function client() 
    {
        return view('admin.book.create', [
            'tags' => $this->tags,
            'authors' => $this->authors,
            'categories' => $this->categories
        ]);
    }
}
