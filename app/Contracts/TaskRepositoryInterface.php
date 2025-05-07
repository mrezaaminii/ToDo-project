<?php

namespace App\Contracts;

interface TaskRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserTasks(string $userId);
    public function getUpcomingTasks($now, $time);
}
