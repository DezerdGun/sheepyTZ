<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $names = ['Первая','Вторая','Третья'];
        foreach($names as $n){
            Category::firstOrCreate(['name'=>$n]);
        }
    }
}
