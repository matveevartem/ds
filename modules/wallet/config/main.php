<?php

return  [
    'components' => [
        'queue' => [
            'class' => 'app\modules\wallet\components\QueueComponent',
            'queueName' => 'TRANSACTIONS',
            'host' => getenv('QUEUE_HOST', true) ?: getenv('QUEUE_HOST'),
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite:' . __DIR__ . '/../data/database.db',
        ],
    ],
];
