<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 27.12.16
 * Time: 4:04 PM
 */

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use Zend\Stdlib\ArrayUtils;

// Load configuration
$config = require __DIR__ . '/config.php';

$service = isset($config['services']) ? $config['services'] : [];
$service = isset($config['dependencies']) ? ArrayUtils::merge($service, $config['dependencies']) : $service;

// Build container
$container = new ServiceManager();
(new Config($service))->configureServiceManager($container);

// Inject config
$container->setService('config', $config);

return $container;
