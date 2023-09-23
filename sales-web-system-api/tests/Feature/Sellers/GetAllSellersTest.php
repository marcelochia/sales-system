<?php

namespace Tests\Feature\Sellers;

use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetAllSellersTest extends TestCase
{
    use RefreshDatabase;

    private string $apiUrl = '/api/sellers';

    /** @test */
    public function canGetAllSellersSuccesfully(): void
    {
        Seller::factory(5)->create();

        $response = $this->get($this->apiUrl);

        $response->assertStatus(200);
        $response->assertJsonCount(5);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'email'
            ],
        ]);
    }

    /** @test */
    public function mustReturnEmptyResultIfNoSellers(): void
    {
        $response = $this->get($this->apiUrl);

        $this->assertEmpty($response->json());
    }
}
