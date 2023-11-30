<?php

namespace Tests\Unit;

use App\Domain\Services\SellerService;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidateUniqueEmailTest extends TestCase
{
    use RefreshDatabase;

    private SellerService $sellerService;

    public function setUp(): void
    {
        parent::setUp();

        $this->sellerService = new SellerService();
    }

    /** @test */
    public function emailNotExists(): void
    {
        $seller = new Seller([
            'name' => 'Vendedor Teste',
            'email' => 'vendedor_teste@empresa.com.br'
        ]);

        $result = $this->sellerService->validateEmail($seller, 'vendedor_teste@empresa.com.br');

        $this->assertNull($result);
    }

    /** @test */
    public function emailExists(): void
    {
        Seller::factory()->create(['email' => 'vendedor_a@empresa.com.br']);

        $sellerB = new Seller([
            'name' => 'Vendedor B',
            'email' => 'vendedor_a@empresa.com.br'
        ]);

        $this->expectException(\DomainException::class);
        $this->sellerService->validateEmail($sellerB, 'vendedor_a@empresa.com.br');
    }
}
