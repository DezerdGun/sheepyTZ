<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Driver;

class CarsTableSeeder extends Seeder
{
    public function run()
    {
        $cm1 = CarModel::where('name','Toyota Camry')->first();
        $cm2 = CarModel::where('name','Hyundai Accent')->first();
        $d1 = Driver::first();
        $d2 = Driver::skip(1)->first();
        if ($cm1) Car::firstOrCreate(['plate_number'=>'01A123BC'], ['car_model_id'=>$cm1->id, 'driver_id'=>$d1->id ?? null]);
        if ($cm2) Car::firstOrCreate(['plate_number'=>'02B456DE'], ['car_model_id'=>$cm2->id, 'driver_id'=>$d2->id ?? null]);
    }
}
