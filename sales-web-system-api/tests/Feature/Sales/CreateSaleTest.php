<?php

namespace Tests\Feature\Sales;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CreateSaleTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private string $apiUrl = '/api/sales';

    /** @test */
    public function itCreatesASale(): void
    {
        $payload = [
            'value' => $this->faker->randomFloat(2, 0, 1_000_000),
            'date' => date('Y-m-d'),
            'seller_id' => Seller::factory()->create()->id,
        ];

        $response = $this->postJson($this->apiUrl, $payload);

        $response->assertStatus(201);
    }

    /** @test */
    public function createsASaleWithADateDifferentFromToday(): void
    {
        $payload = [
            'value' => $this->faker->randomFloat(2, 0, 1_000_000),
            'date' => $this->faker->date('Y-m-d', 'yesterday'),
            'seller_id' => Seller::factory()->create()->id,
        ];

        $response = $this->postJson($this->apiUrl, $payload);

        $response->assertStatus(400);
        $response->assertJsonFragment(['error' => 'A data da venda deve ser igual à data atual.']);
    }

    /** @test */
    public function createSaleWithEmptyPayload(): void
    {
        $response = $this->postJson($this->apiUrl, []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['value', 'date', 'seller_id']);
    }

    /** @test */
    public function createSaleWithInvalidValue(): void
    {
        $payload = [
            'value' => 'invalid_value',
            'date' => $this->faker->date('Y-m-d'),
            'seller_id' => Seller::factory()->create()->id,
        ];

        $response = $this->postJson($this->apiUrl, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('value');
    }

    /** @test */
    public function createSaleWithInvalidDate(): void
    {
        $payload = [
            'value' => $this->faker->randomFloat(2, 0, 1000),
            'date' => 'invalid_date',
            'seller_id' => Seller::factory()->create()->id,
        ];

        $response = $this->postJson($this->apiUrl, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('date');
    }

    /** @test */
    public function createSaleWithInvalidSellerId(): void
    {
        $payload = [
            'value' => $this->faker->randomFloat(2, 0, 1000),
            'date' => $this->faker->date('Y-m-d'),
            'seller_id' => 999,
        ];

        $response = $this->postJson($this->apiUrl, $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('seller_id');
    }
}
