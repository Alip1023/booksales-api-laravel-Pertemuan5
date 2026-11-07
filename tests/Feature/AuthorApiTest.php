<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AuthorApiTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_author_can_be_created_listed_updated_and_deleted(): void
    {
        $this->postJson('/api/authors', [
            'name' => 'Andrea Hirata',
            'email' => 'andrea@example.test',
            'bio' => 'Indonesian author',
        ])->assertCreated()
            ->assertJsonPath('data.name', 'Andrea Hirata');

        $author = Author::firstOrFail();
        $this->assertModelExists($author);

        $this->getJson('/api/authors')
            ->assertOk()
            ->assertJsonPath('data.0.id', $author->id);

        $this->getJson("/api/authors/{$author->id}")
            ->assertOk()
            ->assertJsonPath('data.email', 'andrea@example.test');

        $this->patchJson("/api/authors/{$author->id}", [
            'name' => 'Andrea Updated',
        ])->assertOk()
            ->assertJsonPath('data.name', 'Andrea Updated');

        $this->deleteJson("/api/authors/{$author->id}")
            ->assertNoContent();

        $this->assertModelMissing($author);
    }

    public function test_author_validation_errors_are_returned_as_json(): void
    {
        $this->postJson('/api/authors', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email']);

        $this->assertDatabaseCount('authors', 0);
    }
}
