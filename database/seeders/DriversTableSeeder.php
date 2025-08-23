<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriversTableSeeder extends Seeder
{
    public function run()
    {
        Driver::firstOrCreate(['name'=>'Иванов Иван','phone'=>'+998901234567']);
        Driver::firstOrCreate(['name'=>'Петров Петр','phone'=>'+998901234568']);
    }
}
