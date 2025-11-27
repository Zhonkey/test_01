<?php
namespace api\models;

use common\components\Formatter;
use common\models\enum\TaskStatus;
use common\models\Task;

/**
 * @SWG\Definition()
 *
 * @SWG\Property(property="id", type="integer")
 * @SWG\Property(property="title", type="string")
 * @SWG\Property(property="description", type="integer")
 * @SWG\Property(property="award", type="integer")
 * @SWG\Property(property="energy", type="integer")
 * @SWG\Property(property="type", type="integer")
 */
class TaskResponse
{
    public function __construct(private readonly Formatter $formatter)
    {
    }

    public function build(Task $task): array
    {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'created_at' => $this->formatter->dateTime($task->created_at),
            'updated_at' => $this->formatter->dateTime($task->updated_at),
            'status' => match ($task->status) {
                TaskStatus::CREATED => 'Created',
                TaskStatus::IN_PROCESS => 'InProcess',
                TaskStatus::COMPLETED => 'Completed',
            },
        ];
    }
}