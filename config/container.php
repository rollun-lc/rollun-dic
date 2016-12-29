<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 27.12.16
 * Time: 4:04 PM
 */

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

// Create a ServiceManager from service_manager config and register the merged config as a service
$config = include __DIR__ . '/config.php';
//$configObject = new Config(isset($config['services']) ? $config['services'] : []);
$sm = new ServiceManager(isset($config['services']) ? $config['services'] : []);
$sm->setService('config', $config);
// Return the fully configured ServiceManager
return $sm;