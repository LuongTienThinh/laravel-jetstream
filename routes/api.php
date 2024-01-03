<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('product')->group(function () {
    Route::post('create', [ProductController::class, 'store'])->name('product_create');
    Route::put('edit/{id}', [ProductController::class, 'update'])->name('product_edit');
    Route::delete('delete/{id}', [ProductController::class, 'destroy'])->name('product_delete');
    Route::get('get-list', [ProductController::class, 'index'])->name('product');
});

Route::prefix('category')->group(function () {
    Route::post('create', [CategoryController::class, 'store'])->name('category_create');
    Route::put('edit/{id}', [CategoryController::class, 'update'])->name('category_edit');
    Route::delete('delete/{id}', [CategoryController::class, 'destroy'])->name('category_delete');
    Route::get('/', [CategoryController::class, 'index'])->name('category');
});
