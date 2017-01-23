<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.01.17
 * Time: 17:41
 */

use rollun\utils\MainPipe\Factory\MainPipeFactoryAbstract;
use rollun\utils\MainPipe\ResponseReturnerFactory;

return [
    'dependencies' => [
        'abstract_factories' => [
            MainPipeFactoryAbstract::class
        ],
        'invokables' => [
            \rollun\skeleton\Api\HelloAction::class => \rollun\skeleton\Api\HelloAction::class,
            \rollun\skeleton\Returner\JsonReturnerAction::class => \rollun\skeleton\Returner\JsonReturnerAction::class,
            \rollun\skeleton\Returner\Html\HtmlParamResolver::class => \rollun\skeleton\Returner\Html\HtmlParamResolver::class ,
        ],
        'factories' => [
            \rollun\skeleton\Returner\Html\HtmlReturnerAction::class => \rollun\skeleton\Returner\Html\HtmlReturnerFactory::class,
            ResponseReturnerFactory::class => ResponseReturnerFactory::class
        ],
    ],
    MainPipeFactoryAbstract::KEY_MAIN_PIPE => [
        'home' => [
            'middlewares' => [
                \rollun\skeleton\Api\HelloAction::class,
                ResponseReturnerFactory::class
            ]
        ],
        'htmlReturner' => [
            'middlewares' => [
                \rollun\skeleton\Returner\Html\HtmlParamResolver::class,
                \rollun\skeleton\Returner\Html\HtmlReturnerAction::class
            ]
        ]
    ],

    ResponseReturnerFactory::KEY_RESPONSE_RETURNER => [
        ResponseReturnerFactory::KEY_ACCEPT_TYPE_PATTERN => [
            //pattern => middleware
            '/application\/json/' => \rollun\skeleton\Returner\JsonReturnerAction::class,
            '/text\/html/' => 'htmlReturner'
        ]
    ]
];