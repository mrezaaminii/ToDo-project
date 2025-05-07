<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Notifications\UpcomingTaskAlert;
use App\Services\TaskService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyUpcomingTasksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     protected $taskService;
    public function __construct()
    {
        $this->taskService = resolve(TaskService::class);
    }

    public function handle(): void
    {
        $tasks = $this->taskService->getUpcomingTasks();

        foreach ($tasks as $task) {
            $logMessage = "Reminder: Task [{$task->id}] '{$task->title}' is due soon at {$task->due_at}";

            $task->notify(new UpcomingTaskAlert());

            Log::info($logMessage);
        }
    }
}
