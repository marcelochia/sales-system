<?php

namespace App\Domain\Contracts;

use App\Domain\Entity\Seller;

interface SellerRepositoryInterface
{
    /** @return Seller[] */
    public function all(): array;
    public function findById(int $id): ?Seller;
    public function findByEmail(string $email): ?Seller;
    public function save(Seller $seller): void;
    public function update(Seller $seller): void;
    public function deleteById(int $id): void;
}
