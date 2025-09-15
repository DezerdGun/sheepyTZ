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
            ->assertJson(['pong' => true]);
    }
    
}
