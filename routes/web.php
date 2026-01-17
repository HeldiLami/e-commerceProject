<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TrackingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use App\Http\Controllers\StatisticsController;


/*
|--------------------------------------------------------------------------
| RRUGËT PUBLIKE (Aksesueshme pa login)
|--------------------------------------------------------------------------
*/
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

// use App\Http\Controllers\StripeWebhookController;

// Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
//     ->name('stripe.webhook');

    use App\Http\Controllers\StripeWebhookController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| RRUGËT E MBROJTURA (Kërkojnë Login - Middleware 'auth')
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

    // USER
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Auth Actions
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.overview');
    })->name('admin.overview');

    Route::view('/admin/statistics', 'admin.statistics')->name('admin.statistics');

    Route::get('/admin/users', function () {
        $users = User::latest()->get();
        return view('admin.users', ['users' => $users]);
    })->name('admin.users');
});
