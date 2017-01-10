<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 23.12.16
 * Time: 3:16 PM
 */

namespace rolluncom\test\dic;

use Interop\Container\ContainerInterface;
use rolluncom\dic\Example\InheritanceSimpleDependency;
use rolluncom\dic\Example\SettersDefault;
use rolluncom\dic\Example\SimpleDependency;
use rolluncom\dic\Example\Inheritance;
use rolluncom\dic\Example\SimpleDependencyInit;
use rolluncom\dic\InsideConstruct;

class InsideConstructTest extends \PHPUnit_Framework_TestCase
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

    public function testInitServicesSimpleDependencyInit()
    {
        $this->container->method('has')
            ->will($this->returnValue(false));
        $tested = new SimpleDependencyInit();

        $this->assertEquals('simpleStringA', $tested->getSimpleStringA());
        $this->assertEquals(2.4, $tested->simpleNumericB);
        $this->assertEquals([0 => 'simpleArrayC'], $tested->getSimpleArrayC());
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
        $this->container = $this->getMock(ContainerInterface::class);
        InsideConstruct::setContainer($this->container);
    }
}
