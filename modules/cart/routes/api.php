<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\CartController;
use Modules\Cart\Http\Controllers\CartItemController;

Route::middleware('api')->prefix('api')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('create', [CartItemController::class, 'store'])->name('cart_product_create');
        Route::put('edit/{product_id}', [CartItemController::class, 'update'])->name('cart_product_edit');
        Route::delete('delete/{product_id}', [CartItemController::class, 'destroy'])->name('cart_product_delete');
    });
});


