<?php


namespace rollun\dic\Example;


use rollun\dic\InsideConstruct;

class SimpleService
{
    private $simpleService;

    /**
     * @return SimpleDependency
     */
    public function getSimpleService(): SimpleDependency
    {
        return $this->simpleService;
    }

    /**
     * SerializedService constructor.
     * @param SimpleDependency $simpleService
     * @throws \ReflectionException
     */
    public function __construct(SimpleDependency $simpleService = null)
    {
        InsideConstruct::setConstructParams(["simpleService" => SimpleDependency::class]);
    }
}