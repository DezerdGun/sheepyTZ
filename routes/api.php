<?php

use App\Http\Controllers\Api\PingController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/ping', PingController::class);


Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cars', [CarController::class, 'index']);
});

// Route::get('/available-cars', [CarController::class, 'available']);
