<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripePaymentController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TrackingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

/*
|--------------------------------------------------------------------------
| RRUGËT PUBLIKE (Aksesueshme pa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Webhook duhet të jetë publik dhe pa CSRF
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('stripe.webhook');


/*
|--------------------------------------------------------------------------
| RRUGËT E MBROJTURA (Kërkojnë Login - Middleware 'auth')
|--------------------------------------------------------------------------
| Nëse përdoruesi nuk është i loguar, Laravel do e ridrejtojë te rruga 'login'.
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

    // Admin & User Management
    Route::resource('users', UserController::class)->except('store');
    Route::view('/admin/statistics', 'admin.statistics')->name('admin.statistics');
    
    Route::get('/admin/users', function () {
        $users = User::latest()->get();
        return view('admin.users', ['users' => $users]);
    })->name('admin.users');

Route::get('/admin', function () {
    return view('admin.overview');
})->middleware(['auth', 'admin'])->name('admin.overview');
    //USER
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');  
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    
    // Auth Actions
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/auth/verify-email', function (Request $request) {
        if ($request->query('verified') == 1) {
            Log::info('User logged in');    
        }
        return redirect()->route('home');
    })->middleware('verified');

    Route::post('/ratings/store', [RatingController::class, 'store'])->name('ratings.store');
});

Route::get('/product/{product}', [ProductController::class, 'show'])
    ->name('product.show');