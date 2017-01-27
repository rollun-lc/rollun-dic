<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories' => [
            ],
    ],

    'routes' => [
        /*
         * if you use rollun-datastore uncomment this. and add Config.
         [
            'name' => 'api.rest',
            'path' => '/api/rest[/{resourceName}[/{id}]]',
            'middleware' => 'api-rest',
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
         ],
        */
        /*
         * if you use rollun-callback uncomment this. and add Config.
         [
            'name' => 'interrupt.callback',
            'path' => '/interrupt/callback',
            'middleware' => 'interrupt-callback',
            'allowed_methods' => ['POST'],
         ],
         */
        [
            'name' => 'interrupt.cron',
            'path' => '/interrupt/cron',
            'middleware' => \rollun\skeleton\Api\CronExceptionMiddleware::class,
            'allowed_methods' => ['GET', 'POST'],
        ],
        [
            'name' => 'home-page',
            'path' => '/[{name}]',
            'middleware' => 'home-service',
            'allowed_methods' => ['GET'],
        ],
    ],
];
