<?php

namespace App\Domain\Services;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Domain\Contracts\SellerRepositoryInterface;
use App\Domain\Entity\Sale;
use App\Domain\Entity\Seller;
use App\Exceptions\EntityNotFoundException;
use DateTime;

class SaleService
{
    public function __construct(private SaleRepositoryInterface $saleRepository, private SellerRepositoryInterface $sellerRepository) {}

    /** @return Sale[] */
    public function getAllSales(): array
    {
        return $this->saleRepository->all();
    }

    /** @throws EntityNotFoundException se a venda não existir  */
    public function getSale(int $id): Sale
    {
        $sale = $this->saleRepository->findById($id);

        if (is_null($sale)) {
            throw new EntityNotFoundException('Venda não encontrada.');
        }

        return $sale;
    }

    public function createSale(float $value, DateTime $date, int $sellerId): Sale
    {
        $this->validateSaleDate($date);

        $seller = $this->sellerRepository->findById($sellerId);

        $sale = new Sale($value, $date, $seller);

        $this->saleRepository->save($sale);

        return $sale;
    }

    /** @throws \DomainException se a data da venda for anterior à data atual */
    public function deleteSale(int $id): void
    {
        $sale = $this->saleRepository->findById($id);

        if (is_null($sale)) {
            return;
        }

        if ($sale->getDate()->diff(new DateTime())->days > 0) {
            throw new \DomainException('A venda não pode ser excluída porque já foi processada.');
        }

        $this->saleRepository->deleteById($sale->getId());
    }

    /** @return Sale[] */
    public function getAllSalesPerSeller(Seller $seller): array
    {
        return $this->saleRepository->getBySeller($seller);
    }

    /** @throws \DomainException se a data for diferente da data corrente */
    public function validateSaleDate(DateTime $date): void
    {
        if ($date->format('Y-m-d') != (new DateTime())->format('Y-m-d')) {
            throw new \DomainException('A data da venda deve ser igual à data atual.');
        }
    }
}
