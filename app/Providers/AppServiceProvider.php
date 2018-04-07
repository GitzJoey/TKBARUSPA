<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\DatabaseService;
use App\Services\CompanyService;
use App\Services\UnitService;
use App\Services\BankService;
use App\Services\ProductService;

use App\Services\Implementations\DatabaseServiceImpl;
use App\Services\Implementations\CompanyServiceImpl;
use App\Services\Implementations\UnitServiceImpl;
use App\Services\Implementations\BankServiceImpl;
use App\Services\Implementations\ProductServiceImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DatabaseService::class, function (){
            return new DatabaseServiceImpl();
        });

        $this->app->singleton(CompanyService::class, function (){
            return new CompanyServiceImpl();
        });

        $this->app->singleton(UnitService::class, function (){
            return new UnitServiceImpl();
        });

        $this->app->singleton(BankService::class, function (){
            return new BankServiceImpl();
        });

        $this->app->singleton(ProductService::class, function (){
            return new ProductServiceImpl();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'App\Services\DatabaseService',
            'App\Services\CompanyService',
            'App\Services\UnitService',
            'App\Services\BankService',
        ];
    }
}
