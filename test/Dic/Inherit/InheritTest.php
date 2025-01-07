<?php


namespace rollun\test\dic\Inherit;


use PHPUnit\Framework\TestCase;

class InheritTest extends TestCase
{
    public function setUp(): void
    {
        global $container;
        $container->setService(DependencyClass::class, new DependencyClass());
    }

    public function testInherit()
    {
        $instance = new ChildrenClass();

        $this->assertInstanceOf(ChildrenClass::class, $instance);
        $this->assertEquals($instance->aTest, "test");
    }
}