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

        Route::group(['prefix' => 'bank'], function () {
            Route::get('', 'BankController@index')->name('db.bank');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('company', 'CompanyController@index')->name('db.settings.company');
            Route::get('unit', 'UnitController@index')->name('db.settings.unit');
        });

        Route::post('search', 'SearchController@search')->name('db.search');
    });
});
