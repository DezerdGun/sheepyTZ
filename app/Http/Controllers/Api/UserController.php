<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Список сотрудников",
     *     @OA\Response(response=200, description="Список сотрудников", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Employee")))
     * )
     */
    public function index()
    {
        return User::all();
    }

}