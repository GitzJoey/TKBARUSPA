<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'get', 'middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'company'], function () {
        Route::get('readAll', 'CompanyController@readAll')->name('api.get.company.readall');
    });

    Route::group(['prefix' => 'unit'], function () {
        Route::get('readAll', 'UnitController@readAll')->name('api.get.unit.readall');
    });
});

Route::group(['prefix' => 'post', 'middleware' => 'auth:api'], function () {

});