<?php

namespace Tests\Unit;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Domain\Contracts\SellerRepositoryInterface;
use App\Domain\Entity\Seller;
use App\Domain\Services\SellerService;
use App\Domain\ValueObjects\Email;
use PHPUnit\Framework\TestCase;

class ValidateUniqueEmailTest extends TestCase
{
    private object $sellerRepository;
    private object $saleRepository;
    private SellerService $sellerService;

    public function setUp(): void
    {
        $this->sellerRepository = $this->createMock(SellerRepositoryInterface::class);
        $this->saleRepository = $this->createMock(SaleRepositoryInterface::class);

        $this->sellerService = new SellerService($this->sellerRepository, $this->saleRepository);
    }

    /** @test */
    public function emailNotExists(): void
    {
        $sellerA = new Seller('Vendedor A', new Email('vendedor_a@empresa.com.br'), 1);
        $sellerB = new Seller('Vendedor B', new Email('vendedor_b@empresa.com.br'), 2);

        $this->sellerRepository->expects($this->once())
            ->method('findByEmail')
            ->with('vendedor_b@empresa.com.br')
            ->willReturn(null);

        $result = $this->sellerService->validateEmail($sellerB, 'vendedor_b@empresa.com.br');

        $this->assertNull($result);
    }

    /** @test */
    public function emailExists(): void
    {
        $sellerA = new Seller('Vendedor A', new Email('vendedor_a@empresa.com.br'), 1);
        $sellerB = new Seller('Vendedor B', new Email('vendedor_a@empresa.com.br'), 2);

        $this->sellerRepository->expects($this->once())
            ->method('findByEmail')
            ->with('vendedor_a@empresa.com.br')
            ->willReturn($sellerA);

        $this->expectException(\DomainException::class);
        $this->sellerService->validateEmail($sellerB, 'vendedor_a@empresa.com.br');
    }
}
