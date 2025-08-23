<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking;
use App\Models\User;
use App\Models\Car;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'car_id' => Car::factory(),
            'start_time' => $this->faker->dateTimeBetween('+1 hour', '+2 hours'),
            'end_time' => $this->faker->dateTimeBetween('+2 hours', '+4 hours'),
        ];
    }
}
