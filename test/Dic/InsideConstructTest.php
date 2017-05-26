<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 23.12.16
 * Time: 3:16 PM
 */

namespace rollun\test\dic;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use rollun\dic\Example\InheritanceSimpleDependency;
use rollun\dic\Example\SettersDefault;
use rollun\dic\Example\SimpleDependency;
use rollun\dic\Example\Inheritance;
use rollun\dic\Example\SimpleDependencyInit;
use rollun\dic\Example\SimpleWithSeter;
use rollun\dic\Example\SimpleWithSeterAndConstruct;
use rollun\dic\Example\StaticDepSun;
use rollun\dic\InsideConstruct;

class InsideConstructTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function testInitServicesSimpleDependency()
    {
        $this->container->method('has')
            ->will($this->returnValue(false));
        $tested = new SimpleDependency();

        $this->assertEquals('simpleStringA', $tested->getSimpleStringA());
        $this->assertEquals(2.4, $tested->simpleNumericB);
        $this->assertEquals([0 => 'simpleArrayC'], $tested->getSimpleArrayC());
    }

    public function testInitServicesSimpleDependencyWithSeter()
    {
        $mapHas = [
            ['propA', true],
            ['propB', true],
        ];
        $this->container->method('has')
            ->will($this->returnValueMap($mapHas));

        $mapGet = [
            ['propA', 'PropA value'],
            ['propB', 'PropB value'],
        ];

        $this->container->method('get')
            ->will($this->returnValueMap($mapGet));
        $tested = new SimpleWithSeter();

        $this->assertEquals('PropA value', $tested->getPropA());
        $this->assertEquals('PropB value', $tested->getSetterProp());
    }

    public function testInitServicesSimpleDependencyWithConsturct()
    {
        $mapHas = [
            ['propA', true],
            ['propB', true],
        ];
        $this->container->method('has')
            ->will($this->returnValueMap($mapHas));

        $mapGet = [
            ['propA', 'PropA value'],
            ['propB', 'PropB value'],
        ];

        $this->container->method('get')
            ->will($this->returnValueMap($mapGet));
        $tested = new SimpleWithSeterAndConstruct();

        $this->assertEquals('PropB value', $tested->getPropA());
        $this->assertEquals('PropB value', $tested->getSetterProp());
    }

    public function testInitServicesSimpleDependencyInit()
    {
        $this->container->method('has')
            ->will($this->returnValue(false));
        $tested = new SimpleDependencyInit();

        $this->assertEquals('simpleStringA', $tested->getSimpleStringA());
        $this->assertEquals(2.4, $tested->simpleNumericB);
        $this->assertEquals([0 => 'simpleArrayC'], $tested->getSimpleArrayC());
    }

    public function testInitServicesStaticDependencyInit()
    {
        $this->container->method('has')
            ->will($this->returnValue(false));
        $tested = new StaticDepSun();

        $this->assertEquals('simpleStringA', $tested->getSimpleStringA());
        $this->assertEquals(2.4, $tested->simpleNumericB);
        $this->assertEquals([0 => 'simpleArrayC'], $tested->getSimpleArrayC());
        $this->assertEquals(StaticDepSun::CONST_VAL, $tested->getConstStatic());
    }

    //==========================================================================

    public function testInitServicesInheritanceSimpleDependency()
    {
        $this->container->method('has')
            ->will($this->returnValue(false));
        $tested = new InheritanceSimpleDependency();

        $this->assertEquals('simpleString_A', $tested->getSimpleStringA());
        $this->assertEquals(2.4, $tested->simpleNumericB);
        $this->assertEquals([0 => 'simpleArrayC'], $tested->getSimpleArrayC());
    }

    public function testInitServicesSettersDefault()
    {
        $mapHas = [
            ['propA', true],
            ['propB', true],
            ['propC', true],
        ];
        $this->container->method('has')
            ->will($this->returnValueMap($mapHas));

        $mapGet = [
            ['propA', 'PropA value'],
            ['propB', new \ArrayObject()],
            ['propC', new \stdClass()],
        ];

        $this->container->method('get')
            ->will($this->returnValueMap($mapGet));


        $useDiTrue = true;
        $tested = new SettersDefault($useDiTrue);
        $diResult = $useDiTrue; //by reference
        $useDiFalse = false;
        $expected = new SettersDefault($useDiFalse, 'PropA value', new \ArrayObject(), new \stdClass());
        unset($diResult['useDi']);
        $this->assertEquals(
            [
                'propA' => 'PropA value',
                'propB' => new \ArrayObject(),
                'propC' => new \stdClass()
            ],
            $diResult
        );
        $this->assertEquals($expected, $tested);

        $useDiTrue = true;
        $tested = new SettersDefault($useDiTrue, null, 'PropB value');
        $diResult = $useDiTrue; //by reference
        $useDiFalse = false;
        $expected = new SettersDefault($useDiFalse, null, 'PropB value', new \stdClass());
        unset($diResult['useDi']);
        $this->assertEquals(
            [
                'propA' => null,
                'propB' => 'PropB value',
                'propC' => new \stdClass()
            ],
            $diResult
        );
        $this->assertEquals($expected, $tested);
    }

    public function testInitServicesInheritance()
    {
        $mapHas = [
            ['propA', true],
            ['propB', true],
            ['propC', true],
            ['newPropA', true],
        ];
        $this->container->method('has')
            ->will($this->returnValueMap($mapHas));

        $mapGet = [
            ['propA', 'PropA value'],
            ['propB', new \ArrayObject()],
            ['propC', new \stdClass()],
            ['newPropA', 'PropNewA value'],
        ];

        $this->container->method('get')
            ->will($this->returnValueMap($mapGet));

        $tested = new Inheritance();

        $this->assertEquals('PropNewA value', $tested->propA);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed
     */
    protected function setUp()
    {
        $this->container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        InsideConstruct::setContainer($this->container);
    }
}
