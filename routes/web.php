<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/api/health', function () {
    return response()->json(['status' => 'ok']);
});
