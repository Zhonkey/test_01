<?php
namespace api\presenters;

use api\models\TaskListResponse;
use api\models\TaskResponse;
use common\models\Task;

class TaskPresenter
{
    public function __construct(
        private readonly TaskResponse $taskResponse,
        private readonly TaskListResponse $taskListResponse,
    ) {
    }

    /**
     * @param Task $task
     * @return array
     */
    public function view(Task $task): array {
        return $this->taskResponse->build($task);
    }

    /**
     * @param array<Task> $tasks
     * @return array
     */
    public function list(array $tasks):array {
        return $this->taskListResponse->build($tasks);
    }
}