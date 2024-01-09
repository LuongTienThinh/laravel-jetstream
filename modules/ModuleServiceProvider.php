<?php

namespace Modules;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register module service
     */
    public function register()
    {

    }

    /**
     * Bootstrap module service
     *
     * @return void
     */
    public function boot(): void
    {
        $modules = [
            'Modules\Cart\CartServiceProvider',
            'Modules\Cart\src\Providers\RouteServiceProvider',
            'Modules\Cart\src\Providers\RepositoryServiceProvider',
        ];

        foreach ($modules as $module) {
            $this->app->register($module);
        }
    }
}
