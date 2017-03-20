<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.01.17
 * Time: 17:32
 */

use rollun\actionrender\Factory\ActionRenderAbstractFactory;

return [
    'dependencies' => [
        'invokables' => [
            \rollun\actionrender\Example\Api\HelloAction::class => \rollun\actionrender\Example\Api\HelloAction::class
        ],
    ],
    ActionRenderAbstractFactory::KEY => [
        'home-service' => [
                ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE =>
                    \rollun\actionrender\Example\Api\HelloAction::class,
                ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => 'simpleHtmlJsonRenderer'
            ]
    ],

];