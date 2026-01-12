<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//frontend views
Route::view('/', 'front.amazon');
Route::view('/orders', 'front.orders');
Route::view('/checkout', 'front.checkout');
Route::view('/tracking', 'front.tracking');

Route::get('/register', [AuthController::class, 'showRegisterUser'])->name('show.registerUser');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::resource('users', UserController::class)->except('store');