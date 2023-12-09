<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VariationTemplateController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\FeaturedCategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// authentication
Route::get('/login', [AuthController::class, 'login'])
  ->name('login');
Route::post('/login', [AuthController::class, 'postLogin'])
  ->name('auth.post-login');
Route::post('/logout', [AuthController::class, 'logout'])
  ->name("auth.logout")
  ->middleware('auth');

Route::middleware(['auth'])->group(function () {
  Route::get('/', [HomeController::class, 'index'])
    ->name('home.index');

  Route::get('/get-last-30-days-orders-data', [HomeController::class, 'getLast30DaysOrders']);

  Route::get('/get-comparison-orders', [HomeController::class, 'getComparisonOrders']);

  //user
  Route::resource('/users', UserController::class);
  Route::resource('/brands', BrandController::class);
  Route::resource('/categories', CategoryController::class);
  Route::get('/get-sub-categories', [CategoryController::class, 'getSubCategories']);

  Route::resource('/variation-templates', VariationTemplateController::class);

  Route::get('/products/{id}/image_gallery', [ProductController::class, 'imageGallery'])
    ->name('products.image-gallery');

  Route::post('/products/{id}/upload', [ProductController::class, 'upload'])
    ->name('products.upload');

  Route::post('/products/{id}/image_gallery', 'ProductController@uploadGallery');
  Route::delete('/products/{id}/delete_gallery_image', [ProductController::class, 'deleteGalleryImage']);
  Route::get('/products/{id}/load_images', [ProductController::class, 'loadImages']);
  Route::get('/search-products', [ProductController::class, 'search']);
  Route::resource('/products', ProductController::class);
  Route::get('/get-variation-template', [ProductController::class, 'getVariationTemplate']);
  Route::post('/create-variation', [ProductController::class, 'createVariation']);
  Route::get('/get-variation', [ProductController::class, 'getVariation']);

  //customer
  Route::get('/search-customer', [CustomerController::class, 'search']);
  Route::get('/get-customer-details', [CustomerController::class, 'getCustomerDetails']);
  Route::resource('/customers', CustomerController::class);

  Route::get('/get-order-row', [OrderController::class, 'getOrderRow']);
  Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])
    ->name('orders.update-status');
  Route::post('/orders/{id}/update-shipping-status', [OrderController::class, 'updateShippingStatus']);
  Route::resource('/orders', OrderController::class);

  Route::get('{type}-orders', [OrderController::class, 'index'])
    ->where('type', 'confirmed|pending|cancelled')
    ->name('order-type.index');

  Route::resource('/pages', PageController::class);

  //settings

  Route::post('/carousels/{id}/add-slide', [CarouselController::class, 'addSlide'])
    ->name('carousels.add-slide');

  Route::post('/carousels/{id}/reorder', [CarouselController::class, 'reorder'])
    ->name('carousels.reorder-slide');

  Route::delete('/carousel-slide/{id}', [CarouselController::class, 'removeSlide'])
    ->name("carousels.delete-slide");

  Route::post('/carousels/{id}/make-active', [CarouselController::class, 'makeActive'])
    ->name('carousels.make-active');

  Route::resource('/carousels', CarouselController::class);

  Route::post('/featured-categories/{id}/add', [FeaturedCategoryController::class, 'add']);

  Route::post('/featured-categories/update-order', [FeaturedCategoryController::class, 'updateOrder'])
    ->name('featured-categories.update-order');

  Route::resource('/featured-categories', FeaturedCategoryController::class)
    ->only(['index', 'destroy']);

  //General Settings

  Route::get('/settings', [SettingController::class, 'getSettings'])
    ->name('settings.index');

  Route::post("/settings", [SettingController::class, 'updateSettings'])
    ->name('settings.update');

});



