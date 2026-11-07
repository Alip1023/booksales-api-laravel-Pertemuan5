<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_book_can_be_created_listed_updated_and_deleted(): void
    {
        $author = Author::create([
            'name' => 'Andrea Hirata',
            'email' => 'andrea@example.test',
        ]);

        $this->postJson('/api/books', [
            'author_id' => $author->id,
            'title' => 'Laskar Pelangi',
            'price' => 85000,
            'stock' => 12,
        ])->assertCreated()
            ->assertJsonPath('data.title', 'Laskar Pelangi')
            ->assertJsonPath('data.author.id', $author->id);

        $book = Book::firstOrFail();
        $this->assertModelExists($book);

        $this->getJson('/api/books')
            ->assertOk()
            ->assertJsonPath('data.0.id', $book->id);

        $this->getJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('data.title', 'Laskar Pelangi');

        $this->patchJson("/api/books/{$book->id}", [
            'stock' => 20,
        ])->assertOk()
            ->assertJsonPath('data.stock', 20);

        $this->deleteJson("/api/books/{$book->id}")
            ->assertNoContent();

        $this->assertModelMissing($book);
    }

    public function test_book_validation_errors_are_returned_as_json(): void
    {
        $this->postJson('/api/books', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['author_id', 'title', 'price']);

        $this->assertDatabaseCount('books', 0);
    }
}
