<?php

return [
    'debug' => constant('APP_ENV') != 'dev' ? false : true,

    'config_cache_enabled' => false,

    'zend-expressive' => [
        'error_handler' => [
            'template_404'   => 'error::404',
            'template_error' => 'error::error',
        ],
    ],
];
