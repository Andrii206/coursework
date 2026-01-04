<?php

namespace App\Http\Controllers\Admin\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\Author\UpdateRequest;
use App\Models\Author;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Author $author, UpdateRequest $request)
    {
        $data = $request->validated();
        $author->update($data);
        return redirect()->route('category.show', $author->id);
    }
}
