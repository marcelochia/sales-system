<?php

namespace App\Domain\Contracts;

use App\Domain\Entity\Seller;

interface SellerRepositoryInterface
{
    /** @return Seller[] */
    public function all(): array;
    public function findById(int $id): ?Seller;
    public function findByEmail(string $email): ?Seller;
    /** @return Seller[] */
    public function findBy(array $filter): array;
    public function save(Seller $seller): void;
    public function update(Seller $seller): void;
    public function deleteById(int $id): void;
}
