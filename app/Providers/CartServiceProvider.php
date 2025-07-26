<?php

namespace App\Providers;

use App\Domains\Cart\Repositories\HybridCartRepository;
use Illuminate\Support\ServiceProvider;
use App\Domains\Cart\Contracts\CartRepositoryInterface;


class CartServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(CartRepositoryInterface::class, HybridCartRepository::class);
    }
}
