<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarModel;
use App\Models\Category;

class CarModelsTableSeeder extends Seeder
{
    public function run()
    {
        $cat1 = Category::where('name','Первая')->first();
        $cat2 = Category::where('name','Вторая')->first();
        CarModel::firstOrCreate(['name'=>'Toyota Camry','category_id'=>$cat1->id ?? null]);
        CarModel::firstOrCreate(['name'=>'Hyundai Accent','category_id'=>$cat2->id ?? null]);
    }
}
