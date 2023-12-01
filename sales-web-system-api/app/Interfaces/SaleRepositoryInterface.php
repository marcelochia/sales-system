<?php

namespace App\Interfaces;

interface SaleRepositoryInterface
{
    public function getSumOfDailySalesPerSeller(string $date, ?int $sellerId): array;
}
