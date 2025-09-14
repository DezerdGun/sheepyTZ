<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\TaskComment;
use App\Jobs\SendTaskNotificationJob;

class CheckOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-overdue {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check tasks in progress that are overdue and notify managers';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $overdueTasks = Task::where('status', 'in_progress')
                            ->where('created_at', '<', now()->subDays(7))
                            ->get();

        $count = $overdueTasks->count();

        $this->info("Found {$count} overdue tasks.");

        if ($dryRun) {
            $this->info('Dry-run mode: no changes applied.');
            return 0;
        }

        foreach ($overdueTasks as $task) {
    
            TaskComment::create([
                'task_id' => $task->id,
                'user_id' => $task->user_id, 
                'comment' => 'Task is overdue! Created ' . $task->created_at->format('Y-m-d'),
            ]);


            SendTaskNotificationJob::dispatch($task->id, 'overdue');
        }

        $this->info("Processed {$count} overdue tasks.");
        return 0;
    }
}
