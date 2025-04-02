<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\IProduct;
use App\Repositories\Product\IProduct as ProductIProduct;
use App\Repositories\ProductRepository\IProduct as ProductRepositoryIProduct;
use App\Repositories\Product\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(ProductIProduct::class,ProductRepository ::class);
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
