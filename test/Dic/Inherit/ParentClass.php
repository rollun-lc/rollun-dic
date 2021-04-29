<?php


namespace rollun\test\dic\Inherit;


use rollun\dic\InsideConstruct;
use rollun\test\dic\Inherit\AbstractClass;
use rollun\test\dic\Inherit\DependencyClass;

class ParentClass extends AbstractClass
{
    protected $test;

    public function __construct(DependencyClass $test = null)
    {
        static $count = 1;
        $count++;
        if ($count > 100) {
            throw new \Exception('Constructor of ' . self::class . ' was run more than 100 times');
        }
        InsideConstruct::init(['test' => DependencyClass::class]);
    }
}