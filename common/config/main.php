<?php

use api\presenters\ExceptionPresenter;
use api\presenters\TaskPresenter;
use common\components\Formatter;
use common\repositories\TaskRepository;
use common\services\TaskService;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
    ],
    'bootstrap' => [
        function () {
            $container = Yii::$container;

            $container->set(Formatter::class);
            $container->set(TaskRepository::class);
            $container->set(TaskService::class);
            $container->set(TaskPresenter::class);
            $container->set(ExceptionPresenter::class);
        },
    ]
];
