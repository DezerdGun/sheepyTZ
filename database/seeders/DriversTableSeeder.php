<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriversTableSeeder extends Seeder
{
    public function run()
    {
        Driver::firstOrCreate(['name'=>'Karis','phone'=>'+998901234567']);
        Driver::firstOrCreate(['name'=>'Oris','phone'=>'+998901234568']);
    }
}
