<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
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

});



