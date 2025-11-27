<?php

use api\controllers\ApiErrorHandler;
use yii\log\FileTarget;
use yii\web\JsonParser;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => JsonParser::class,
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => 'task/<id:\d+>',
                    'route' => 'task/delete',
                    'verb' => 'DELETE',
                ],
                [
                    'pattern' => 'task/<id:\d+>',
                    'route' => 'task/view',
                    'verb' => 'GET',
                ],
                [
                    'pattern' => 'task/<id:\d+>/status',
                    'route' => 'task/update-status',
                    'verb' => 'PUT',
                ],
            ],
        ],
    ],
    'params' => $params,
];
