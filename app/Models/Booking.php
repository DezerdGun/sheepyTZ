<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    /**
     * @OA\Schema(
     *     schema="Booking",
     *     type="object",
     *     required={"user_id","car_id","start_time","end_time"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="user_id", type="integer", example=1),
     *     @OA\Property(property="car_id", type="integer", example=2),
     *     @OA\Property(property="start_time", type="string", format="date-time", example="2025-08-23T09:00:00Z"),
     *     @OA\Property(property="end_time", type="string", format="date-time", example="2025-08-23T12:00:00Z")
     * )
     */
    protected $fillable = ['user_id', 'car_id', 'start_time', 'end_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
