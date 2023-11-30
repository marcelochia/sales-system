<?php

namespace Tests\Feature\Sellers;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetSellerTest extends TestCase
{
    use RefreshDatabase;

    private string $apiUrl = '/api/sellers/';

    /** @test */
    public function canSellerSuccesfully(): void
    {
        $seller = Seller::factory()->create();

        $response = $this->get($this->apiUrl . $seller->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $seller->id,
            'name' => $seller->name,
            'email' => $seller->email,
            'sales' => []
        ]);
    }

    /** @test */
    public function mustReturnNotFoundIfNoSellers(): void
    {
        $response = $this->get($this->apiUrl . '100');

        $response->assertStatus(404);
        $response->assertJsonFragment(['error' => 'Vendedor não encontrado.']);
    }
}
