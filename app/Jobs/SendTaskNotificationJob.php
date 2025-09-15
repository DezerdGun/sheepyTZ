<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTaskNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $taskId;
    protected string $notificationType;

    /**
     * Create a new job instance.
     *
     * @param int $taskId
     * @param string $notificationType
     */
    public function __construct(int $taskId, string $notificationType)
    {
        $this->taskId = $taskId;
        $this->notificationType = $notificationType;
    }

    /**
     * Getters for testing
     */
    public function getTaskId(): int
    {
        return $this->taskId;
    }

    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $task = Task::find($this->taskId);

        if (!$task) {
            Log::warning("SendTaskNotificationJob: Task {$this->taskId} not found.");
            return;
        }

        $managers = User::where('position', 'manager')->get();

        foreach ($managers as $manager) {
            TaskNotification::create([
                'task_id' => $task->id,
                'user_id' => $manager->id,
                'message' => $this->generateMessage($task),
            ]);

            Log::info("Notification to Manager {$manager->id}: " . $this->generateMessage($task));
        }
    }

    /**
     * Generate notification message
     */
    protected function generateMessage(Task $task): string
    {
        return match ($this->notificationType) {
            'status_changed' => "Task '{$task->title}' status changed to {$task->status}.",
            'task_assigned' => "High priority task '{$task->title}' assigned to user ID {$task->user_id}.",
            'overdue' => "Task '{$task->title}' is overdue!",
            default => "Notification for task '{$task->title}'.",
        };
    }
}
