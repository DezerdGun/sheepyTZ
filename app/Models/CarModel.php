<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="CarModel",
 *     type="object",
 *     title="CarModel",
 *     required={"name", "category_id"},
 *     @OA\Property(property="id", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="Toyota Camry"),
 *     @OA\Property(property="category_id", type="integer", example=1),
 *     @OA\Property(
 *         property="comfort_category",
 *         ref="#/components/schemas/Category"
 *     )
 * )
 */
class CarModel extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
