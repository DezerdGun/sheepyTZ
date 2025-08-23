<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Driver",
 *     type="object",
 *     title="Driver",
 *     required={"name"},
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="name", type="string", example="Иванов Иван Иванович"),
 *     @OA\Property(property="phone", type="string", example="+998901234567")
 * )
 */
class Driver extends Model
{
    protected $fillable = ['name', 'phone'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
