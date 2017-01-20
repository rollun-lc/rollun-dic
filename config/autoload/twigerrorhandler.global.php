<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.01.17
 * Time: 14:08
 */

return constant('APP_ENV') === 'prod' ? [
    'dependencies' => [
        'factories' => [
            'Zend\Expressive\FinalHandler' =>
                Zend\Expressive\Container\TemplatedErrorHandlerFactory::class,
        ],
    ],
] : [];
