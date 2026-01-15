<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

//frontend views
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::view('/orders', 'front.orders')->name('orders');
Route::post('/orders', [OrderController::class, 'store'])->middleware('auth')->name('orders.store');
Route::get('/cart', function () {
    $products = Product::all()->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'image' => asset($product->image),
            'price_cents' => $product->price_cents,
        ];
    });

    return view('front.cart', ['products' => $products]);
})->name('cart');
Route::view('/checkout', 'front.checkout')->name('checkout');
Route::view('/tracking', 'front.tracking')->name('tracking');
Route::view('/sidebar', 'components.sidebar')->name('sidebar');


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

Route::get('/admin/dashboard', function (Request $request) {
    if ($request->query('verified') == 1) {
        Log::info('User logged in');    
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified']);

//TEST middleware
// Route::get('/orders', function (){
//     return view('front.orders');
// })->middleware(['auth', 'verified'])->name('orders');
use App\Http\Controllers\StripePaymentController;

Route::post('/checkout/session', [StripePaymentController::class, 'createCheckoutSession'])
    ->middleware('auth')
    ->name('checkout.session');
Route::get('/checkout/success', [StripePaymentController::class, 'success'])
    ->middleware('auth')
    ->name('checkout.success');
Route::get('/checkout/cancel', [StripePaymentController::class, 'cancel'])
    ->middleware('auth')
    ->name('checkout.cancel');

 

Route::get('/product/{product}', [ProductController::class, 'show'])
    ->name('product.show');
