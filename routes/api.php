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

    Route::controller(App\Http\Controllers\CityController::class)->group(function () {
        Route::get('/city', 'index');
        Route::get('/city/pagination', 'get_cities_pagination');
        Route::get('/city/{city_id}', 'show')->where('city_id', '[0-9]+');
        Route::get('/city/uuid/{city_uuid}', 'get_city_uuid');
        Route::get('/city/country/{country_id}', 'get_cities_country')->where('country_id', '[0-9]+');
        Route::post('/city', 'store');
        Route::put('/city/{city_uuid}', 'update');
        Route::delete('/city/{city_uuid}', 'destroy');
    });

    Route::controller(App\Http\Controllers\UserController::class)->group(function () {
        Route::post('/logout', 'logout');
    });
});

Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
