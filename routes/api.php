<?php

use App\Http\Controllers\Api\PingController;
use App\Http\Controllers\Api\TaskController;

Route::get('/ping', [PingController::class, 'index']);

Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
Route::post('/tasks/{id}/comments', [TaskController::class, 'addComment']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);


