<?php
namespace api\models;

use common\models\Task;
use yii\helpers\ArrayHelper;

/**
 * @SWG\Definition(
 *     definition="TaskListResponse",
 *     type="array",
 *     @SWG\Items(ref="#/definitions/TaskResponse")
 * )
 */
class TaskListResponse
{
    public function __construct(private readonly TaskResponse $taskResponse)
    {
    }

    /**
     * @param array<Task> $tasks
     * @return array
     */
    public function build($tasks)
    {
        return ArrayHelper::getColumn($tasks, fn(Task $task) => $this->taskResponse->build($task));
    }
}