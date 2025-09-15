<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskAddCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_404_if_task_not_found()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/tasks/999/comments', [
            'comment' => 'Test comment',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Task not found']);
    }

    public function test_validates_request_data()
    {
        $task = Task::factory()->create();

        $response = $this->postJson("/api/tasks/{$task->id}/comments", [
            'user_id' => null,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('comment');
    }

    public function test_cannot_add_comment_to_cancelled_task()
    {
        $task = Task::factory()->create(['status' => 'cancelled']);
        $user = User::factory()->create();

        $response = $this->postJson("/api/tasks/{$task->id}/comments", [
            'comment' => 'Test comment',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(500)
                 ->assertJson(['message' => 'Cannot add comment to cancelled task']);
    }

    public function test_creates_comment_successfully()
    {
        $task = Task::factory()->create(['status' => 'new']);
        $user = User::factory()->create();

        $response = $this->postJson("/api/tasks/{$task->id}/comments", [
            'comment' => 'Test comment',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'task_id',
                     'user_id',
                     'comment',
                     
                 ]);

        $this->assertDatabaseHas('task_comments', [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => 'Test comment',
        ]);
    }
}
