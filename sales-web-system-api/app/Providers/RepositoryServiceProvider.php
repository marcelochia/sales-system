<?php

namespace App\Providers;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\SaleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SaleRepositoryInterface::class, SaleRepository::class);
    }
}
