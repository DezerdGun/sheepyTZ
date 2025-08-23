<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     summary="Получить список броней",
     *     @OA\Response(
     *         response=200,
     *         description="Список броней",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Booking"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $bookings = Booking::where('user_id', $user->id)->with(['car', 'user'])->get();
        } else {
            $bookings = Booking::with(['car', 'user'])->get();
        }

        return response()->json($bookings);
    }

    /**
     * @OA\Post(
     *     path="/api/bookings",
     *     tags={"Bookings"},
     *     summary="Создать бронь",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Booking")
     *     ),
     *     @OA\Response(response=201, description="Бронь создана", @OA\JsonContent(ref="#/components/schemas/Booking")),
     *     @OA\Response(response=422, description="Conflict or validation error")
     * )
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->only(['user_id', 'car_id', 'start_time', 'end_time']);

        if ($user) {
            $data['user_id'] = $user->id;
        }

        $userIdRule = $user ? 'required|integer' : 'required|integer|exists:users,id';

        $validator = Validator::make($data, [
            'user_id' => $userIdRule,
            'car_id' => 'required|integer|exists:cars,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $start = $data['start_time'];
        $end = $data['end_time'];

        $conflict = Booking::where('car_id', $data['car_id'])
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($conflict) {
            return response()->json(['message' => 'Car is already booked for the requested interval'], 422);
        }

        try {
            $booking = Booking::create($data);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'message' => 'Failed to create booking: invalid related record or constraint violation',
                'error' => $ex->getMessage(),
            ], 422);
        }

        return response()->json($booking, 201);
    }
}
