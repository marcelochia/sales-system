<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\SellerRepositoryInterface;
use App\Domain\Entity\Seller;
use App\Domain\ValueObjects\Email;
use App\Models\Seller as Model;

class SellerRepository implements SellerRepositoryInterface
{
    public function all(): array
    {
        $sellers = [];

        $records = Model::all();

        foreach ($records as $record) {
            $sellers[] = $this->bindingEntity($record);
        }

        return $sellers;
    }

    public function findById(int $id): ?Seller
    {
        return Model::find($id) ? $this->bindingEntity(Model::find($id)) : null;
    }

    public function findByEmail(string $email): ?Seller
    {
        $model = Model::where('email', $email)->first();
        return $model ? $this->bindingEntity($model) : null;
    }

    public function findBy(array $filters, string $sortBy = null, string $order = 'asc'): array
    {
        $sellers = [];

        $results = Model::where(function ($query) use ($filters) {
            foreach ($filters as $key => $value) {
                $query->orwhere($key, 'like', '%' . $value . '%');
            }
        })
        ->orderBy($sortBy, $order)
        ->get();

        foreach ($results as $result) {
            $sellers[] = $this->bindingEntity($result);
        }

        return $sellers;
    }

    public function save(Seller $seller): void
    {
        $model = Model::create([
            'name' => $seller->getName(),
            'email' => $seller->getEmail(),
        ]);

        $seller->setId($model->id);
    }

    public function update(Seller $seller): void
    {
        Model::where('id', $seller->getId())->update([
            'name' => $seller->getName(),
            'email' => $seller->getEmail(),
        ]);
    }

    public function deleteById(int $id): void
    {
        Model::destroy($id);
    }

    private function bindingEntity(Model $model): Seller
    {
        return new Seller(
            name: $model->name,
            email: new Email($model->email),
            id: $model->id
        );
    }
}
