<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;
use Modules\Cart\Http\Controllers\CartItemController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('create', [CartItemController::class, 'store'])->name('cart_item_create');
        Route::put('edit/{cart_item_id}', [CartItemController::class, 'update'])->name('cart_item_edit');
        Route::delete('delete/{cart_item_id}', [CartItemController::class, 'destroy'])->name('cart_item_delete');
    });
});


