<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.01.17
 * Time: 17:26
 */

use rollun\actionrender\Factory\ActionRenderAbstractFactory;
use rollun\actionrender\Factory\MiddlewarePipeAbstractFactory;
use rollun\api\Api\Google\Client\Factory\WebAbstractFactory;
use rollun\datastore\AbstractFactoryAbstract;
use rollun\datastore\DataStore\Factory\CacheableAbstractFactory;
use rollun\datastore\DataStore\Factory\DataStoreAbstractFactory;
use rollun\permission\Acl\Factory\AclFromDataStoreFactory;
use rollun\permission\Auth\Adapter\Factory\HttpAdapterAbstractFactory;
use rollun\permission\Auth\Adapter\Factory\OpenIDAdapterAbstractFactory;
use rollun\permission\Auth\Adapter\Resolver\Factory\OpenIDResolverAbstractFactory;
use rollun\permission\Auth\Adapter\Resolver\Factory\UserDSResolverAbstractFactory;
use rollun\permission\Auth\Middleware\Factory\AuthenticationAbstractFactory;
use rollun\permission\Auth\Middleware\Factory\UserResolverFactory;
use rollun\permission\Comparator\Factory\AttributeRequestComparatorAbstractFactory;
use rollun\permission\Middleware\Factory\LazyLoadSwitchAbstractFactory;
use Zend\Permissions\Acl\Acl;

