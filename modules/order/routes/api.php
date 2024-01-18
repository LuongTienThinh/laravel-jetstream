<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Modules\Order\Http\Controllers\OrderItemController;
use Modules\Order\Http\Controllers\PaymentMethodController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('payment')->group(function () {
        Route::get('/method', [PaymentMethodController::class, 'index']);
        Route::post('/create-order', [OrderItemController::class, 'store']);
    });
});


