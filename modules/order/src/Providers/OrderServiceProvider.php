<?php

namespace Modules\Order\Providers;

use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
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
//        $this->mergeConfigFrom(__DIR__.'/../../config/cart-config.php', 'cart-config');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
//        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'cart');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'Modules-Order');
    }
}
