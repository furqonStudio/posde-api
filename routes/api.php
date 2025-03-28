<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('stores', StoreController::class);
Route::apiResource('users', UserController::class);
Route::get('/stores/{storeId}/users', [StoreController::class, 'getUsersByStore']);
Route::post('/stores/{storeId}/assign-user', [StoreController::class, 'assignUserToStore']);
Route::get('/business-types', [StoreController::class, 'getBusinessTypes']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/validate-token', [AuthController::class, 'validateToken']);
