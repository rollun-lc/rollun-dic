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
        /*[
            'name' => 'api-datastore',
            'path' => '/api/datastore[/{resourceName}[/{id}]]',
            'middleware' => 'api-datastore',
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        ],
        [
            'name' => 'rest-datastore',
            'path' => '/api/datastore[/{resourceName}[/{id}]]',
            'middleware' => 'rest-datastore',
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        ],*/
        [
            'name' => 'webhook',
            'path' => '/webhook[/{resourceName}]',
            'middleware' => 'webhookActionRender',
            'allowed_methods' => ['GET', 'POST'],
        ],
        [
            'name' => 'user',
            'path' => '/user',
            'middleware' => 'user-service',
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
