<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

Route::middleware('web')->group(function () {
    Route::get('/cart', [CartController::class, 'viewCartDetail'])->name('cart');
});
