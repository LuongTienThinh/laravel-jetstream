<?php

namespace Modules\Cart\src\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Cart\src\Services\CartRepository;
use Modules\Cart\src\Services\CartItemRepository;
use Modules\Cart\src\Services\CartRepositoryEloquent;
use Modules\Cart\src\Services\CartItemRepositoryEloquent;

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
