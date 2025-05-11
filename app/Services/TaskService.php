<?php

namespace App\Services;

use App\Contracts\TaskRepositoryInterface;
use App\DTOs\UserRegisterDTO;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function getAllTasks()
    {
        return $this->taskRepository->getUserTasks(auth()->id());
    }

    public function cacheTasks(): mixed
    {
        return Cache::remember('task_list_user_' . auth()->id(), now()->addMinutes(10), function () {
            return $this->getAllTasks();
        });
    }

    public function getTask(int $id)
    {
        return $this->taskRepository->find($id);
    }

    public function createTask(array $data)
    {
        return $this->taskRepository->create($data);
    }

    public function updateTask(int $id, array $data)
    {
        return $this->taskRepository->update($id, $data);
    }

    public function markAsComplete(int $id)
    {
        return $this->taskRepository->update($id, ['is_done' => 1]);
    }

    public function deleteTask(int $id)
    {
        return $this->taskRepository->delete($id);
    }

    public function authorizeUserOperation($operation, $id)
    {
        return auth()->user()->can($operation, $this->getTask($id));
    }

    public function checkTaskComplete($id)
    {
        return $this->getTask($id)->is_done;
    }

    public function getUpcomingTasks()
    {
        $now = Carbon::now();
        $threshold = $now->copy()->addMinutes(30);
        return $this->taskRepository->getUpcomingTasks($now, $threshold);
    }

    public function removeCache()
    {
        Cache::forget('task_list_user_' . auth()->id());
    }
}
