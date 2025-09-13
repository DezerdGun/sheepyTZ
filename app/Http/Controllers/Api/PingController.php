<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class PingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/ping",
     *     summary="Check API ping",
     *     @OA\Response(
     *         response=200,
     *         description="API is alive"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(['pong' => true]);
    }
}
