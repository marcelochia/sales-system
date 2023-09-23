<?php

namespace App\Providers;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Domain\Contracts\SellerRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\SaleRepository;
use App\Infrastructure\Repositories\Eloquent\SellerRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SellerRepositoryInterface::class, SellerRepository::class);
        $this->app->bind(SaleRepositoryInterface::class, SaleRepository::class);
    }
}
