<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth:api'], function () {

    Route::controller(App\Http\Controllers\ProductController::class)->group(function () {
        Route::get('/product', 'index');
        Route::get('/product/pagination', 'get_products_pag');
        Route::get('/product/{product_id}', 'show')->where('product_id', '[0-9]+');
        Route::get('/product/uuid/{product_uuid}', 'show_uuid');
        Route::get('/product/country/{country_id}', 'get_cities_country')->where('country_id', '[0-9]+');
        Route::post('/product', 'store');
        Route::put('/product/{city_uuid}', 'update');
        Route::delete('/product/{city_uuid}', 'destroy');
    });

    Route::controller(App\Http\Controllers\UserController::class)->group(function () {
        Route::post('/logout', 'logout');
    });
});

Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
