<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware('web')->group(function () {
    Route::get('/checkout', [OrderController::class, 'viewCheckout'])->name('checkout');

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'viewOrder'])->name('order');
        Route::get('/order-detail/{id?}', [OrderController::class, 'viewOrderDetail'])->name('order-detail');
    });
});
