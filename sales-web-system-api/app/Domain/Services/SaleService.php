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
    public function getAllSales(?string $query = null): array
    {
        return $this->saleRepository->findBy([
            'value' => $query,
            'date' => $query
        ]);
    }

    /** @throws EntityNotFoundException se a venda não existir  */
    public function getSale(string $id): Sale
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

        $commission = $this->calculateSalesCommision($value);

        $sale = new Sale(value: $value, date: $date, seller: $seller, commission: $commission);

        $this->saleRepository->save($sale);

        return $sale;
    }

    public function calculateSalesCommision(float $saleValue): float
    {
        $salesCommissionPercentage = (float) env('SALES_COMMISSION_PERCENTAGE', 8.5);

        $salesCommission = $saleValue * $salesCommissionPercentage / 100;

        return round($salesCommission, 2);
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

    public function getTotalDailySales(): array
    {
        $sales = $this->saleRepository->all();

        usort($sales, function($a, $b) {
            $timestampA = $a->getDate()->getTimestamp();
            $timestampB = $b->getDate()->getTimestamp();
            return $timestampB - $timestampA;
        });

        $arrayOfSales = [];

        /** @var Sale $sale */
        foreach ($sales as $sale) {
            $arrayOfSales[] = [
                'date' => $sale->getDate()->format('Y-m-d'),
                'value' => $sale->getValue()
            ];
        }

        return array_reduce($arrayOfSales, function ($carry, $item) {
            $date = $item['date'];
            $value = $item['value'];
            $carry[$date] = $carry[$date] ?? 0;
            $carry[$date] += $value;
            return $carry;
        }, []);
    }

    public function getTotalSalesForTheDayGroupedBySeller(Datetime $day = new DateTime(), ?int $sellerId = null): array
    {
        return $this->saleRepository->getSumOfDailySalesPerSeller($day->format('Y-m-d'), $sellerId);
    }
}
