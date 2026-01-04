<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookImage extends Model
{
     use HasFactory;

    protected $table = 'book_images';
    protected $guarded = false;

    public function getImageUrlAttribute(){
        return $this -> file_path;
    }
    // public function getImageUrlAttribute(){
    //     return url('storage/' . $this -> file_path);
    // }
}
