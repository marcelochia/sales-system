<?php

namespace Tests\Feature\Sales;

use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteSaleTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private string $apiUrl = '/api/sales/';

    /** @test */
    public function deleteSaleSuccessfully(): void
    {
        $sale = Sale::factory()->create(['date' => date('Y-m-d')]);

        $response = $this->deleteJson($this->apiUrl . $sale->id);

        $response->assertStatus(200);
        $response->assertJson(['success' => 'Venda excluída.']);
    }

    /** @test */
    public function deleteSaleAlreadyProcessed(): void
    {
        $sale = Sale::factory()->create(['date' => $this->faker->date(max: 'yesterday')]);

        $response = $this->deleteJson($this->apiUrl . $sale->id);

        $response->assertStatus(400);
        $response->assertJsonFragment(['error' => 'A venda não pode ser excluída porque já foi processada.']);
    }
}
