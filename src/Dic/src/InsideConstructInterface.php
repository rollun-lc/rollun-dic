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
     * @param array $mapping
     * @return array
     */
    public static function init(array $mapping = []);

    /**
     * Init service usage dependency service from __constructor args
     * @param array $setService
     * @return array
     */
    public static function setConstructParams(array $setService = []);

    /**
     * Init service in __wakeup method
     * @param array $service
     * @return array
     */
    public static function initWakeup(array $service = []);

    /**
     * Run parent constructor (parent::__constructor(...))
     * with init service usage dependency service from __constructor args
     * @param array $loadParams
     * @return mixed
     */
    public static function runParentConstruct(array $loadParams = []);
}