<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [ApiAuthController::class, 'register']);
// Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/login', [ApiAuthController::class, 'login'])->name('login'); //jika sering dirujuk, gunakan name()
// Route::middleware('auth:api')->post('/logout', [ApiAuthController::class, 'logout']);
// Route::middleware('auth:api')->get('/check-auth', [ApiAuthController::class, 'checkAuth']);

Route::middleware('auth.api')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/check-auth', [ApiAuthController::class, 'checkAuth']);
});

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('stores', StoreController::class);
