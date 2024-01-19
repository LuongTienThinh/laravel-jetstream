<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware('web')->group(function () {
    Route::get('/checkout', [OrderController::class, 'viewOrder'])->name('checkout');
});
