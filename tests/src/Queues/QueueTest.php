<?php

namespace rollun\test\skeleton\Queues;

use rollun\callback\Queues\Queue;
use rollun\promise\Promise\Promise;
use rollun\callback\Queues\QueueInterface;
use rollun\dic\InsideConstruct;

class QueueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var QueueInterface
     */
    protected $object;

    protected function setUp()
    {
//        $container = include 'config/container.php';
//        InsideConstruct::setContainer($container);

        $this->object = new Queue('test_queue');
        $this->object->purgeQueue('test_queue');
    }

    public function test__getNullMessage()
    {
        $message = $this->object->getMessage();
        $this->assertEquals(null, $message);
    }

    public function test__addMessage()
    {

        $this->object->addMessage('test1');
        $message = $this->object->getMessage();
        $this->assertEquals('test1', $message->getData());
    }

}
