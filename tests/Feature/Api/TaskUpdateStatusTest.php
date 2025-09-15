<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskComment;
use App\Jobs\SendTaskNotificationJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

class TaskUpdateStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_404_if_task_not_found()
    {
        $response = $this->putJson('/api/tasks/999/status', [
            'status' => 'completed',
        ]);

        $response->assertStatus(404)
                 ->assertJson(['message' => 'Task not found']);
    }

    public function test_validates_request_data()
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/tasks/{$task->id}/status", [
            'status' => 'invalid_status',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('status');
    }

    public function test_updates_task_status()
    {
        $task = Task::factory()->create(['status' => 'new']);

        $response = $this->putJson("/api/tasks/{$task->id}/status", [
            'status' => 'in_progress',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'in_progress']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_creates_comment_when_task_completed()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => 'in_progress']);

        $response = $this->putJson("/api/tasks/{$task->id}/status", [
            'status' => 'completed',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('task_comments', [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => "Task completed by {$user->name}",
        ]);
    }

    public function test_dispatches_notification_job()
    {
        Queue::fake();

        $task = Task::factory()->create(['status' => 'new']);

        $this->putJson("/api/tasks/{$task->id}/status", [
            'status' => 'in_progress',
        ]);

        Queue::assertPushed(SendTaskNotificationJob::class, function ($job) use ($task) {
            return $job->getTaskId() === $task->id
                   && $job->getNotificationType() === 'status_changed';
        });
    }
}
