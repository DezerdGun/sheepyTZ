<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Company Fleet API",
 *     description="API для служебных автомобилей"
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local dev server"
 * )
 */
class PingController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/ping",
     *   summary="Проверка доступности API",
     *   tags={"Health"},
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="ok")
     *     )
     *   )
     * )
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }
}
