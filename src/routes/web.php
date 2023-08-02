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


Route::get('/', [App\Http\Controllers\ProductsController::class, 'list'])->name('list');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namespace' => 'App\Http\Controllers'], function()
{
    Route::group(['prefix' => 'products'], function() {
        Route::get('/create', 'ProductsController@create')->name('products.create');
        Route::post('/create', 'ProductsController@store')->name('products.store');
        Route::post('/add-products-basket', 'ProductsController@addProductsBasket')->name('products.addProductsBasket');
    });
});

Auth::routes();
