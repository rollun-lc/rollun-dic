<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23.01.17
 * Time: 17:41
 */

use rollun\actionrender\Factory\MiddlewarePipeAbstractFactory;
use rollun\actionrender\Factory\ActionRenderAbstractFactory;
use rollun\actionrender\Renderer\ResponseRendererAbstractFactory;

return [
    'dependencies' => [
        'abstract_factories' => [
            MiddlewarePipeAbstractFactory::class,
            ActionRenderAbstractFactory::class,
            ResponseRendererAbstractFactory::class
        ],
        'invokables' => [
            \rollun\actionrender\Renderer\Html\HtmlParamResolver::class =>
                \rollun\actionrender\Renderer\Html\HtmlParamResolver::class,
            \rollun\actionrender\Renderer\Json\JsonRendererAction::class =>
                \rollun\actionrender\Renderer\Json\JsonRendererAction::class,
        ],
        'factories' => [
            \rollun\actionrender\Renderer\Html\HtmlRendererAction::class =>
                \rollun\actionrender\Renderer\Html\HtmlRendererFactory::class
        ],
    ],
    MiddlewarePipeAbstractFactory::KEY_AMP => [
        'htmlReturner' => [
            'middlewares' => [
                \rollun\actionrender\Renderer\Html\HtmlParamResolver::class,
                \rollun\actionrender\Renderer\Html\HtmlRendererAction::class
            ]
        ]
    ],
    ResponseRendererAbstractFactory::KEY_RESPONSE_RENDERER => [
        'simpleHtmlJsonRenderer' => [
            ResponseRendererAbstractFactory::KEY_ACCEPT_TYPE_PATTERN => [
                //pattern => middleware-Service-Name
                '/application\/json/' => \rollun\actionrender\Renderer\Json\JsonRendererAction::class,
                '/text\/html/' => 'htmlReturner'
            ]
        ]
    ],
    ActionRenderAbstractFactory::KEY_AR_SERVICE => [
        /*'home' => [
            ActionRenderAbstractFactory::KEY_AR_MIDDLEWARE => [
                ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE => '',
                ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => 'simpleHtmlJsonRenderer'
            ]
        ],*/
    ]
];