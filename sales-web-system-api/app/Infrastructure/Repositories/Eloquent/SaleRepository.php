<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Domain\Entity\Sale;
use App\Domain\Entity\Seller;
use App\Domain\ValueObjects\Email;
use App\Models\Sale as Model;
use DateTime;

class SaleRepository implements SaleRepositoryInterface
{
    public function all(): array
    {
        $sellers = [];

        $registers = Model::all();

        foreach ($registers as $register) {
            $sellers[] = $this->bindingEntity($register);
        }

        return $sellers;
    }

    public function findById(int $id): ?Sale
    {
        return Model::find($id) ? $this->bindingEntity(Model::find($id)) : null;
    }

    public function findBy(array $filters): array
    {
        $sellers = [];

        $results = Model::where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                $query->orwhere($key, 'like', '%' . $value . '%');
            }
        })->get();

        foreach ($results as $result) {
            $sellers[] = $this->bindingEntity($result);
        }

        return $sellers;
    }

    public function getBySeller(Seller $seller): array
    {
        $sellers = [];

        $records = Model::where('seller_id', $seller->getId())->get();

        foreach ($records as $record) {
            $sellers[] = $this->bindingEntity($record);
        }

        return $sellers;
    }

    public function save(Sale $seller): void
    {
        $model = Model::create([
            'value' => $seller->getValue(),
            'date' => $seller->getDate(),
            'seller_id' => $seller->getSeller()->getId()
        ]);

        $seller->setId($model->id);
    }

    public function deleteById(int $id): void
    {
        Model::destroy($id);
    }

    private function bindingEntity(Model $model): Sale
    {
        return new Sale(
            id: $model->id,
            value: $model->value,
            date: new DateTime($model->date),
            seller: new Seller($model->seller->name, new Email($model->seller->email), $model->seller->id)
        );
    }
}
