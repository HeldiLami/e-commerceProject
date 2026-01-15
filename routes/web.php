<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

//frontend views
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::view('/orders', 'front.orders')->name('orders');
Route::view('/checkout', 'front.checkout')->name('checkout');
Route::view('/tracking', 'front.tracking')->name('tracking');
Route::view('/sidebar', 'components.sidebar')->name('sidebar');

// Route::get('/register', [AuthController::class, 'showRegisterUser'])->name('show.registerUser');
// Route::post('/register', [AuthController::class, 'register'])->name('register');

// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('users', UserController::class)->except('store');

Route::get('/products', [ProductController::class, 'index']);


// Route::post('/statistics', 'admin.statistics')->name('statistics');
Route::get('/admin/statistics', function () {
    return view('admin.statistics');
})->name('admin.statistics');



Route::get('/admin/users', function () {
    $users = User::latest()->get();
    return view('admin.users', ['users' => $users]);
})->name('admin.users');


Route::get('/products', [ProductController::class, 'index']);


//TEST HELDI

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');
 
//TEST middleware
// Route::get('/orders', function (){
//     return view('front.orders');
// })->middleware(['auth', 'verified'])->name('orders');