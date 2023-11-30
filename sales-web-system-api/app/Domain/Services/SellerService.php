<?php

namespace App\Domain\Services;

use App\Domain\ValueObjects\Email;
use App\Exceptions\ModelNotFoundException;
use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Collection;

class SellerService
{
    public function __construct() {}

    public function getAllSellers(?string $query = null, string $sortBy = null, string $order = 'asc'): Collection
    {
        $filters = [
            'name' => $query,
            'email' => $query
        ];

        return Seller::where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                $query->orwhere($key, 'like', '%' . $value . '%');
            }
        })->orderBy($sortBy, $order)
          ->get();
    }

    /** @throws ModelNotFoundException se o vendedor não existir  */
    public function getSeller(int $id): Seller
    {
        $seller = Seller::find($id);

        if (is_null($seller)) {
            throw new ModelNotFoundException('Vendedor não encontrado.');
        }

        return $seller;
    }

    public function createSeller(string $name, string $email): Seller
    {
        $seller = new Seller([
            'name' => $name,
            'email' => (new Email($email))->value
        ]);

        $this->validateEmail($seller, $email);

        $seller->save();

        return $seller;
    }

    /** @throws ModelNotFoundException se o vendedor não existir  */
    public function updateSeller(int $id, string $name, string $email): Seller
    {
        $seller = $this->getSeller($id);

        $this->validateEmail($seller, $email);

        $seller->name = $name;
        $seller->email = (new Email($email))->value;
        $seller->save();

        return $seller;
    }

    /** @throws \DomainException se o vendedor tiver vendas relacionadas  */
    public function deleteSeller(string $id): void
    {
        try {
            $seller = $this->getSeller($id);
        } catch (ModelNotFoundException) {
            return;
        }

        $salesOfThisSeller = Sale::where('seller_id', $seller->id)->get();

        if (count($salesOfThisSeller) > 0) {
            throw new \DomainException('Não é possível excluir o vendedor porque tem vendas relacionadas.');
        }

        $seller->delete();
    }

    /** @throws \DomainException se o email estiver associado a outro vendedor  */
    public function validateEmail(Seller $seller, string $email): void
    {
        $sellerWithExistentEmail = Seller::where('email', $email)->first();

        if (!is_null($sellerWithExistentEmail) && $seller->id !== $sellerWithExistentEmail->id) {
            throw new \DomainException('Email já está em uso para outro vendedor.');
        }
    }
}
