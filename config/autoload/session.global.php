<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.02.17
 * Time: 12:38
 */

return [
    'dependencies' => [
        'factories' => [
            \Zend\Session\SessionManager::class =>
                \Zend\Session\Service\SessionManagerFactory::class,
        ],
        'abstract_factories' => [
            \Zend\Session\Service\ContainerAbstractServiceFactory::class,
        ],
    ],
    'session_containers' => [
        'WebSessionContainer'
    ],
];