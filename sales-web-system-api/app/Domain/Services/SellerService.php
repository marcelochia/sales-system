<?php

namespace App\Domain\Services;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Domain\Contracts\SellerRepositoryInterface;
use App\Domain\Entity\Seller;
use App\Domain\ValueObjects\Email;
use App\Exceptions\EntityNotFoundException;

class SellerService
{
    public function __construct(private SellerRepositoryInterface $sellerRepository, private SaleRepositoryInterface $saleRepository) {}

    /** @return Seller[] */
    public function getAllSellers(?string $query = null, string $sortBy = null, string $order = 'asc'): array
    {
        return $this->sellerRepository->findBy([
            'name' => $query,
            'email' => $query
        ], $sortBy, $order);
    }

    /** @throws EntityNotFoundException se o vendedor não existir  */
    public function getSeller(int $id): Seller
    {
        $seller = $this->sellerRepository->findById($id);

        if (is_null($seller)) {
            throw new EntityNotFoundException('Vendedor não encontrado.');
        }

        return $seller;
    }

    public function createSeller(string $name, string $email): Seller
    {
        $seller = new Seller($name, new Email($email));

        $this->validateEmail($seller, $email);

        $this->sellerRepository->save($seller);

        return $seller;
    }

    /** @throws EntityNotFoundException se o vendedor não existir  */
    public function updateSeller(int $id, string $name, string $email): Seller
    {
        $seller = $this->sellerRepository->findById($id);

        if (is_null($seller)) {
            throw new EntityNotFoundException('Vendedor não encontrado.');
        }

        $this->validateEmail($seller, $email);

        $seller->setName($name)->setEmail(new Email($email));

        $this->sellerRepository->update($seller);

        return $seller;
    }

    /** @throws \DomainException se o vendedor tiver vendas relacionadas  */
    public function deleteSeller(string $id): void
    {
        $seller = $this->sellerRepository->findById($id);

        if (is_null($seller)) {
            return;
        }

        $salesOfThisSeller = $this->saleRepository->getBySeller($seller);

        if (count($salesOfThisSeller) > 0) {
            throw new \DomainException('Não é possível excluir o vendedor porque tem vendas relacionadas.');
        }

        $this->sellerRepository->deleteById($id);
    }

    /** @throws \DomainException se o vendedor não existir  */
    public function validateEmail(Seller $seller, string $email): void
    {
        $existentEmail = $this->sellerRepository->findByEmail($email);

        if (!is_null($existentEmail) && $seller->getId() !== $existentEmail->getId()) {
            throw new \DomainException('Email já está em uso para outro vendedor.');
        }
    }
}
