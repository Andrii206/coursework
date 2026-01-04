<?php

namespace App\Http\Controllers\Admin\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\UpdateRequest;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;


class UpdateController extends Controller
{
    public function __invoke(Book $book, UpdateRequest $request)
    {
        $data = $request->validated();
        $data['preview_image'] = Storage::disk('public')->put('/images', $data['preview_image']);
        
        

        if(isset($data['tags']))
        {
            $book->tags()->sync($data['tags']);
            unset($data['tags']);
        }
    

        $currentImageCount = $book->book_images->count();

        foreach ($data['book_images'] as $bookImage) {
            if ($currentImageCount > 3) {
                continue;
            }
            Storage::disk('public')->put('/images', $bookImage);
            $currentImageCount++; // Збільшуємо лічильник зображень для даного продукту
           
        }
        unset($data['book_images']);

        $book->update($data);
    
        
    
        return redirect()->route('book.show', $book->id);
    }
    
}
