<?php

namespace rollun\dic;

use Interop\Container\ContainerInterface;

/**
 * Static service
 * Interface InsideConstructInterface
 * @package rollun\dic
 */
interface InsideConstructInterface
{

    /**
     * Setup container into InsideConstructor
     * @param ContainerInterface $container
     * @return array
     */
    public static function setContainer(ContainerInterface $container);

    /**
     * Init dependency service and call parent construct with service init
     * @param array $dependencyMapping
     * @return array
     */
    public static function init(array $dependencyMapping = []);

    /**
     * Init service usage dependency service from __constructor args
     * @param array $dependencyMapping
     * @return array
     */
    public static function setConstructParams(array $dependencyMapping = []);

    /**
     * Init service in __wakeup method
     * @param array $dependencyMapping
     * @return array
     */
    public static function initWakeup(array $dependencyMapping = []);

    /**
     * Run parent constructor (parent::__constructor(...))
     * with init service usage dependency service from __constructor args
     * @param array $loadParams
     * @return mixed
     */
    public static function runParentConstruct(array $loadParams = []);
}