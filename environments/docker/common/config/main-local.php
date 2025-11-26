<?php

use yii\queue\redis\Queue;
use yii\redis\Connection;

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => env('DB_DSN'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'redis' => [
            'class' => Connection::class,
            'hostname' => env('REDIS_HOSTNAME'),
        ],
        'queue' => [
            'class' => Queue::class,
            'redis' => 'redis',
            'channel' => 'queue',
        ],
        'cache' => [
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true,
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOSTNAME'),
                    'port' => 11211,
                    'weight' => 60,
                ]
            ],
        ],
    ],
];
