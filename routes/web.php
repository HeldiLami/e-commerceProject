<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [])->name('show.register');
Route::get('/login', [])->name('show.login');


Route::resource('users', UserController::class);