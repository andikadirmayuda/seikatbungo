<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Sale;
use App\Observers\ProductObserver;
use App\Observers\SaleObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Sale::observe(SaleObserver::class);
        Paginator::useBootstrap(); // Menggunakan Bootstrap style untuk pagination
    }
}
