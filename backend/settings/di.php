<?php

return [
    // Factories
    'App\Factory\IssueFactory' => [],

    // Services
    'App\Service\HttpService' => [],
    'App\Service\StorageService' => [
        [
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
        ]
//        [
//            'host' => 'localhost',
//            'port' => 6379,
//            'timeout' => 0.0,
//            'reserved' => null,
//            'retryInterval' => 0,
//            'readTimeout' => 0.0,
//        ]
    ],

    // Middleware
    'App\Http\Middleware\ControllerMiddleware' => [],
    'App\Http\Middleware\TestMiddleware' => [],
    'Franzl\Middleware\Whoops\WhoopsMiddleware' => [],
];
