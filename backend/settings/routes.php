<?php

return [
    '/' => [
        'httpMethod' => 'GET',
        'controller' => 'Index',
        'method' => 'index',
//        'modifier' => 'sync',
//        'pipeline' => 'DEFAULT',
    ],
    '/issue/@issue/join' => [
        'httpMethod' => 'POST',
        'controller' => 'Issue',
        'method' => 'join',
//        'modifier' => 'sync',
//        'pipeline' => 'DEFAULT',
    ],
    '/issue/@issue/vote' => [
        'httpMethod' => 'POST',
        'controller' => 'Issue',
        'method' => 'vote',
//        'modifier' => 'sync',
//        'pipeline' => 'DEFAULT',
    ],
    '/issue/@issue' => [
        'httpMethod' => 'GET',
        'controller' => 'Issue',
        'method' => 'index',
//        'modifier' => 'sync',
//        'pipeline' => 'DEFAULT',
    ],
    '/admin' => [
        'httpMethod' => 'GET',
        'controller' => 'Admin',
        'method' => 'index',
        'modifier' => 'ajax',
        'pipeline' => 'AUTH',
    ],
];
