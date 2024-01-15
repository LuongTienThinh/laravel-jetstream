<?php

namespace Modules\Cart\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Cart\Services\CartRepository;
use Modules\Cart\Services\CartItemRepository;
use Modules\Cart\Services\CartRepositoryEloquent;
use Modules\Cart\Services\CartItemRepositoryEloquent;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(CartRepository::class, CartRepositoryEloquent::class);
        $this->app->bind(CartItemRepository::class, CartItemRepositoryEloquent::class);
    }
}
