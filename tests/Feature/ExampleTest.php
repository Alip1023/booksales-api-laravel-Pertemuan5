<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_unknown_api_routes_return_json_errors(): void
    {
        $response = $this->get('/api/not-found');

        $response->assertNotFound()
            ->assertJsonStructure(['message']);
    }
}
