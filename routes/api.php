<?php

use App\Http\Controllers\Api\PingController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/ping', PingController::class);


Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);

// Register /api/cars. Use auth:sanctum when the guard is configured, otherwise
// fall back to an unauthenticated route so local/dev Swagger Try-it works.
if (config('auth.guards.sanctum')) {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/cars', [CarController::class, 'index']);
    });
} else {
    // Sanctum guard not available in this environment — register public route
    Route::get('/cars', [CarController::class, 'index']);
}

// Public testing endpoint (no auth) — useful for Swagger Try-it in local/dev only
Route::get('/cars-public', [CarController::class, 'index']);

// Route::get('/available-cars', [CarController::class, 'available']);
