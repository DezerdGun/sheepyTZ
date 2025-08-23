<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/users",
 *     summary="Get list of users",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation"
 *     )
 * )
 */
public function index()
{
    return User::all();
}

}