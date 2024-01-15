<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

Route::middleware('web')->group(function () {
    Route::get('/cart', function () {
        return view('Modules-Cart::cart-detail');
    })->name('cart');

    Route::get('/checkout', function () {
        return view('Modules-Cart::checkout');
    })->name('checkout');
});
