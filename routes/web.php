<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin dashboard (public for testing)
Route::get('/admin', function () {
    return view('admin.dashboard');
});

// Public admin page for testing without authentication (temporary)
Route::get('/admin/available-cars', [\App\Http\Controllers\Admin\AvailableCarsController::class, 'index']);
Route::get('/admin/available-cars/data', [\App\Http\Controllers\Admin\AvailableCarsController::class, 'available']);

// Admin CRUD for categories
Route::prefix('admin')->name('admin.')->group(function(){
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
    Route::resource('car-models', \App\Http\Controllers\Admin\CarModelController::class)->parameters(['car-models' => 'carModel'])->except(['show']);
    Route::resource('cars', \App\Http\Controllers\Admin\CarController::class);
    Route::resource('drivers', \App\Http\Controllers\Admin\DriverController::class);
    Route::resource('positions', \App\Http\Controllers\Admin\PositionController::class)->except(['show']);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->except(['show']);
});

// Minimal login route so auth middleware can redirect when no session exists.
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
