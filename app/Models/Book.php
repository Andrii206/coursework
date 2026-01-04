<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'books';
    protected $guarded = false; 

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
     public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function author(){
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    // public function getImageUrlAttribute(){
    //     return url('storage/' . $this->preview_image);
    // }
    public function getImageUrlAttribute(){
        return  $this->preview_image;
    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'book_tags', 'book_id', 'tag_id');
    }
    public function bookImages(){
        return $this->hasMany(BookImage::class, 'book_id', 'id');
    }
}
