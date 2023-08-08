<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [App\Http\Controllers\ProductsController::class, 'list'])->name('products.list');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namespace' => 'App\Http\Controllers'], function()
{
    Route::group(['prefix' => 'products'], function() {
        Route::get('/create', 'ProductsController@create')->name('products.create');
        Route::post('/create', 'ProductsController@store')->name('products.store');
    });

    Route::group(['prefix' => 'basket'], function() {
        Route::get('/show', 'BasketController@show')->name('basket.show');
        Route::post('/add', 'BasketController@add')->name('basket.add');
        Route::post('/delete', 'BasketController@delete')->name('basket.delete');
    });

    Route::group(['prefix' => 'orders'], function() {
        Route::get('/list', 'OrdersController@list')->name('orders.list');
        Route::post('/add', 'OrdersController@add')->name('orders.add');
        Route::post('/delete', 'OrdersController@delete')->name('orders.delete');
    });
});

Auth::routes();
