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

        // ensure the user has a position and allowed categories
        $allowedCategoryIds = $user->position?->categories->pluck('id')->toArray() ?? [];

        $query = Car::query()
            ->whereHas('carModel.category', function ($q) use ($allowedCategoryIds) {
                if (!empty($allowedCategoryIds)) {
                    $q->whereIn('id', $allowedCategoryIds);
                }
            })
            // exclude cars that have bookings overlapping requested interval
            ->whereDoesntHave('bookings', function ($q) use ($request) {
                if ($request->filled('start_time') && $request->filled('end_time')) {
                    $start = $request->start_time;
                    $end = $request->end_time;

                    // overlap if booking.start < requested_end AND booking.end > requested_start
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
