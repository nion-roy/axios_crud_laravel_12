<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
Route::get('get-products', [ProductController::class, 'getProducts'])->name('products.getProducts');
