<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/checkout', function () {
        return view('Modules-Order::checkout');
    })->name('checkout');
});
