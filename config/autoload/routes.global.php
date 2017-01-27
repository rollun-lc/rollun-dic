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
         * if you use rollun-datastore uncomment this
         [
            'name' => 'api.rest',
            'path' => '/api/rest[/{Resource-Name}[/{id}]]',
            'middleware' => 'api-rest,
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        ],
        */
    ],
];
