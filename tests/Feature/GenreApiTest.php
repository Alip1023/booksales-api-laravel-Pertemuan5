<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class GenreApiTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_genre_can_be_created_listed_updated_and_deleted(): void
    {
        $this->postJson('/api/genres', [
            'name' => 'Fantasy',
            'description' => 'Stories with imaginary worlds.',
        ])->assertCreated()
            ->assertJsonPath('data.name', 'Fantasy');

        $genre = Genre::where('name', 'Fantasy')->firstOrFail();
        $this->assertModelExists($genre);

        $this->getJson('/api/genres')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $genre->id,
                'name' => 'Fantasy',
            ]);

        $this->getJson("/api/genres/{$genre->id}")
            ->assertOk()
            ->assertJsonPath('data.name', 'Fantasy');

        $this->patchJson("/api/genres/{$genre->id}", [
            'name' => 'Fantasy Adventure',
        ])->assertOk()
            ->assertJsonPath('data.name', 'Fantasy Adventure');

        $author = Author::create([
            'name' => 'Andrea Hirata',
            'email' => 'andrea@example.test',
        ]);
        $book = Book::create([
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'Laskar Pelangi',
            'price' => 85000,
        ]);

        $this->getJson("/api/genres/{$genre->id}")
            ->assertOk()
            ->assertJsonPath('data.books.0.id', $book->id);

        $this->deleteJson("/api/genres/{$genre->id}")
            ->assertStatus(409)
            ->assertJsonPath('message', 'Genre cannot be deleted while books are assigned to it.');

        $this->assertModelExists($genre);

        $book->delete();

        $this->deleteJson("/api/genres/{$genre->id}")
            ->assertNoContent();

        $this->assertModelMissing($genre);
    }

    public function test_genre_validation_errors_are_returned_as_json(): void
    {
        $this->postJson('/api/genres', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);

        $this->assertDatabaseCount('genres', 1);
    }
}
