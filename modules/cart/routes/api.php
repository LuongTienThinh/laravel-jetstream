<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\src\Http\Controllers\CartController;

Route::get('cart/', [CartController::class, 'index']);
Route::get('cart/sub/', [CartController::class, 'index']);
