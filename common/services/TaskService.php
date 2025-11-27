<?php
namespace common\services;

use Codeception\Constraint\Page;
use common\models\enum\TaskStatus;
use common\models\exceptions\NotSaveException;
use common\models\Task;
use ValueError;
use yii\base\Exception;

class TaskService
{
    public function create($title, $description): Task
    {
        $task = new Task([
            'title' => $title,
            'description' => $description,
            'status' => TaskStatus::CREATED
        ]);

        $task->save();

        return $task;
    }

    public function delete(Task $task){
        if(!$task->delete()) {
            throw new Exception('Delete task failed');
        }
    }

    public function setStatus(Task $task, $status): void {
        try {
            $status = TaskStatus::from($status);
        } catch (ValueError $e) {
            $task->addError('status', $e->getMessage());
            throw new NotSaveException($task);
        }

        $task->status = $status;
        $task->save();
    }
}