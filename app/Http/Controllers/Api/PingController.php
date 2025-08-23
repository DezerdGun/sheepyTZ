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
 *     url="http://localhost:8080",
 *     description="Local dev server"
 * )
 * @OA\Tag(
 *     name="Health",
 *     description="Запросы для проверки состояния API"
 * )
 */
class PingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/ping",
     *     tags={"Health"},
     *     summary="Проверка доступности API",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="ok")
     *         )
     *     )
     * )
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }
}
