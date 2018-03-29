<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 23.12.16
 * Time: 3:16 PM
 */

namespace rollun\test\dic;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use rollun\dic\Example\InheritanceSimpleDependency;
use rollun\dic\Example\SerializedService;
use rollun\dic\Example\SettersDefault;
use rollun\dic\Example\SimpleDependency;
use rollun\dic\Example\Inheritance;
use rollun\dic\Example\SimpleDependencyInit;
use rollun\dic\Example\SimpleService;
use rollun\dic\Example\SimpleTypeService;
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

    public function testCallOnWakeup()
    {
        $simpleDependency = new SimpleDependency();
        /** @var $container MockObject */
        $this->container->method("has")->with(SimpleDependency::class)->willReturn(true);
        $this->container->method("get")->with(SimpleDependency::class)->willReturn($simpleDependency);
        $tested = new SerializedService($simpleDependency);
        $serializeData = serialize($tested);
        /** @var SerializedService $afterSerializeTested */
        $afterSerializeTested = unserialize($serializeData);
        $this->assertEquals($tested->getSimpleService(), $afterSerializeTested->getSimpleService());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCallNotInConstructorException() {
        InsideConstruct::init();
    }

    /**
     *
     */
    public function testNotLoadSimpleType() {
        $this->container->method("has")->with()->willReturn(true);
        $stub = $this->returnCallback(function (){throw new \RuntimeException("");});
        $this->container->method("get")->with("simpleStringA")->will($stub);
        $this->container->method("get")->with("simpleNumericB")->will($stub);
        $this->container->method("get")->with("simpleArrayC")->will($stub);
        $simple = new SimpleTypeService(1,"asd");
        $this->assertEquals(1, $simple->getInt());
        $this->assertEquals("asd", $simple->getString());
        $this->assertEquals(9.8, $simple->getFloat());
        $this->assertEquals([], $simple->getArray());

    }

    public function testNotFoundNonTypingService() {
        $this->container->method("has")->willReturn(false);
        $simpleDep = new SimpleDependency();
        $this->assertEquals('simpleStringA',$simpleDep->getSimpleStringA());
        $this->assertEquals(2.4,$simpleDep->simpleNumericB);
        $this->assertEquals([0 => 'simpleArrayC'],$simpleDep->getSimpleArrayC());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNotFoundServiceException() {
        $this->container->method("has")->willReturn(false);
        new SimpleService();
    }

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
