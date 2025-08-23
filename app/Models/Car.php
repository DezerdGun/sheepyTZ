<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Car",
 *     type="object",
 *     title="Car",
 *     required={"plate_number", "car_model_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="plate_number", type="string", example="01A123BC"),
 *     @OA\Property(property="car_model_id", type="integer", example=2),
 *     @OA\Property(property="driver_id", type="integer", nullable=true, example=5),
 *     @OA\Property(
 *         property="car_model",
 *         ref="#/components/schemas/CarModel"
 *     ),
 *     @OA\Property(
 *         property="driver",
 *         ref="#/components/schemas/Driver"
 *     )
 * )
 */
class Car extends Model
{
    protected $fillable = ['plate_number', 'car_model_id', 'driver_id'];

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
