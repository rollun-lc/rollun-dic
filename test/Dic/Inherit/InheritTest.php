<?php


namespace rollun\test\dic\Inherit;


use PHPUnit\Framework\TestCase;

class InheritTest extends TestCase
{
    public function setUp()
    {
        global $container;
        $container->setService(DependencyClass::class, new DependencyClass());
    }

    public function testInherit()
    {
        $object = new ChildrenClass();
    }
}