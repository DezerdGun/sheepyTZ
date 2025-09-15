<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_all_tasks_without_filters()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertOk()
            ->assertJsonCount(3);
    }

    public function test_can_filter_tasks_by_status()
    {
        Task::factory()->create(['status' => 'new']);
        Task::factory()->create(['status' => 'completed']);

        $response = $this->getJson('/api/tasks?status=new');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['status' => 'new']);
    }

    public function test_can_filter_tasks_by_priority()
    {
        Task::factory()->create(['priority' => 'high']);
        Task::factory()->create(['priority' => 'low']);

        $response = $this->getJson('/api/tasks?priority=low');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['priority' => 'low']);
    }

    public function test_can_filter_tasks_by_user_id()
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id]);
        Task::factory()->create(['user_id' => null]);

        $response = $this->getJson("/api/tasks?user_id={$user->id}");

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['user_id' => $user->id]);
    }

    public function test_returns_validation_error_for_invalid_status()
    {
        $response = $this->getJson('/api/tasks?status=wrong_status');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_returns_validation_error_for_non_existent_user()
    {
        $response = $this->getJson('/api/tasks?user_id=9999');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    }
}
