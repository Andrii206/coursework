<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Category::factory(10)->create();
        // Author::factory(10)->create();
        // User::factory(10)->create();
        $books = Book::factory(100)->create();
        // $tags = Tag::factory(10)->create();

        // foreach ($books as $book) {
        //     $tagIds = $tags->random(3)->pluck('id');
        //     $book->tags()->attach($tagIds);
        // }
    }
}
