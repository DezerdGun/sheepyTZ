<?php

use App\Http\Controllers\Api\PingController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/ping', PingController::class);


Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
if (config('auth.guards.sanctum')) {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/cars', [CarController::class, 'index']);
    });
} else {
    Route::get('/cars', [CarController::class, 'index']);
}
Route::get('/cars-public', [CarController::class, 'index']);

if (config('auth.guards.sanctum')) {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'index']);
        Route::post('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'store']);
    });
} else {
    Route::get('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'index']);
    Route::post('/bookings', [\App\Http\Controllers\Api\BookingController::class, 'store']);
}

if (config('auth.guards.sanctum')) {
    Route::middleware('auth:sanctum')->get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
} else {
    Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
}

// Route::get('/available-cars', [CarController::class, 'available']);
