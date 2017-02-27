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
use rollun\permission\Acl\Factory\AclFromDataStoreFactory;
use Zend\Permissions\Acl\Acl;

return [
    'dependencies' => [
        'abstract_factories' => [

        ],
        'invokables' => [
            \rollun\permission\Acl\Middleware\PrivilegeResolver::class =>
                \rollun\permission\Acl\Middleware\PrivilegeResolver::class
        ],
        'factories' => [
            \rollun\permission\Acl\Middleware\ResourceResolver::class =>
                \rollun\permission\Acl\Middleware\Factory\ResourceResolverFactory::class,

            \rollun\permission\Acl\Middleware\RoleResolver::class =>
                \rollun\permission\Acl\Middleware\Factory\RoleResolverFactory::class,

            \rollun\permission\Acl\Middleware\AclMiddleware::class =>
                \rollun\permission\Acl\Middleware\Factory\AclMiddlewareFactory::class,

            Acl::class => AclFromDataStoreFactory::class
        ],
    ],
    MiddlewarePipeAbstractFactory::KEY_AMP => [
        'aclPipes' => [
            'middlewares' => [
                \rollun\permission\Acl\Middleware\RoleResolver::class,
                \rollun\permission\Acl\Middleware\ResourceResolver::class,
                \rollun\permission\Acl\Middleware\PrivilegeResolver::class,
                \rollun\permission\Acl\Middleware\AclMiddleware::class,
            ]
        ]
    ],
];