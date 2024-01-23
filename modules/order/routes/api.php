<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;
use Modules\Order\Http\Controllers\OrderItemController;
use Modules\Order\Http\Controllers\PaymentMethodController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('payment')->group(function () {
        Route::get('/method', [PaymentMethodController::class, 'index']);
        Route::post('/create-order', [OrderItemController::class, 'store']);
    });

    Route::prefix('order')->group(function () {
        Route::get('/list-order', [OrderController::class, 'getListOrders']);
        Route::get('/order-detail/{id}', [OrderController::class, 'getOrderDetails']);
    });
});


