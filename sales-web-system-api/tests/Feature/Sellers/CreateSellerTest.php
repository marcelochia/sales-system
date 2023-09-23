<?php

namespace Tests\Feature\Sellers;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateSellerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private string $apiUrl = '/api/sellers';

    /** @test */
    public function createSellerSuccessfully(): void
    {
        $payload = [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
        ];

        $response = $this->postJson($this->apiUrl, $payload);

        $response->assertStatus(201);
        $response->assertJson($payload);
    }

    /** @test */
    public function createSellerWithEmptyPayload(): void
    {
        $response = $this->postJson($this->apiUrl, []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email']);
    }

    /** @test */
    public function createSellerWithInvalidEmail(): void
    {
        $payload = [
            'name' => $this->faker->name(),
            'email' => 'invalid_email',
        ];

        $response = $this->postJson($this->apiUrl, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('email');
    }

    /** @test */
    public function createSellerWithDuplicateEmail(): void
    {
        $email = $this->faker->safeEmail();

        Seller::factory()->create(['email' => $email]);

        $payload = [
            'name' => $this->faker->name(),
            'email' => $email,
        ];

        $response = $this->postJson($this->apiUrl, $payload);

        $response->assertStatus(409);
        $response->assertJsonFragment(['error' => 'Email já está em uso para outro vendedor.']);
    }
}
