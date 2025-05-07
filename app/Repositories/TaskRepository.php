<?php

namespace App\Repositories;

use App\Contracts\TaskRepositoryInterface;
use App\Models\Task;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function getUserTasks(string $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getUpcomingTasks($now, $time)
    {
        return $this->model->where('is_done', false)->whereBetween('due_at', [$now, $time])->get();
    }
}
