<?php

namespace Tests\Feature\Sellers;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteSellerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private string $apiUrl = '/api/sellers/';

    /** @test */
    public function deleteSellerSuccessfully(): void
    {
        $seller = Seller::factory()->create();

        $response = $this->deleteJson($this->apiUrl . $seller->id);

        $response->assertStatus(200);
        $response->assertJson(['success' => 'Vendedor excluído.']);
    }

    /** @test */
    public function deleteSellerWithSales(): void
    {
        $sale = Sale::factory()->create();

        $response = $this->deleteJson($this->apiUrl . $sale->seller->id);

        $response->assertStatus(400);
        $response->assertJsonFragment(['error' => 'Não é possível excluir o vendedor porque tem vendas relacionadas.']);
    }
}
