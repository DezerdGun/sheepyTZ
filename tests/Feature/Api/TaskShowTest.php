<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskComment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_404_if_task_not_found()
    {
        $response = $this->getJson('/api/tasks/999');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Task not found']);
    }

    public function test_returns_task_with_user_and_comments()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $commentUser = User::factory()->create();
        $comment = TaskComment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $commentUser->id,
            'comment' => 'Test comment',
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $task->id,
                     'name' => $user->name,
                     'position' => $user->position,
                 ])
                 ->assertJsonFragment([
                     'id' => $comment->id,
                     'comment' => 'Test comment',
                     'name' => $commentUser->name,
                 ]);
    }
}
