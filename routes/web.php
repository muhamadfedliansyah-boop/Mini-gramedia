<?php

use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SubscriptionPackageController;
use App\Http\Controllers\SubscriptionPackageBookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/category/{id}', [HomeController::class, 'category'])->name('category.show');
Route::get('/kategori/{id}', [HomeController::class, 'category']);

// Route Detail Paket Langganan untuk User / Publik
Route::get('/subscription-package/{subscriptionPackage}', [HomeController::class, 'subscriptionPackageDetail'])
    ->name('subscription-package.show');
Route::get('/subscription-package/{subscriptionPackage}/detail', [HomeController::class, 'subscriptionPackageDetail'])
    ->name('subscription-packages.subscription_package_detail');
Route::get('/paket-langganan/{subscriptionPackage}', [HomeController::class, 'subscriptionPackageDetail']);

// Route Detail Buku untuk User / Publik
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

// Routes untuk User yang sudah login
Route::middleware(['IsLoggedIn'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/profil', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile', [UserController::class, 'profile'])->name('profil');

    Route::get('/subscription-packages/{id}/checkout', [SubscriptionPackageController::class, 'checkoutPage'])
        ->name('subscription-packages.checkout.page');
    Route::get('/subscription-package/{id}/checkout', [SubscriptionPackageController::class, 'checkoutPage'])
        ->name('user.subscription-package.checkout.page');
    Route::post('/subscription-packages/{id}/checkout', [SubscriptionPackageController::class, 'checkout'])
        ->name('subscription-packages.checkout');
    Route::post('/subscription-package/{id}/checkout', [SubscriptionPackageController::class, 'checkout'])
        ->name('user.subscription-package.checkout');
    Route::get('/carts', [CartController::class, 'index'])->name('carts');
    Route::post('/carts', [CartController::class, 'store'])->name('carts.store');
    Route::delete('/carts/{id}', [CartController::class, 'destroy'])->name('carts.destroy');

    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/{id}', [CheckoutController::class, 'show'])->name('checkout.show');

    Route::resource('books', BookController::class)->except(['show']);

    // Routes Khusus Admin
    Route::prefix('admin')->name('admin.')->middleware('IsAdmin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('book-categories/export', [BookCategoryController::class, 'export'])->name('book-categories.export');
        Route::resource('book-categories', BookCategoryController::class);

        Route::get('books/export', [BookController::class, 'export'])->name('books.export');
        Route::resource('books', BookController::class);

        Route::get('paket-langganan/export', [SubscriptionPackageController::class, 'export'])->name('paket-langganan.export');
        Route::resource('paket-langganan', SubscriptionPackageController::class);

        // Manajemen Buku di dalam Paket Langganan (Admin)
        Route::get('subscription-package/{subscriptionPackage}/books', [SubscriptionPackageBookController::class, 'index'])
            ->name('subscription-packages.books');
        Route::post('subscription-package/{subscriptionPackage}/books', [SubscriptionPackageBookController::class, 'store'])
            ->name('subscription-packages.books.store');
        Route::post('subscription-package/{subscriptionPackage}/books/create', [SubscriptionPackageBookController::class, 'store'])
            ->name('subscription-packages.books.create');
    });
});

// Routes untuk Tamu (Guest)
Route::middleware(['IsGuest'])->group(function () {
    Route::get('/register', function () {
        return view('register');
    })->name('register');

    Route::post('/register', [UserController::class, 'register'])->name('register.store')
        ->middleware('throttle:5,1');

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [UserController::class, 'login'])->name('login.store')
        ->middleware('throttle:5,1');
});

