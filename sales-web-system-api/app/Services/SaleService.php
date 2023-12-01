<?php

namespace App\Services;

use App\Interfaces\SaleRepositoryInterface;
use App\Exceptions\ModelNotFoundException;
use App\Models\Sale;
use App\Models\Seller;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

class SaleService
{
    public function __construct(private SaleRepositoryInterface $saleRepository) {}

    public function getAllSales(?string $query = null): Collection
    {
        $filters = [
            'value' => $query,
            'date' => $query
        ];

        return Sale::where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                $query->orwhere($key, 'like', '%' . $value . '%');
            }
        })->get();
    }

    /** @throws ModelNotFoundException se a venda não existir  */
    public function getSale(string $id): Sale
    {
        $sale = Sale::find($id);

        if (is_null($sale)) {
            throw new ModelNotFoundException('Venda não encontrada.');
        }

        return $sale;
    }

    /** @throws ModelNotFoundException se o vendedor não existir  */
    public function createSale(float $value, DateTime $date, int $sellerId): Sale
    {
        $this->validateSaleDate($date);

        $seller = Seller::find($sellerId);

        if (is_null($seller)) {
            throw new ModelNotFoundException('Vendedor não encontrado.');
        }

        $commission = $this->calculateSalesCommision($sellerId, $value);

        return Sale::create([
            'value' => $value,
            'date' => $date->format('Y-m-d'),
            'commission' => $commission,
            'seller_id' => $seller->id
        ]);
    }

    public function calculateSalesCommision(int $sellerId, float $saleValue): float
    {
        $salesCommissionPercentage = Seller::find($sellerId)->commission_percentage;

        $salesCommission = $saleValue * $salesCommissionPercentage / 100;

        return round($salesCommission, 2);
    }

    /** @throws \DomainException se a data da venda for anterior à data atual */
    public function deleteSale(int $id): void
    {
        $sale = Sale::find($id);

        if (is_null($sale)) {
            return;
        }

        if ($sale->date->diff(new DateTime())->days > 0) {
            throw new \DomainException('A venda não pode ser excluída porque já foi processada.');
        }

        $sale->delete();
    }

    public function getAllSalesPerSeller(Seller $seller): Collection
    {
        return Sale::where('seller_id', $seller->id)->get();
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
        $sales = Sale::all()->toArray();

        usort($sales, function($a, $b) {
            $timestampA = DateTime::createFromFormat('Y-m-d', $a['date'])->getTimestamp();
            $timestampB = DateTime::createFromFormat('Y-m-d', $b['date'])->getTimestamp();

            return $timestampB - $timestampA;
        });

        return array_reduce($sales, function ($carry, $item) {
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
