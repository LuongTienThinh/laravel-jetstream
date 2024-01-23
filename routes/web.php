<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/product', [ProductController::class, 'viewProduct'])->name('product_web');
    Route::get('/category', [CategoryController::class, 'viewCategory'])->name('category_web');
});

Route::get('/list-product', [ProductController::class, 'viewListProduct'])->name('list_product');
