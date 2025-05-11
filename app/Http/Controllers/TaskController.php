<?php

namespace App\Http\Controllers;

use App\DTOs\TaskCreateDTO;
use App\DTOs\TaskUpdateDTO;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Libraries\General;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    public function index()
    {
        $cachedTasks = $this->taskService->cacheTasks();

        return General::response(true, TaskResource::collection($cachedTasks), 'List of tasks');
    }

    public function show(string $id)
    {
        if (!$this->taskService->authorizeUserOperation('show', $id)) {
            return General::response(false, null, 'You are not allowed to view other user task', true, 403);
        }

        $task = $this->taskService->getTask($id);

        return General::response(true, TaskResource::make($task), 'Specific task');
    }

    public function store(StoreTaskRequest $request)
    {
        $taskData = TaskCreateDTO::fromRequest($request->validated())->toArray();

        $task = $this->taskService->createTask($taskData);

        $this->taskService->removeCache();

        return General::response(true, TaskResource::make($task), 'Task created successfully');
    }

    public function update(UpdateTaskRequest $request, string $id)
    {
        if (!$this->taskService->authorizeUserOperation('update', $id)) {
            return General::response(false, null, 'You are not allowed to update other user task', true, 403);
        }

        if (!$this->taskService->getTask($id)) {
            return General::response(false, null, 'Task not found', true, 404);
        }

        $taskData = TaskUpdateDTO::fromRequest($request->validated())->toArray();

        $task = $this->taskService->updateTask($id, $taskData);

        $this->taskService->removeCache();

        return General::response(true, TaskResource::make($task), 'Task updated successfully');
    }

    public function destroy(string $id)
    {
        if (!$this->taskService->authorizeUserOperation('delete', $id)) {
            return General::response(false, null, 'You are not allowed to delete other user task', true, 403);
        }

        if (!$this->taskService->getTask($id)) {
            return General::response(false, null, 'Task not found', true, 404);
        }

        $this->taskService->deleteTask($id);

        $this->taskService->removeCache();

        return General::response(true, null, 'Task was deleted successfully');
    }

    public function complete(int $id)
    {
        if (!$this->taskService->authorizeUserOperation('update', $id)) {
            return General::response(false, null, 'You are not allowed to mark the other user task as complete', true, 403);
        }

        if (!$this->taskService->getTask($id)) {
            return General::response(false, null, 'Task not found', true, 404);
        }

        if ($this->taskService->checkTaskComplete($id)) {
            return General::response(false, null, 'Task is already complete', true, 400);
        }

        $task = $this->taskService->markAsComplete($id);

        $this->taskService->removeCache();

        return General::response(true, TaskResource::make($task), 'Task status is completed now');
    }
}
