<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use \Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Vinkla\Hashids\Facades\Hashids;

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => LaravelLocalization::setLocale()], function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('', 'DashboardController@index')->name('db');

        Route::group(['prefix' => 'product'], function () {
            Route::get('', 'ProductController@index')->name('db.product');
        });

        Route::group(['prefix' => 'po'], function () {
            Route::get('', 'PurchaseOrderController@index')->name('db.po');
        });

        Route::group(['prefix' => 'truck'], function () {
            Route::group(['prefix' => 'vendor_trucking'], function () {
                Route::get('', 'VendorTruckingController@index')->name('db.truck.vendor_trucking');
            });

            Route::get('', 'TruckController@index')->name('db.truck');
        });

        Route::group(['prefix' => 'price_level'], function () {
            Route::get('', 'PriceLevelController@index')->name('db.price_level');
        });

        Route::group(['prefix' => 'warehouse'], function () {
            Route::get('', 'WarehouseController@index')->name('db.warehouse');
        });

        Route::group(['prefix' => 'bank'], function () {
            Route::get('', 'BankController@index')->name('db.bank');
        });

        Route::group(['prefix' => 'supplier'], function () {
            Route::get('', 'SupplierController@index')->name('db.supplier');
        });

        Route::group(['prefix' => 'customer'], function () {
            Route::get('', 'CustomerController@index')->name('db.customer');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('user', 'UserController@index')->name('db.settings.user');
            Route::get('company', 'CompanyController@index')->name('db.settings.company');
            Route::get('unit', 'UnitController@index')->name('db.settings.unit');
            Route::get('role', 'RoleController@index')->name('db.settings.role');
            Route::get('phone_provider', 'PhoneProviderController@index')->name('db.settings.phone_provider');
        });

        Route::post('search', 'SearchController@search')->name('db.search');
    });

    Route::view('test', 'test');
});
