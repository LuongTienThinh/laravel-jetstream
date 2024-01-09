<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\src\Http\Controllers\CartController;

Route::middleware([
    'auth',
    'web'
])->group(function () {
    Route::get('/cart', function () {
        return view('Modules-Cart::cart-detail');
    })->name('cart');
});
