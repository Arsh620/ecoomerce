<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::controller(ProductController::class)->group(function () {
    Route::post('/create-products', 'store');
    Route::get('/get-all-products', 'index');
    Route::get('/get-product-by-id', 'getById');
    Route::put('/update-product-by-id', 'update');
    Route::post('/delete-product-by-id', 'destroy');
});
