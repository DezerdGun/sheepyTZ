<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_task_with_valid_data()
    {
        $manager = User::factory()->create(['position' => 'manager']);

        $payload = [
            'title' => 'Fix login bug',
            'description' => 'Login fails on Safari',
            'priority' => 'normal',
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertCreated()
                 ->assertJsonFragment([
                     'title' => 'Fix login bug',
                     'priority' => 'normal',
                     'status' => 'new',
                     'user_id' => $manager->id,
                 ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Fix login bug',
            'status' => 'new',
            'user_id' => $manager->id,
        ]);
    }

    public function test_assigns_existing_user_if_user_id_provided()
    {
        $manager = User::factory()->create(['position' => 'manager']);
        $developer = User::factory()->create(['position' => 'developer']);

        $payload = [
            'title' => 'Fix dashboard UI',
            'user_id' => $developer->id,
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertCreated()
                 ->assertJsonFragment(['user_id' => $developer->id]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Fix dashboard UI',
            'user_id' => $developer->id,
        ]);
    }

    public function test_sets_status_to_in_progress_when_priority_is_high()
    {
        $manager = User::factory()->create(['position' => 'manager']);

        $payload = [
            'title' => 'Critical bug',
            'priority' => 'high',
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertCreated()
                 ->assertJsonFragment(['status' => 'in_progress']);
    }

    public function test_returns_validation_error_when_title_missing()
    {
        $response = $this->postJson('/api/tasks', [
            'priority' => 'high'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_returns_validation_error_for_invalid_user_id()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Test task',
            'user_id' => 999999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }
}
