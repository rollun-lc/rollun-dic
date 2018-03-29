<?php


namespace rollun\dic\Example;


use rollun\dic\InsideConstruct;

class SerializedService
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
     */
    public function __construct(SimpleDependency $simpleService)
    {
        $this->simpleService = $simpleService;
    }

    public function __wakeup()
    {
        InsideConstruct::initWakeup(["simpleService" => SimpleDependency::class]);
    }


}