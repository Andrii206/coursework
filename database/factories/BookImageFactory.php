<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class BookImageFactory extends Factory
{   
    protected $model = BookImage::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::get()->random()->id,
            'file_path' => $this->faker->imageUrl(),
        ];
    }
}
