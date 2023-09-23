<?php

namespace Tests\Feature\Sellers;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateSellerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private string $apiUrl = '/api/sellers/';

    /** @test */
    public function updateSellerSuccessfully(): void
    {
        $seller = Seller::factory()->create();

        $payload = [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
        ];

        $response = $this->putJson($this->apiUrl . $seller->id, $payload);

        $response->assertStatus(200);
        $response->assertJson($payload);
    }

    /** @test */
    public function updateSellerWithEmptyPayload(): void
    {
        $seller = Seller::factory()->create();

        $response = $this->putJson($this->apiUrl . $seller->id, []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email']);
    }

    /** @test */
    public function updateSellerWithInvalidEmail(): void
    {
        $seller = Seller::factory()->create();

        $payload = [
            'name' => $seller->name,
            'email' => 'invalid_email',
        ];

        $response = $this->putJson($this->apiUrl . $seller->id, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('email');
    }

    /** @test */
    public function updateSellerWithDuplicateEmail(): void
    {
        $seller1 = Seller::factory()->create();
        $seller2 = Seller::factory()->create();

        $payload = [
            'name' => $seller1->name,
            'email' => $seller2->email,
        ];

        $response = $this->putJson($this->apiUrl . $seller1->id, $payload);

        $response->assertStatus(409);
        $response->assertJsonFragment(['error' => 'Email já está em uso para outro vendedor.']);
    }
}