return [

    'dataSource' => [
        'aclRules' => [],
        'aclRoles' => [],
        'aclResource' => [],
        'aclPrivilege' => [],
        'aclUser' => [],
        'aclUserRoles' => [],
    ],
    'dataStore' => [
        'rulesDS' => [
            CacheableAbstractFactory::KEY_DATASOURCE => 'aclRules',
            CacheableAbstractFactory::KEY_CLASS => \rollun\datastore\DataStore\Cacheable::class,
            CacheableAbstractFactory::KEY_IS_REFRESH => true,
        ],
        'rolesDS' => [
            CacheableAbstractFactory::KEY_DATASOURCE => 'aclRoles',
            CacheableAbstractFactory::KEY_CLASS => \rollun\datastore\DataStore\Cacheable::class,
            CacheableAbstractFactory::KEY_IS_REFRESH => true,
        ],
        'resourceDS' => [
            CacheableAbstractFactory::KEY_DATASOURCE => 'aclResource',
            CacheableAbstractFactory::KEY_CLASS => \rollun\datastore\DataStore\Cacheable::class,
            CacheableAbstractFactory::KEY_IS_REFRESH => true,
        ],
        'privilegeDS' => [
            CacheableAbstractFactory::KEY_DATASOURCE => 'aclPrivilege',
            CacheableAbstractFactory::KEY_CLASS => \rollun\datastore\DataStore\Cacheable::class,
            CacheableAbstractFactory::KEY_IS_REFRESH => true,
        ],
        'userDS' => [
            CacheableAbstractFactory::KEY_DATASOURCE => 'aclUser',
            CacheableAbstractFactory::KEY_CLASS => \rollun\datastore\DataStore\Cacheable::class,
            CacheableAbstractFactory::KEY_IS_REFRESH => true,
        ],
        'aclUserRolesDS' => [
            CacheableAbstractFactory::KEY_DATASOURCE => 'aclUserRoles',
            CacheableAbstractFactory::KEY_CLASS => \rollun\datastore\DataStore\Cacheable::class,
            CacheableAbstractFactory::KEY_IS_REFRESH => true,
        ],
    ],

    'acl' => [
        AclFromDataStoreFactory::KEY_DS_RULE_SERVICE => 'rulesDS',
        AclFromDataStoreFactory::KEY_DS_ROLE_SERVICE => 'rolesDS',
        AclFromDataStoreFactory::KEY_DS_RESOURCE_SERVICE => 'resourceDS',
        AclFromDataStoreFactory::KEY_DS_PRIVILEGE_SERVICE => 'privilegeDS',
    ],

    'aclUser' => [
        ['id' => "108787658858627228573", 'name' => 'victor'],
        ['id' => "1", 'name' => 'user', 'password' => '123wqe321'],
        ['id' => "0", 'name' => 'guest', 'password' => ''],
    ],

    'aclUserRoles' => [
        ['id' => 0, 'role_id' => 2, 'user_id' => '108787658858627228573'],
        ['id' => 1, 'role_id' => 3, 'user_id' => '1'],
        ['id' => 3, 'role_id' => 1, 'user_id' => '0'],
    ],

    'aclRoles' => [
        ['id' => 1, 'name' => 'guest', 'parent_id' => null],
        ['id' => 2, 'name' => 'user', 'parent_id' => 1],
        ['id' => 3, 'name' => 'admin', 'parent_id' => 2],
    ],

    'aclResource' => [
        ['id' => 1, 'name' => 'root', 'pattern' => '/^http:\/\/' . constant("HOST") . '\/$/', 'parent_id' => null],
        ['id' => 7, 'name' => 'user', 'pattern' => '/^http:\/\/' . constant("HOST") . '\/user$/', 'parent_id' => null],
        ['id' => 2, 'name' => 'login', 'pattern' => '/^http:\/\/' . constant("HOST") . '\/login/', 'parent_id' => null],
        ['id' => 3, 'name' => 'logout', 'pattern' => '/^http:\/\/' . constant("HOST") . '\/logout$/', 'parent_id' => null],
        ['id' => 4, 'name' => 'interrupt', 'pattern' => '/^http:\/\/' . constant("HOST") . '\/interrupt/', 'parent_id' => null],
        ['id' => 5, 'name' => 'api-rest', 'pattern' => '/^http:\/\/' . constant("HOST") . '\/api\/rest/', 'parent_id' => null],
        ['id' => 6, 'name' => 'api', 'pattern' => '/^http:\/\/' . constant("HOST") . '\/api/', 'parent_id' => 5],
    ],

    'aclPrivilege' => [
        ['id' => 1, 'name' => 'GET'],
        ['id' => 2, 'name' => 'PUT'],
        ['id' => 3, 'name' => 'POST'],
        ['id' => 4, 'name' => 'DELETE'],
    ],

    'aclRules' => [
        ['id' => 1, 'role_id' => 2, 'resource_id' => 7, 'privilege_id' => 1, 'allow_flag' => 1],
        ['id' => 3, 'role_id' => 2, 'resource_id' => 7, 'privilege_id' => 3, 'allow_flag' => 1],

        ['id' => 5, 'role_id' => 1, 'resource_id' => 2, 'privilege_id' => 1, 'allow_flag' => 1],
        ['id' => 6, 'role_id' => 1, 'resource_id' => 2, 'privilege_id' => 3, 'allow_flag' => 1],

        ['id' => 11, 'role_id' => 1, 'resource_id' => 1, 'privilege_id' => 1, 'allow_flag' => 1],
        ['id' => 12, 'role_id' => 1, 'resource_id' => 1, 'privilege_id' => 3, 'allow_flag' => 1],

        ['id' => 7, 'role_id' => 2, 'resource_id' => 3, 'privilege_id' => 1, 'allow_flag' => 1],
        ['id' => 8, 'role_id' => 2, 'resource_id' => 3, 'privilege_id' => 2, 'allow_flag' => 1],
        ['id' => 9, 'role_id' => 2, 'resource_id' => 3, 'privilege_id' => 3, 'allow_flag' => 1],
        ['id' => 10, 'role_id' => 2, 'resource_id' => 3, 'privilege_id' => 4, 'allow_flag' => 1],

    ],

    'dependencies' => [
        'invokables' => [
            \rollun\permission\Auth\Middleware\ErrorHandler\CredentialErrorHandlerMiddleware::class =>
                \rollun\permission\Auth\Middleware\ErrorHandler\CredentialErrorHandlerMiddleware::class,
            \rollun\permission\Comparator\PathRequestComparator::class =>
                \rollun\permission\Comparator\PathRequestComparator::class,
            \rollun\actionrender\ReturnMiddleware::class => \rollun\actionrender\ReturnMiddleware::class,

            ##### Error Handler Start #####
            \rollun\permission\Auth\Middleware\ErrorHandler\CredentialErrorHandlerMiddleware::class =>
                \rollun\permission\Auth\Middleware\ErrorHandler\CredentialErrorHandlerMiddleware::class,
            ##### Error Handler End   #####

        ],

        'factories' => [
            \rollun\permission\Auth\Middleware\LogoutAction::class =>
                \rollun\permission\Auth\Middleware\Factory\LogoutActionFactory::class,

            \Zend\Authentication\AuthenticationService::class =>
                \rollun\permission\Auth\Factory\AuthServiceFactory::class,

            \Zend\Session\SessionManager::class => \Zend\Session\Service\SessionManagerFactory::class,

            \rollun\permission\Auth\Middleware\AuthenticationAction::class =>
                \rollun\permission\Auth\Middleware\Factory\AuthenticationAbstractFactory::class,

            \rollun\permission\Auth\Middleware\IdentifyAction::class =>
                \rollun\permission\Auth\Middleware\Factory\IdentifyFactory::class,

            rollun\permission\Auth\Middleware\UserResolver::class =>
                \rollun\permission\Auth\Middleware\Factory\UserResolverFactory::class,

            ##### Error Handler Start #####

            \rollun\permission\Auth\Middleware\ErrorHandler\AccessForbiddenHandlerMiddleware::class =>
                \rollun\permission\Auth\Middleware\ErrorHandler\Factory\AccessForbiddenHandlerFactory::class,

            \rollun\permission\Auth\Middleware\ErrorHandler\AlreadyLogginHandler::class =>
                \rollun\permission\Auth\Middleware\ErrorHandler\Factory\AlreadyLogginHandlerFactory::class,

            ##### Error Handler End   #####

        ],

        'abstract_factories' => [
            \rollun\permission\Acl\DataSource\Factory\ConfigDataSourceAbstractFactory::class,
            \rollun\api\Api\Google\Client\Factory\AbstractFactory::class,
            \rollun\permission\Auth\Adapter\Resolver\Factory\UserDSResolverAbstractFactory::class,
            \rollun\permission\Auth\Adapter\Resolver\Factory\OpenIDResolverAbstractFactory::class,
            \rollun\permission\Auth\Adapter\Factory\OpenIDAdapterAbstractFactory::class,
            \rollun\permission\Auth\Adapter\Factory\HttpAdapterAbstractFactory::class,
            \rollun\permission\Middleware\Factory\LazyLoadSwitchAbstractFactory::class,
            \rollun\permission\Auth\Middleware\Factory\AuthenticationAbstractFactory::class,
            \rollun\actionrender\Factory\ActionRenderAbstractFactory::class,
            \rollun\permission\Comparator\Factory\AttributeRequestComparatorAbstractFactory::class,
        ]
    ],

    AuthenticationAbstractFactory::KEY_AUTHENTICATION => [
        AuthenticationAbstractFactory::KEY_ADAPTER => \rollun\permission\Auth\Adapter\OpenID::class
    ],

    HttpAdapterAbstractFactory::KEY_ADAPTER => [
        'basicHttpAdapter' => [
            HttpAdapterAbstractFactory::KEY_BASIC_RESOLVER => 'httpBasicResolver',
        ]
    ],

    OpenIDAdapterAbstractFactory::KEY_ADAPTER => [
        'openIdAdapter' => [
            OpenIDAdapterAbstractFactory::KEY_RESOLVER => 'openIdResolver',
        ]
    ],

    UserDSResolverAbstractFactory::KEY_RESOLVER => [
        'httpBasicResolver' => [
            UserDSResolverAbstractFactory::KEY_DS_SERVICE => 'userDS',
        ],
    ],

    OpenIDResolverAbstractFactory::KEY_RESOLVER => [
        'openIdResolver' => [
            OpenIDResolverAbstractFactory::KEY_USER_DS_SERVICE => 'userDS',
        ]
    ],

    MiddlewarePipeAbstractFactory::KEY_AMP => [
        'identityPipe' => [
            'middlewares' => [
                \rollun\permission\Auth\Middleware\IdentifyAction::class,
                'authPathSwitch',
            ]
        ],
        'authPipe' => [
            'middlewares' => [
                'authTypeSwitch',
                'authReturnSwitch'
            ]
        ],
    ],

    LazyLoadSwitchAbstractFactory::LAZY_LOAD_SWITCH => [
        'authPathSwitch' => [
            LazyLoadSwitchAbstractFactory::KEY_COMPARATOR_SERVICE =>
                \rollun\permission\Comparator\PathRequestComparator::class,
            LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                '/\/base/' => 'authPipe',
                '/\//' => \rollun\permission\Auth\Middleware\UserResolver::class,
            ]
        ],
        'authTypeSwitch' => [
            LazyLoadSwitchAbstractFactory::KEY_COMPARATOR_SERVICE =>
                \rollun\permission\Comparator\PathRequestComparator::class,
            LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                '/\/base/' => 'baseAuth',
                '/\//' => 'openId',
            ]
        ],
        'authReturnSwitch' => [
            LazyLoadSwitchAbstractFactory::KEY_COMPARATOR_SERVICE => 'returnResultAttributeRequestComparator',
            LazyLoadSwitchAbstractFactory::KEY_MIDDLEWARES_SERVICE => [
                '/^true$/' => \rollun\actionrender\ReturnMiddleware::class,
                '/^false$/' => \rollun\permission\Auth\Middleware\UserResolver::class,
            ]
        ]
    ],

    AuthenticationAbstractFactory::KEY_AUTHENTICATION => [
        'baseAuth' => [
            AuthenticationAbstractFactory::KEY_ADAPTER => 'basicHttpAdapter'
        ],
        'openId' => [
            AuthenticationAbstractFactory::KEY_ADAPTER => 'openIdAdapter'
        ],
    ],

    ActionRenderAbstractFactory::KEY_AR_SERVICE => [
        'base-service' => [
            ActionRenderAbstractFactory::KEY_AR_MIDDLEWARE => [
                ActionRenderAbstractFactory::KEY_ACTION_MIDDLEWARE_SERVICE =>
                    \rollun\actionrender\Example\Api\HelloAction::class,
                ActionRenderAbstractFactory::KEY_RENDER_MIDDLEWARE_SERVICE => 'simpleHtmlJsonRenderer'
            ]
        ],
    ],

    AttributeRequestComparatorAbstractFactory::KEY_COMPARATOR => [
        'returnResultAttributeRequestComparator' => [
            AttributeRequestComparatorAbstractFactory::KEY_ATTRIBUTE_KEY => 'returnResult'
        ]
    ],

    UserResolverFactory::KEY_USER_RESOLVER => [
        UserResolverFactory::KEY_USER_DS_SERVICE => 'userDS',
        UserResolverFactory::KEY_ROLES_DS_SERVICE => 'rolesDS',
        UserResolverFactory::KEY_USER_ROLES_DS_SERVICE => 'aclUserRolesDS',
    ],

];