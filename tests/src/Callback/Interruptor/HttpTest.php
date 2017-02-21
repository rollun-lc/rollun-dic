<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 30.12.16
 * Time: 5:17 PM
 */

namespace rollun\test\skeleton\Interruptor\Callback;

use Interop\Container\ContainerInterface;
use rollun\callback\Callback\Interruptor\Http;
use rollun\callback\Callback\Interruptor\InterruptorAbstract;
use rollun\callback\Callback\Interruptor\Process;
use rollun\test\skeleton\Callback\CallbackTestDataProvider;
use Zend\Expressive\Helper\UrlHelper;

class HttpTest extends CallbackTestDataProvider
{

    protected $url;

    public function setUp()
    {
        /** @var ContainerInterface $container */
        $container = include 'config/container.php';

        $config = $container->get("config");
        $this->url = 'http://' . constant("HOST") . '/webhook/httpCallback';
    }

    /**
     * @param $callable
     * @param $val
     * @param $expected
     * @dataProvider provider_mainType()
     */
    public function test_httpInterruptorCallback($callable, $val, $expected)
    {
        $httpInterraptor = new Http($callable, $this->url);
        $result = $httpInterraptor($val);
        $this->assertTrue(isset($result['data']));
        $this->assertTrue($result['data']['status'] == 200);
        $this->assertTrue(isset($result[InterruptorAbstract::MACHINE_NAME_KEY]));
        $this->assertTrue(isset($result[InterruptorAbstract::INTERRUPTOR_TYPE_KEY]));
    }

    /**
     * @param $callable
     * @param $val
     * @param $expected
     * @dataProvider provider_mainType()
     */
    public function test_httpInterruptorInterruptor($callable, $val, $expected)
    {
        $callable = new Process($callable);
        $httpInterraptor = new Http($callable, $this->url);
        $result = $httpInterraptor($val);
        $this->assertTrue(isset($result['data']));
        $this->assertTrue(isset($result[InterruptorAbstract::MACHINE_NAME_KEY]));
        $this->assertEquals(Http::class, $result[InterruptorAbstract::INTERRUPTOR_TYPE_KEY]);
    }
}
