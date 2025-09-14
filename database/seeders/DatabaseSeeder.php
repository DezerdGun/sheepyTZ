<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskNotification;
use App\Jobs\SendTaskNotificationJob;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'position' => 'manager',
            ]
        );

        $developer = User::firstOrCreate(
            ['email' => 'dev@example.com'],
            [
                'name' => 'Developer User',
                'position' => 'developer',
            ]
        );

        $tester = User::firstOrCreate(
            ['email' => 'tester@example.com'],
            [
                'name' => 'Tester User',
                'position' => 'tester',
            ]
        );

        $task = Task::firstOrCreate(
            ['title' => 'Initial Task'],
            [
                'description' => 'This is a seeded task',
                'user_id' => $developer->id,
                'status' => 'new',
                'priority' => 'high',
            ]
        );

        TaskComment::firstOrCreate(
            ['task_id' => $task->id, 'user_id' => $developer->id, 'comment' => 'Initial comment']
        );

        TaskNotification::firstOrCreate(
            [
                'task_id' => $task->id,
                'user_id' => $manager->id,
                'message' => 'Initial notification for task'
            ]
        );

        if ($task->priority === 'high') {
            SendTaskNotificationJob::dispatch($task->id, 'task_assigned');
        }
        $overdueTask = Task::firstOrCreate(
            ['title' => 'Overdue Task'],
            [
                'description' => 'This task is in progress and older than 7 days',
                'user_id' => $developer->id,
                'status' => 'in_progress',
                'priority' => 'normal',
                'created_at' => Carbon::now()->subDays(10),
            ]
        );

        TaskComment::firstOrCreate([
            'task_id' => $overdueTask->id,
            'user_id' => $developer->id,
            'comment' => 'Initial comment for overdue task'
        ]);

        TaskNotification::firstOrCreate([
            'task_id' => $overdueTask->id,
            'user_id' => $manager->id,
            'message' => 'Initial notification for overdue task'
        ]);

        $recentTask = Task::firstOrCreate(
            ['title' => 'Recent InProgress Task'],
            [
                'description' => 'This task is in progress but recent',
                'user_id' => $developer->id,
                'status' => 'in_progress',
                'priority' => 'low',
                'created_at' => Carbon::now()->subDays(3),
            ]
        );

        TaskComment::firstOrCreate([
            'task_id' => $recentTask->id,
            'user_id' => $developer->id,
            'comment' => 'Initial comment for recent task'
        ]);
    }
}
