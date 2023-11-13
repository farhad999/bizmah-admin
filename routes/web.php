<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
  ->name('home.index');

// authentication
Route::get('/login', [LoginController::class, 'index'])
  ->name('login');

