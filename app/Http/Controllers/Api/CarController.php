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
    *         name="model_id",
    *         in="query",
    *         description="ID модели автомобиля (car_model_id)",
    *         required=false,
    *         @OA\Schema(type="integer", format="int64")
    *     ),
    *     @OA\Parameter(
    *         name="category_id",
    *         in="query",
    *         description="ID категории комфорта",
    *         required=false,
    *         @OA\Schema(type="integer", format="int64")
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

        // support unauthenticated requests (e.g. public Swagger Try-it)
        $allowedCategoryIds = [];
        if ($user && $user->position) {
            $allowedCategoryIds = $user->position->categories->pluck('id')->toArray();
        }

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

        if ($request->filled('model_id')) {
            $query->where('car_model_id', $request->model_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('carModel.category', fn($q) => $q->where('id', $request->category_id));
        }

        $cars = $query->with(['driver', 'carModel.category', 'bookings'])->get();

        // evaluate availability per car
        $start = $request->filled('start_time') ? $request->start_time : null;
        $end = $request->filled('end_time') ? $request->end_time : null;

        $result = $cars->map(function ($car) use ($start, $end) {
            $isAvailable = true;
            $nextAvailable = null;

            if ($start && $end) {
                // any booking that overlaps requested interval => not available
                $overlap = $car->bookings->first(function ($b) use ($start, $end) {
                    return ($b->start_time < $end) && ($b->end_time > $start);
                });

                if ($overlap) {
                    $isAvailable = false;
                    // compute next available time as the latest end_time of bookings overlapping or starting before end
                    $next = $car->bookings->where('end_time', '>', $start)->sortByDesc('end_time')->first();
                    $nextAvailable = $next?->end_time?->toIso8601String() ?? null;
                }
            }

            $payload = $car->toArray();
            $payload['available'] = $isAvailable;
            $payload['next_available_time'] = $nextAvailable;

            // remove bookings from payload to keep response small
            unset($payload['bookings']);

            return $payload;
        });

        return response()->json($result->values());
    }
}
