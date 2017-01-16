<?php

return [
    'dependencies' => [
        'invokables' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouter::class,
        ],
        'factories' => [
            /*
             * if you use rollun-datastore uncomment this
             \rollun\datastore\Pipe\RestRql::class => \rollun\datastore\Pipe\Factory\RestRqlFactory::class
             */
            \rollun\skeleton\Api\HelloAction::class => \rollun\skeleton\Api\HelloActionFactory::class
        ],
    ],

    'routes' => [
        /*
         * if you use rollun-datastore uncomment this
         [
            'name' => 'api.rest',
            'path' => '/api/rest[/{Resource-Name}[/{id}]]',
            'middleware' => \rollun\datastore\Pipe\RestRql::class,
            'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        ],
        */
        [
            'name' => 'home',
            'path' => '/[{name}]',
            'middleware' => \rollun\skeleton\Api\HelloAction::class,
            'allowed_methods' => ['GET'],
        ],
    ],
];
