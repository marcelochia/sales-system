<?php

namespace Tests\Feature\Sellers;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetSalesPerSellerTest extends TestCase
{
    use RefreshDatabase;

    private string $apiUrl = '/api/sellers/{id}/sales';

    /** @test */
    public function canGetAllSellersSuccesfully(): void
    {
        $seller = Seller::factory()->create();
        Sale::factory(5)->create(['seller_id' => $seller->id]);

        $endpoint = $this->getApiUrl($seller->id);

        $response = $this->get($endpoint);

        $response->assertStatus(200);
        $response->assertJsonCount(5);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'value',
                'date',
                'seller_id',
            ],
        ]);
    }

    /** @test */
    public function mustReturnNotFoundIfNoSeller(): void
    {
        $endpoint = $this->getApiUrl(100);

        $response = $this->get($endpoint);

        $response->assertStatus(404);
        $response->assertJsonFragment(['error' => 'Vendedor não encontrado.']);
    }

    /** @test */
    public function mustReturnEmptyResultIfNoSales(): void
    {
        $seller = Seller::factory()->create();
        $endpoint = $this->getApiUrl($seller->id);

        $response = $this->get($endpoint);

        $this->assertEmpty($response->json());
    }

    private function getApiUrl(int $sellerId): string
    {
        $pattern = '/\{id\}/';
        $replacement = $sellerId;
        return preg_replace($pattern, $replacement, $this->apiUrl);
    }
}
