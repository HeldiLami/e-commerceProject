<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('show.register');
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('show.login');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::resource('users', UserController::class)->except('store');