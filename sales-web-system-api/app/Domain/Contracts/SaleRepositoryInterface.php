<?php

namespace App\Domain\Contracts;

use App\Domain\Entity\Sale;
use App\Domain\Entity\Seller;

interface SaleRepositoryInterface
{
    /** @return Sale[] */
    public function all(): array;
    public function findById(int $id): ?Sale;
    /** @return Sale[] */
    public function getBySeller(Seller $seller): array;
    public function save(Sale $seller): void;
    public function deleteById(int $id): void;
}
