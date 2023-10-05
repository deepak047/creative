<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WishlistController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [UserController::class, 'login']);

Route::post('/register', [RegisterController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    //Authenticated routes place here.. only available after user login
    Route::get('/user', [UserController::class, 'index']);
    
      //Checkout routes
      Route::group(['prefix' => 'checkout'], function () {

        Route::post('cart/add/{id}', [CartController::class, 'store']);

        Route::get('/cart', [CartController::class, 'get']);

        Route::get('cart/empty', [CartController::class, 'destroy']);

        Route::get('cart/remove-item/{id}', [CartController::class, 'destroyItem']);

        Route::post('save-order', [CheckoutController::class, 'saveOrder']);

    });

     //Order routes

    Route::apiResource('order', OrderController::class);

     //Wishlist routes

    Route::get('move-to-cart/{id}', [WishlistController::class, 'moveToCart']);

    Route::get('wishlist/add/{id}', [WishlistController::class, 'create']);

    Route::get('wishlist', [WishlistController::class, 'index']);

    Route::delete('wishlist/remove/{id}', [WishlistController::class, 'destroy']);

});


