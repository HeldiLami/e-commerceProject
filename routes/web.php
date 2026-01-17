<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripePaymentController;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Route;
use App\Models\User;
>>>>>>> b2895cdc6b7460c56fa911f1092d1398cdbb86f5
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TrackingController;
<<<<<<< HEAD
use App\Http\Controllers\StatisticsController;

/*
|--------------------------------------------------------------------------
| RRUGËT PUBLIKE (Aksesueshme pa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

/*
|--------------------------------------------------------------------------
| STRIPE WEBHOOK (publik + pa CSRF)
|--------------------------------------------------------------------------
*/
=======
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

Route::get('/register', [AuthController::class, 'showRegisterUser'])->name('show.registerUser');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Webhook duhet të jetë publik dhe pa CSRF
>>>>>>> b2895cdc6b7460c56fa911f1092d1398cdbb86f5
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('stripe.webhook');

<<<<<<< HEAD
/*
|--------------------------------------------------------------------------
| RRUGËT E MBROJTURA (auth)
|--------------------------------------------------------------------------
=======

/*
|--------------------------------------------------------------------------
| RRUGËT E MBROJTURA (Kërkojnë Login - Middleware 'auth')
|--------------------------------------------------------------------------
| Nëse përdoruesi nuk është i loguar, Laravel do e ridrejtojë te rruga 'login'.
>>>>>>> b2895cdc6b7460c56fa911f1092d1398cdbb86f5
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

<<<<<<< HEAD
    // User
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Verify email
    Route::get('/auth/verify-email', function (Request $request) {
        if ($request->query('verified') == 1) {
            Log::info('User logged in');
=======
    // Admin & User Management
    Route::resource('users', UserController::class)->except('store');

    Route::middleware('admin')->group(function () {
        Route::get('/admin', function () {
            return view('admin.overview');
        })->name('admin.overview');

        Route::view('/admin/statistics', 'admin.statistics')->name('admin.statistics');

        Route::get('/admin/users', function () {
            $users = User::latest()->get();
            return view('admin.users', ['users' => $users]);
        })->name('admin.users');
    });
    //USER
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');  
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

    // Auth Actions
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/auth/verify-email', function (Request $request) {
        if ($request->query('verified') == 1) {
            Log::info('User logged in');    
>>>>>>> b2895cdc6b7460c56fa911f1092d1398cdbb86f5
        }
        return redirect()->route('home');
    })->middleware('verified');

<<<<<<< HEAD
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

    // Statistics (nga DB)
    Route::get('/admin/statistics', [StatisticsController::class, 'index'])
        ->name('admin.statistics');

=======
    Route::post('/ratings/store', [RatingController::class, 'store'])->name('ratings.store');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // User Management
    Route::get('/admin/statistics', function () {
        return view('admin.statistics');
    })->name('admin.statistics');
    
>>>>>>> b2895cdc6b7460c56fa911f1092d1398cdbb86f5
    Route::get('/admin/users', function () {
        $users = User::latest()->get();
        return view('admin.users', ['users' => $users]);
    })->name('admin.users');
<<<<<<< HEAD
});

=======
});Route::get('/product/{product}', [ProductController::class, 'show'])
    ->name('product.show');
>>>>>>> b2895cdc6b7460c56fa911f1092d1398cdbb86f5
