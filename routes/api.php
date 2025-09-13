<?php

use App\Http\Controllers\Api\PingController;

Route::get('/ping', [PingController::class, 'index']);




