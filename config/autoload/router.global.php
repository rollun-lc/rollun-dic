<?php

use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;

return [
    'dependencies' => [
        'invokables' => [
            //RouterInterface::class => FastRouteRouter::class,
        ],
        'factories' => [
            Zend\Expressive\Router\RouterInterface::class => Zend\Expressive\Router\FastRouteRouterFactory::class,
        ]
    ],

    'router' => [
        'fastroute' => [
            // Decidable caching support:
            'cache_enabled' => constant("APP_ENV") === 'prod' ? true : false,
            // Optional (but recommended) cache file path:
            'cache_file'    => 'data/cache/fastroute.php.cache',
        ],
    ],
];
