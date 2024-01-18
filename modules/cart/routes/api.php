<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\CartController;
use Modules\Cart\Http\Controllers\CartItemController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('create', [CartItemController::class, 'store'])->name('cart_product_create');
        Route::put('edit/{cart_id}-{product_id}', [CartItemController::class, 'update'])->name('cart_product_edit');
        Route::delete('delete/{cart_id}-{product_id}', [CartItemController::class, 'destroy'])->name('cart_product_delete');
    });
});


