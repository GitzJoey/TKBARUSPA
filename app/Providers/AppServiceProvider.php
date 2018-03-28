<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\DatabaseService;
use App\Services\CompanyService;

use App\Services\Implementations\DatabaseServiceImpl;
use App\Services\Implementations\CompanyServiceImpl;

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
        ];
    }
}
