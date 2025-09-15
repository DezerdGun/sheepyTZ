<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function health(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }
}
