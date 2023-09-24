<?php

namespace Tests\Unit;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Domain\Contracts\SellerRepositoryInterface;
use App\Domain\Services\SaleService;
use PHPUnit\Framework\TestCase;

class ValidateSaleDateTest extends TestCase
{
    private object $saleRepository;
    private object $sellerRepository;
    private SaleService $saleService;

    public function setUp(): void
    {
        $this->saleRepository = $this->createMock(SaleRepositoryInterface::class);
        $this->sellerRepository = $this->createMock(SellerRepositoryInterface::class);

        $this->saleService = new SaleService($this->saleRepository, $this->sellerRepository);
    }

    /** @test */
    public function dateIsValid(): void
    {
        $result = $this->saleService->validateSaleDate(new \DateTime());

        $this->assertNull($result);
    }

    /** @test */
    public function shouldBeAValidDate(): void
    {
        $this->expectException(\DomainException::class);
        $this->saleService->validateSaleDate(new \DateTime('yesterday'));
    }
}