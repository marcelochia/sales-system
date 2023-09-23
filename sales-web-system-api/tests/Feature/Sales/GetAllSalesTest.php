<?php

namespace Tests\Feature\Sales;

use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAllSalesTest extends TestCase
{
    use RefreshDatabase;

    private string $apiUrl = '/api/sales';

    /** @test */
    public function canGetAllSalesSuccesfully(): void
    {
        Sale::factory(5)->create();

        $response = $this->get($this->apiUrl);

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
    public function mustReturnEmptyResultIfNoSales(): void
    {
        $response = $this->get($this->apiUrl);

        $this->assertEmpty($response->json());
    }
}
