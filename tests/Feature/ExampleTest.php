<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $this->seed(\Database\Seeders\ServiceCategorySeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_public_pages_render(): void
    {
        $this->seed(\Database\Seeders\ServiceCategorySeeder::class);

        foreach (['/services', '/professionals', '/about', '/contact', '/how-it-works', '/privacy', '/terms', '/login', '/register', '/forgot-password'] as $path) {
            $this->get($path)->assertSuccessful();
        }
    }
}
