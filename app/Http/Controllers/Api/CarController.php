<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cars",
     *     summary="Получить доступные автомобили",
     *     description="Возвращает список машин, доступных текущему пользователю для поездки",
     *     tags={"Cars"},
    *     @OA\Parameter(
    *         name="model",
    *         in="query",
    *         description="Фильтр по модели авто",
    *         required=false,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="category",
    *         in="query",
    *         description="Фильтр по категории комфорта",
    *         required=false,
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="start_time",
    *         in="query",
    *         description="Запланированное начало поездки (ISO 8601, например 2025-08-21T09:00:00)",
    *         required=false,
    *         @OA\Schema(type="string", format="date-time")
    *     ),
    *     @OA\Parameter(
    *         name="end_time",
    *         in="query",
    *         description="Запланированный конец поездки (ISO 8601)",
    *         required=false,
    *         @OA\Schema(type="string", format="date-time")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Список доступных автомобилей",
    *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Car"))
    *     ),
    *     security={{"bearerAuth": {}}}
     * )
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $allowedCategoryIds = $user->position?->categories->pluck('id')->toArray() ?? [];

        $query = Car::query()
            ->whereHas('carModel.category', function ($q) use ($allowedCategoryIds) {
                if (!empty($allowedCategoryIds)) {
                    $q->whereIn('id', $allowedCategoryIds);
                }
            })
            ->whereDoesntHave('bookings', function ($q) use ($request) {
                if ($request->filled('start_time') && $request->filled('end_time')) {
                    $start = $request->start_time;
                    $end = $request->end_time;

                    $q->where(function ($qq) use ($start, $end) {
                        $qq->where('start_time', '<', $end)
                           ->where('end_time', '>', $start);
                    });
                }
            });

        if ($request->filled('model')) {
            $query->whereHas('carModel', function ($q) use ($request) {
                $q->where('name', 'ILIKE', "%{$request->model}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('carModel.category', fn($q) => $q->where('name', $request->category));
        }

        return response()->json($query->with(['driver', 'carModel.category'])->get());
    }
}
