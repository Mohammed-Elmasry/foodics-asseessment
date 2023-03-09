<?php

namespace App\Providers;

use App\Services\IngredientsService;
use App\Services\OrdersService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(IngredientsService::class, function () {
            return new IngredientsService();
        });

        $this->app->singleton(OrdersService::class, function () {
            return new OrdersService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
