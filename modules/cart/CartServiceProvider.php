<?php

namespace Modules\Cart;

use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register cart service
     */
    public function register()
    {

    }

    /**
     * Bootstrap cart service
     *
     * @return void
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/cart-config.php', 'cart-config');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Modules-Cart');
    }
}
