<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


    /**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Task",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="status", type="string", enum={"new","in_progress","completed","cancelled"}),
 *     @OA\Property(property="priority", type="string", enum={"high","normal","low"}),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'user_id', 'status', 'priority'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }
}
