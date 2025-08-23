<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Driver;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        return [
            'plate_number' => strtoupper($this->faker->bothify('??###??')),
            'car_model_id' => CarModel::factory(),
            'driver_id' => Driver::factory(),
        ];
    }
}
