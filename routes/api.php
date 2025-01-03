<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/get-related-products/{slug}', [ProductController::class, 'getRelatedProducts']);
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/new-arrivals', [HomeController::class, 'latestProducts']);
Route::get('/cart-products', [ProductController::class, 'cartProducts']);
Route::get('/product-filters', [ProductController::class, 'getFilters']);

//customer authentication
Route::post('/auth/get-code', [AuthController::class, 'getCode']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::get('get-cities', [AddressController::class, 'getCities']);
Route::get('get-zones/{city-id}', [AddressController::class, 'getZones']);

Route::get('/settings', [HomeController::class, 'getSettings']);
Route::get('/featured-categories', [ProductController::class, 'getFeaturedCategories']);
Route::get('/home-slides', [HomeController::class, 'homeSlides']);

Route::get('/page/{slug}', [HomeController::class, 'getPage']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/auth/user', [AuthController::class, 'getUser']);

  //address

  Route::resource('addresses', AddressController::class)
    ->only(['store', 'destroy']);

  Route::post('/remove-cart-item', [CartController::class, 'destroy']);
  Route::post('/update-cart-quantity', [CartController::class, 'updateQty']);
  //cart
  Route::resource('carts', CartController::class)
    ->only(['index', 'store']);

  //orders

    Route::group(['as' => 'api.'], function(){
      Route::resource('/orders', OrderController::class)
  ->except('store');
});
  

});

Route::post('/orders', [OrderController::class, 'store']);
