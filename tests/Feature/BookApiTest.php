<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $genre = Genre::create(['name' => 'Fantasy']);
        $updatedGenre = Genre::create(['name' => 'Drama']);

        $this->postJson('/api/books', [
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'Laskar Pelangi',
            'price' => 85000,
            'stock' => 12,
        ])->assertCreated()
            ->assertJsonPath('data.title', 'Laskar Pelangi')
            ->assertJsonPath('data.author.id', $author->id)
            ->assertJsonPath('data.genre.name', 'Fantasy');

        $book = Book::firstOrFail();
        $this->assertModelExists($book);

        $this->getJson('/api/books')
            ->assertOk()
            ->assertJsonPath('data.0.id', $book->id)
            ->assertJsonPath('data.0.genre.id', $genre->id);

        $this->getJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('data.title', 'Laskar Pelangi');

        $this->patchJson("/api/books/{$book->id}", [
            'stock' => 20,
            'genre_id' => $updatedGenre->id,
        ])->assertOk()
            ->assertJsonPath('data.stock', 20)
            ->assertJsonPath('data.genre.id', $updatedGenre->id);

        $this->deleteJson("/api/books/{$book->id}")
            ->assertNoContent();

        $this->assertModelMissing($book);
    }

    public function test_book_validation_errors_are_returned_as_json(): void
    {
        $this->postJson('/api/books', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['author_id', 'genre_id', 'title', 'price']);

        $this->assertDatabaseCount('books', 0);
    }

    public function test_updating_a_book_cover_replaces_the_old_file_and_destroy_removes_the_new_file(): void
    {
        Storage::fake('public');

        $author = Author::create([
            'name' => 'Andrea Hirata',
            'email' => 'andrea@example.test',
        ]);
        $genre = Genre::create(['name' => 'Fantasy']);

        $created = $this->post('/api/books', [
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'Laskar Pelangi',
            'price' => 85000,
            'cover_photo' => UploadedFile::fake()->createWithContent(
                'old-cover.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII='),
            ),
        ], ['Accept' => 'application/json'])
            ->assertCreated();

        $bookId = $created->json('data.id');
        $oldCoverPath = $created->json('data.cover_photo');
        Storage::disk('public')->assertExists($oldCoverPath);

        $updated = $this->post("/api/books/{$bookId}", [
            '_method' => 'PATCH',
            'cover_photo' => UploadedFile::fake()->createWithContent(
                'new-cover.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASsJTYQAAAAASUVORK5CYII='),
            ),
        ], ['Accept' => 'application/json'])
            ->assertOk();

        $newCoverPath = $updated->json('data.cover_photo');
        $this->assertNotSame($oldCoverPath, $newCoverPath);
        Storage::disk('public')->assertMissing($oldCoverPath);
        Storage::disk('public')->assertExists($newCoverPath);
        $this->assertDatabaseHas('books', [
            'id' => $bookId,
            'cover_photo' => $newCoverPath,
        ]);

        $this->deleteJson("/api/books/{$bookId}")
            ->assertNoContent();

        Storage::disk('public')->assertMissing($newCoverPath);
        $this->assertDatabaseMissing('books', ['id' => $bookId]);
    }

    public function test_book_cover_upload_rejects_non_images(): void
    {
        Storage::fake('public');

        $author = Author::create([
            'name' => 'Andrea Hirata',
            'email' => 'andrea@example.test',
        ]);
        $genre = Genre::create(['name' => 'Fantasy']);

        $this->post('/api/books', [
            'author_id' => $author->id,
            'genre_id' => $genre->id,
            'title' => 'Laskar Pelangi',
            'price' => 85000,
            'cover_photo' => UploadedFile::fake()->createWithContent('notes.txt', 'not an image'),
        ], ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cover_photo']);

        $this->assertDatabaseCount('books', 0);
    }
}
