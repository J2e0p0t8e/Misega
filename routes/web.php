<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Home & Catalog routes
Route::get('/', [MangaController::class, 'home'])->name('home');
Route::get('/catalogue', [MangaController::class, 'catalog'])->name('catalogue');
Route::get('/catalog', [MangaController::class, 'catalog'])->name('catalog');
Route::get('/manga/{id}', [MangaController::class, 'show'])->name('manga.show');

// Cart & Checkout routes
Route::get('/cart', fn () => Inertia::render('Cart'))->name('cart');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('orders.success');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('orders.index'))->name('dashboard');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
