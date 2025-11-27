<?php
namespace common\repositories;

use common\models\Task;

class TaskRepository
{
    /**
     * @return array<Task>
     */
    public function findAll(): array
    {
        return Task::find()->all();
    }

    /**
     * @param $id
     * @return Task|null
     */
    public function findOne($id): ?Task
    {
        return Task::findOne($id);
    }
}