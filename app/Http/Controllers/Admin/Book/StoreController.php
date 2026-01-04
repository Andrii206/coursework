<?php

namespace App\Http\Controllers\Admin\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreRequest;
use App\Models\Book;
use App\Models\BookImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        if ($request->hasFile('preview_image')) {
            $data['preview_image'] = Storage::disk('public')->put('/images', $data['preview_image']);
        }

        $tagsIds = $data['tags'] ?? [];
        $bookImages = $data['book_images'] ?? [];

        unset($data['tags'], $data['book_images']);

        $book = Book::firstOrCreate($data);

        $book->tags()->attach($tagsIds);
        foreach ($bookImages as $image) {
            $filePath = Storage::disk('public')->put('/images', $image);
            BookImage::create([
                'book_id' => $book->id,
                'file_path' => $filePath,
            ]);
        }
        return redirect()->route('book.index');
    }
}
