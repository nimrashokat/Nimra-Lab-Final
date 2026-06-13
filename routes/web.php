<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Public landing page: only login options
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }
        return redirect('/home');
    }
    return view('landing');
})->name('landing');

// Authenticated home (after login)
Route::middleware('auth')->get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/size-chart', [HomeController::class, 'sizeChart'])->name('size.chart');

Route::controller(ShopController::class)->group(function () {
    Route::get('/shop', 'index')->name('shop.index');
    Route::get('/category/{slug}', 'category')->name('shop.category');
    Route::get('/product/{product:slug}', 'show')->name('shop.show');
    Route::get('/chat', fn () => view('shop.chat'))->name('shop.chat');
});

Route::middleware('auth')->group(function () {
    Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/order', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/products', AdminProductController::class);
        Route::resource('/orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
    });

require __DIR__ . '/auth.php';
