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

Route::bind('id', function ($id) {
    if (!is_numeric($id)) {
        return Hashids::decode($id)[0];
    } else {
        return $id;
    }
});

Route::group(['prefix' => 'get', 'middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'price'], function () {
        Route::group(['prefix' => 'price_level'], function () {
            Route::get('read', 'PriceLevelController@read')->name('api.get.price.price_level.read');
        });
    });

    Route::group(['prefix' => 'product'], function () {
        Route::get('read', 'ProductController@read')->name('api.get.product.read');

        Route::group(['prefix' => 'product_type'], function () {
            Route::get('read', 'ProductTypeController@read')->name('api.get.product_type.read');
        });
    });

    Route::group(['prefix' => 'supplier'], function () {
        Route::get('read', 'SupplierController@read')->name('api.get.supplier.read');
    });

    Route::group(['prefix' => 'truck'], function () {
        Route::group(['prefix' => 'vendor_trucking'], function () {
            Route::get('read', 'VendorTruckingController@read')->name('api.get.truck.vendor_trucking.read');
        });
    });

    Route::group(['prefix' => 'warehouse'], function () {
        Route::get('read', 'WarehouseController@read')->name('api.get.warehouse.read');
    });

    Route::group(['prefix' => 'bank'], function () {
        Route::get('read', 'BankController@read')->name('api.get.bank.read');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('read', 'UserController@read')->name('api.get.settings.user.read');
        });

        Route::group(['prefix' => 'roles'], function () {
            Route::get('read', 'RolesController@read')->name('api.get.settings.roles.read');
        });

        Route::group(['prefix' => 'company'], function () {
            Route::get('read', 'CompanyController@read')->name('api.get.settings.company.read');
        });

        Route::group(['prefix' => 'unit'], function () {
            Route::get('read', 'UnitController@read')->name('api.get.settings.unit.read');
        });

        Route::group(['prefix' => 'phone_provider'], function () {
            Route::get('read', 'PhoneProviderController@read')->name('api.get.settings.phone_provider.read');
        });
    });

    Route::group(['prefix' => 'lookup'], function() {
        Route::get('byCategory/{category}', 'LookupController@getLookupByCategory')->name('api.get.lookup.bycategory');
    });
});

Route::group(['prefix' => 'post', 'middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'warehouse'], function () {
        Route::post('save', 'WarehouseController@store')->name('api.post.warehouse.save');
        Route::post('edit/{id}', 'WarehouseController@update')->name('api.post.warehouse.edit');
        Route::post('delete/{id}', 'WarehouseController@delete')->name('api.post.warehouse.delete');
    });

    Route::group(['prefix' => 'product'], function () {
        Route::post('save', 'ProductController@store')->name('api.post.product.save');
        Route::post('edit/{id}', 'ProductController@update')->name('api.post.product.edit');
        Route::post('delete/{id}', 'ProductController@delete')->name('api.post.product.delete');
    });

    Route::group(['prefix' => 'price'], function () {
        Route::group(['prefix' => 'price_level'], function () {
            Route::post('save', 'PriceLevelController@store')->name('api.post.price.price_level.save');
            Route::post('edit/{id}', 'PriceLevelController@update')->name('api.post.price.price_level.edit');
            Route::post('delete/{id}', 'PriceLevelController@delete')->name('api.post.price.price_level.delete');
        });
    });

    Route::group(['prefix' => 'truck'], function () {
        Route::group(['prefix' => 'vendor_trucking'], function () {
            Route::post('save', 'VendorTruckingController@store')->name('api.post.truck.vendor_trucking.save');
            Route::post('edit/{id}', 'VendorTruckingController@update')->name('api.post.truck.vendor_trucking.edit');
            Route::post('delete/{id}', 'VendorTruckingController@delete')->name('api.post.truck.vendor_trucking.delete');
        });
    });

    Route::group(['prefix' => 'supplier'], function () {
        Route::post('save', 'SupplierController@store')->name('api.post.supplier.save');
        Route::post('edit/{id}', 'SupplierController@update')->name('api.post.supplier.edit');
        Route::post('delete/{id}', 'SupplierController@delete')->name('api.post.supplier.delete');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::post('save', 'UserController@store')->name('api.post.settings.user.save');
            Route::post('edit/{id}', 'UserController@update')->name('api.post.settings.user.edit');
            Route::post('delete/{id}', 'UserController@delete')->name('api.post.settings.user.delete');
        });

        Route::group(['prefix' => 'company'], function () {
            Route::post('save', 'CompanyController@store')->name('api.post.settings.company.save');
            Route::post('edit/{id}', 'CompanyController@update')->name('api.post.settings.company.edit');
            Route::post('delete/{id}', 'CompanyController@delete')->name('api.post.settings.company.delete');
        });

        Route::group(['prefix' => 'unit'], function () {
            Route::post('save', 'UnitController@store')->name('api.post.settings.unit.save');
            Route::post('edit/{id}', 'UnitController@update')->name('api.post.settings.unit.edit');
            Route::post('delete/{id}', 'UnitController@delete')->name('api.post.settings.unit.delete');
        });
    });
});