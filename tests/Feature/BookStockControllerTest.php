<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookStock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookStockControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function 在庫数を取得できる(): void
    {
        $book = Book::factory()->create();
        BookStock::query()->insert([
            ['book_id' => $book->id, 'created_at' => now()],
            ['book_id' => $book->id, 'created_at' => now()],
            ['book_id' => $book->id, 'created_at' => now()],
        ]);

        $response = $this->get("/api/books/{$book->id}/stocks");

        $response->assertStatus(200);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->where('book_id', $book->id)
                ->where('quantity', 3)
        );
    }

    #[Test]
    public function 存在しない本の在庫を取得しようとすると404が返る(): void
    {
        $response = $this->get('/api/books/1/stocks');

        $response->assertStatus(404);
    }

    #[Test]
    public function 在庫を増やせる(): void
    {
        $book = Book::factory()->create();
        $this->assertDatabaseEmpty(BookStock::class);

        $response = $this->patch("/api/books/{$book->id}/stocks", [
            'quantity_change' => 3,
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseCount(BookStock::class, 3);
        $this->assertDatabaseHas(BookStock::class, ['book_id' => $book->id]);
    }

    #[Test]
    public function 在庫を減らせる(): void
    {
        $book = Book::factory()->create();
        BookStock::query()->insert([
            ['book_id' => $book->id, 'created_at' => now()],
            ['book_id' => $book->id, 'created_at' => now()],
            ['book_id' => $book->id, 'created_at' => now()],
        ]);

        $response = $this->patch("/api/books/{$book->id}/stocks", [
            'quantity_change' => -2,
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseCount(BookStock::class, 1);
    }

    #[Test]
    public function 在庫が足りない場合は400エラーが返る(): void
    {
        $book = Book::factory()->create();
        BookStock::query()->insert([
            ['book_id' => $book->id, 'created_at' => now()],
        ]);

        $response = $this->patch("/api/books/{$book->id}/stocks", [
            'quantity_change' => -2,
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'message' => '在庫が足りません',
        ]);
        $this->assertDatabaseCount(BookStock::class, 1);
    }

    #[Test]
    public function 存在しない本の在庫を更新しようとすると404が返る(): void
    {
        $response = $this->patch('/api/books/1/stocks', [
            'quantity_change' => 1,
        ]);

        $response->assertStatus(404);
    }
}
