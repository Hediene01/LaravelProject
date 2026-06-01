<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SavedCardController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/products', [StorefrontController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [StorefrontController::class, 'show'])->name('products.show');

Route::middleware('guest')->group(function () {
    Route::get('/sign-up', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/sign-up', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/account/cards', [SavedCardController::class, 'store'])->name('account.cards.store');
    Route::patch('/account/cards/{savedCard}', [SavedCardController::class, 'update'])->name('account.cards.update');
    Route::delete('/account/cards/{savedCard}', [SavedCardController::class, 'destroy'])->name('account.cards.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::prefix('product')
            ->name('product.')
            ->controller(AdminProductController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{product}', 'show')->name('show');
                Route::get('/edit/{product}', 'edit')->name('edit');
                Route::put('/update/{product}', 'update')->name('update');
                Route::delete('/delete/{product}', 'destroy')->name('destroy');
            });

        Route::prefix('categories')
            ->name('categories.')
            ->controller(AdminCategoryController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::put('/update/{category}', 'update')->name('update');
                Route::delete('/delete/{category}', 'destroy')->name('destroy');
            });

        Route::prefix('brands')
            ->name('brands.')
            ->controller(AdminBrandController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::put('/update/{brand}', 'update')->name('update');
                Route::delete('/delete/{brand}', 'destroy')->name('destroy');
            });

        Route::prefix('orders')
            ->name('orders.')
            ->controller(AdminOrderController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/show/{order}', 'show')->name('show');
                Route::patch('/update/{order}', 'update')->name('update');
            });

        Route::prefix('reviews')
            ->name('reviews.')
            ->controller(AdminReviewController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::patch('/update/{review}', 'update')->name('update');
            });
    });
