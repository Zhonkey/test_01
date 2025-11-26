<?php

namespace console\controllers;

use common\models\enum\TaskStatus;
use common\models\exceptions\NotSaveException;
use common\models\Task;
use yii\base\Controller;
use yii\helpers\Console;

class SeedController extends Controller
{
    public function actionIndex() {
        Console::output("⏳ Создание тестовых задач...");

        for ($i = 1; $i <= 30; $i++) {
            try {
                $task = new Task();
                $task->title = "Тестовая задача #{$i}";
                $task->description = "Описание тестовой задачи №{$i}.";
                $task->status = match (intval(floor($i / 10))) {
                    0 => TaskStatus::CREATED,
                    1 => TaskStatus::IN_PROCESS,
                    2,3 => TaskStatus::COMPLETED,
                };

                $task->save();
                Console::output("✔ Задача {$i} создана (ID: {$task->id})");
            } catch (NotSaveException $e) {
                Console::output("✖ Ошибка при сохранении задачи {$i}");
                Console::output(print_r($task->errors, true));
            }
        }

        Console::output("✅ Задачи созданы");
    }

    public function actionClear() {
        Task::deleteAll();

        Console::output("✅ Все задачи были удалены");
    }
}