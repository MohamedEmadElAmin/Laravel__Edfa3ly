<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Offers\OffersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Cart\ShoppingCartController;
use App\Http\Controllers\Cart\ShoppingCartItemController;

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

Route::prefix('/v1/')->group(function ()
{
    Route::get('/products', [ProductController::class, 'index'])->name('products.list');
    Route::get('/offers', [OffersController::class, 'index'])->name('offers.list');
    Route::post('/register', [AuthController::class, 'register'])->name('users.register');
    Route::post('/login', [AuthController::class, 'login'])->name('users.login');
});


Route::prefix('/v1/')->middleware('auth:sanctum')->group(function ()
{
    Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');

    Route::get('/carts/mine', [ShoppingCartController::class, 'getCustomerCart'])->name('cart.mine');


    Route::post('/carts/mine/items', [ShoppingCartItemController::class, 'addItemToCart'])->name('cart.add_item');
    Route::delete('/carts/mine/items', [ShoppingCartItemController::class, 'removeItemFromCart'])->name('cart.remove_item');
    Route::get('/carts/mine/items', [ShoppingCartItemController::class, 'getItemsInCart'])->name('cart.items');
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
