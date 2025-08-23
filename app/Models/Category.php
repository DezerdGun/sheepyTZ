<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     title="Category",
 *     required={"name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Комфорт")
 * )
 */
class Category extends Model
{
    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_category');
    }

    public function carModels()
    {
        return $this->hasMany(CarModel::class);
    }
}
