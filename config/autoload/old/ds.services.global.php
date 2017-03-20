<?php

use rollun\datastore\DataStore\Eav\EavAbstractFactory;
use rollun\datastore\TableGateway\Factory\TableManagerMysqlFactory;
use rollun\datastore\DataStore\Aspect\Factory\AspectAbstractFactory;
use rollun\datastore\Middleware\Factory\DataStoreAbstractFactory as MiddlewareDataStoreAbstractFactory;
use rollun\datastore\DataStore\Factory\HttpClientAbstractFactory;
use rollun\datastore\DataStore\Factory\DbTableAbstractFactory;
use rollun\datastore\DataStore\Factory\CsvAbstractFactory;
use rollun\datastore\DataStore\Factory\MemoryAbstractFactory;
use rollun\datastore\DataStore\Factory\CacheableAbstractFactory;
use Zend\Db\Adapter\AdapterAbstractServiceFactory;
use rollun\datastore\TableGateway\Factory\TableGatewayAbstractFactory;

return [

    'services' => [
        'factories' => [
            'TableManagerMysql' => TableManagerMysqlFactory::class
        ],
        'abstract_factories' => [
            AspectAbstractFactory::class,
            MiddlewareDataStoreAbstractFactory::class,
            HttpClientAbstractFactory::class,
            DbTableAbstractFactory::class,
            CsvAbstractFactory::class,
            MemoryAbstractFactory::class,
            CacheableAbstractFactory::class,
            AdapterAbstractServiceFactory::class,
            TableGatewayAbstractFactory::class,
        ],
    ]
];
