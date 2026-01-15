<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TrackingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

//frontend views
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/orders', [OrderController::class, 'index'])
    ->middleware('auth')
    ->name('orders');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])
    ->middleware('auth')
    ->name('orders.destroy');
Route::post('/orders', [OrderController::class, 'store'])->middleware('auth')->name('orders.store');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::view('/checkout', 'front.checkout')->name('checkout');
Route::get('/tracking', [TrackingController::class, 'show'])
    ->middleware('auth')
    ->name('tracking');
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

Route::get('/auth/verify-email', function (Request $request) {
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
Route::post('/checkout/session/redirect', [StripePaymentController::class, 'redirectToCheckout'])
    ->middleware('auth')
    ->name('checkout.session.redirect');
Route::get('/checkout/success', [StripePaymentController::class, 'success'])
    ->middleware('auth')
    ->name('checkout.success');
Route::get('/checkout/cancel', [StripePaymentController::class, 'cancel'])
    ->middleware('auth')
    ->name('checkout.cancel');

 

Route::get('/product/{product}', [ProductController::class, 'show'])
    ->name('product.show');

// use App\Http\Controllers\StripeWebhookController;

// Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
//     ->name('stripe.webhook');

    use App\Http\Controllers\StripeWebhookController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('stripe.webhook');
