<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;
    /**
     * @OA\Schema(
     *     schema="Position",
     *     type="object",
     *     required={"name"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Менеджер"),
     *     @OA\Property(
     *         property="categories",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/Category")
     *     )
     * )
     */
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_position');
    }
}
