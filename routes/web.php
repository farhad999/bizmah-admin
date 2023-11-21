<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VariationTemplateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CategoryController;
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

  //user
  Route::resource('/users', UserController::class);
  Route::resource('/brands', BrandController::class);
  Route::resource('/categories', CategoryController::class);
  Route::get('/get-sub-categories', [CategoryController::class, 'getSubCategories']);

  Route::resource('/variation-templates', VariationTemplateController::class);
  Route::resource('/products', ProductController::class);
  Route::get('/get-variation-template', [ProductController::class, 'getVariationTemplate']);
  Route::post('/create-variation', [ProductController::class, 'createVariation']);

});



