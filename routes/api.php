<?php

use App\Http\Controllers\Api\PingController;
use App\Http\Controllers\Api\CarController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cars', [CarController::class, 'index']);
});

// Route::get('/available-cars', [CarController::class, 'available']);
// Route::get('/ping', PingController::class);
