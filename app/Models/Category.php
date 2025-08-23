<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
