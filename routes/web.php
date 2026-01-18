<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\StatisticsController;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| RRUGËT PUBLIKE (pa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Stripe webhook publik (pa CSRF)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('stripe.webhook');


/*
|--------------------------------------------------------------------------
| RRUGËT E MBROJTURA (auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Cart & Tracking
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/tracking', [TrackingController::class, 'show'])->name('tracking');
    Route::view('/checkout', 'front.checkout')->name('checkout');

    // Stripe Payments
    Route::post('/checkout/session', [StripePaymentController::class, 'createCheckoutSession'])->name('checkout.session');
    Route::post('/checkout/session/redirect', [StripePaymentController::class, 'redirectToCheckout'])->name('checkout.session.redirect');
    Route::get('/checkout/success', [StripePaymentController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [StripePaymentController::class, 'cancel'])->name('checkout.cancel');

    // Users
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Verify email callback (nëse e përdor)
    Route::get('/auth/verify-email', function (Request $request) {
        if ($request->query('verified') == 1) {
            Log::info('User logged in');
        }
        return redirect()->route('home');
    })->middleware('verified');

    // Ratings
    Route::post('/ratings/store', [RatingController::class, 'store'])->name('ratings.store');
});


/*
|--------------------------------------------------------------------------
| ADMIN (auth + admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return view('admin.overview');
        })->name('overview');

        // Statistics
        Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

        // ✅ USERS (vetëm nga controller)
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/{user}/edit', [UserController::class, 'adminEdit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'adminUpdate'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // ✅ Products (Admin)
        Route::get('/products/create', [ProductController::class, 'adminCreate'])->name('products.create');
        Route::post('/products', [ProductController::class, 'adminStore'])->name('products.store');
    });