<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['new', 'in_progress', 'completed', 'cancelled']),
            'priority' => $this->faker->randomElement(['high', 'normal', 'low']),
            'user_id' => User::factory(), 
        ];
    }
}
