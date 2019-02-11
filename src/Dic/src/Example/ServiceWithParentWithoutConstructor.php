<?php
/**
 * Created by PhpStorm.
 * User: itprofessor02
 * Date: 11.02.19
 * Time: 20:50
 */

namespace rollun\dic\Example;


use rollun\dic\InsideConstruct;

class ServiceWithParentWithoutConstructor extends ParentWithoutConstructor
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
        InsideConstruct::init(["simpleService" => SimpleDependency::class]);
    }
}