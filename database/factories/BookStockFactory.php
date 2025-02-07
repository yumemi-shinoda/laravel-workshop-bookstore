<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookStock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookStock>
 */
class BookStockFactory extends Factory
{
    protected $model = BookStock::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::query()->inRandomOrder()->first()->id,
        ];
    }
}
