<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';
    protected $guarded = false; 

     public function books()
    {
        return $this->hasMany(Book::class, 'author_id', 'id');
    }
}
