<?php

namespace App\Domain\Contracts;

interface SaleRepositoryInterface
{
    public function getSumOfDailySalesPerSeller(string $date, ?int $sellerId): array;
}
