<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\ProductImage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'note' => $this->faker->text,
            'preview_image' => 'images/' . rand(1, 7) . '.jpg',
            'is_published' => $this->faker->boolean(80), 
            'category_id' => Category::get()->random()->id,
            'user_id' => User::get()->random()->id,
            'author_id' => Author::get()->random()->id,
        ];
    }
    /**
     * Indicate that the product has product images.
     *
     * @param  int  $count
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withProductImages($count = 3)
    {
        return $this->hasAttached(BookImage::factory()->count(3), []);
    }
}
