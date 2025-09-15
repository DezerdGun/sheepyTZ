<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskComment;
use App\Jobs\SendTaskNotificationJob;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

class TaskService
{
    public function createTask(array $data): Task
    {
        $user = isset($data['user_id']) ? User::find($data['user_id']) : null;

        if (!$user) {
            $user = User::where('position', 'manager')->firstOrFail();
        }

        $status = ($data['priority'] ?? 'normal') === 'high' ? 'in_progress' : 'new';

        $task = Task::create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'user_id'     => $user->id,
            'priority'    => $data['priority'] ?? 'normal',
            'status'      => $status,
        ]);

        if ($task->priority === 'high') {
            SendTaskNotificationJob::dispatch($task->id, 'task_assigned');
        }

        return $task;
    }

    public function updateStatus(int $taskId, array $data): Task
    {
        $task = Task::findOrFail($taskId);

        $task->status = $data['status'];
        $task->save();

        if ($task->status === 'completed') {
            $user = isset($data['user_id']) ? User::find($data['user_id']) : $task->user;
            $userName = $user?->name ?? 'System';

            TaskComment::create([
                'task_id' => $task->id,
                'user_id' => $user?->id ?? $task->user_id,
                'comment' => "Task completed by {$userName}",
            ]);
        }

        SendTaskNotificationJob::dispatch($task->id, 'status_changed');

        return $task;
    }

public function addComment(int $taskId, array $data): TaskComment
{
    $task = Task::find($taskId);
    if (!$task) {
        throw new ModelNotFoundException("Task not found");
    }
    if ($task->status === 'cancelled') {
        throw new InvalidArgumentException('Cannot add comment to cancelled task');
    }
    try {
        return TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $data['user_id'],
            'comment' => $data['comment'],
            'created_at' => now(),
        ]);
    } catch (\Exception $e) {
        \Log::error('Failed to create TaskComment: ' . $e->getMessage(), [
            'task_id' => $taskId,
            'data' => $data,
        ]);
        throw $e;
    }
}
    public function getTaskWithRelations(int $taskId): Task
    {
        return Task::with([
            'user:id,name,position',
            'comments.user:id,name,position'
        ])->findOrFail($taskId);
    }
}
