<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\CarModel;
use App\Models\Car;
use App\Models\Booking;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    public function test_ping_endpoint_returns_ok()
    {
        $response = $this->getJson('/api/ping');
        $response->assertStatus(200)
            ->assertJson(['status' => 'ok']);
    }


    public function test_get_categories_returns_list()
    {
        Category::factory()->count(2)->create();
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name']
            ]);
    }

    public function test_post_category_creates_category()
    {
        $response = $this->postJson('/api/categories', ['name' => 'Эконом']);
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name']);
    }

    public function test_post_category_validation_error()
    {
        $response = $this->postJson('/api/categories', []);
        $response->assertStatus(422);
    }

    public function test_get_users_returns_list()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $role->id]);
        $response = $this->getJson('/api/users');
        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id, 'name' => $user->name]);
    }


    public function test_get_cars_returns_list()
    {
        $category = Category::factory()->create();
        $carModel = CarModel::factory()->create(['category_id' => $category->id]);
        $car = Car::factory()->create(['car_model_id' => $carModel->id]);
        $response = $this->getJson('/api/cars-public');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'plate_number', 'car_model_id', 'available', 'next_available_time']
            ]);
    }

    public function test_get_bookings_returns_list()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create();
        Booking::factory()->create(['user_id' => $user->id, 'car_id' => $car->id]);
        $response = $this->getJson('/api/bookings');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'user_id', 'car_id', 'start_time', 'end_time']
            ]);
    }

    public function test_post_booking_creates_booking()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create();
        $payload = [
            'user_id' => $user->id,
            'car_id' => $car->id,
            'start_time' => now()->addHour()->toIso8601String(),
            'end_time' => now()->addHours(2)->toIso8601String(),
        ];
        $response = $this->postJson('/api/bookings', $payload);
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'user_id', 'car_id', 'start_time', 'end_time']);
    }

    public function test_post_booking_conflict_returns_422()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create();
        Booking::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
        ]);
        $payload = [
            'user_id' => $user->id,
            'car_id' => $car->id,
            'start_time' => now()->addHour()->toIso8601String(),
            'end_time' => now()->addHours(2)->toIso8601String(),
        ];
        $response = $this->postJson('/api/bookings', $payload);
        $response->assertStatus(422);
    }

    public function test_post_booking_validation_error()
    {
        $response = $this->postJson('/api/bookings', []);
        $response->assertStatus(422);
    }

    
}
