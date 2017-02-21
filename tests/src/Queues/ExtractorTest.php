<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.01.17
 * Time: 13:24
 */

namespace rollun\test\skeleton\Queues;


use Interop\Container\ContainerInterface;
use rollun\callback\Callback\Example\CallMe;
use rollun\callback\Callback\Interruptor\Http;
use rollun\callback\Callback\Interruptor\Job;
use rollun\callback\Callback\Interruptor\Process;
use rollun\callback\Queues\Extractor;
use rollun\callback\Queues\Queue;
use rollun\callback\Queues\QueueInterface;


class ExtractorTest extends \PHPUnit_Framework_TestCase
{
    /** @var Extractor*/
    protected $object;

    /** @var QueueInterface */
    protected $queue;

    protected $config;

    protected $queueName;

    public function setUp()
    {
        $queueName = 'test_extractor';
        $this->queue = new Queue($queueName);
        /** @var ContainerInterface $container */
        $container = include 'config/container.php';
       // $this->config = $container->get('config');
    }

    public function provider_type()
    {
        $stdObject = (object)['prop' => 'Hello '];
        //function
        return array(
            [
                [
                    function ($val) {
                        return 'Hello ' . $val;
                    },
                    new Process(function ($val) use ($stdObject) {
                        return $stdObject->prop . $val;
                    }),
                    new Process(new CallMe()),
                    new Process([new CallMe(), 'staticMethod']),
                    '\\' . CallMe::class . '::staticMethod'
                ],
                "World"
            ],
        );
    }

    public function addInQueue(array $callbacks, $value)
    {
        foreach ($callbacks as $callback){
            $job = new Job($callback, $value);
            $this->queue->addMessage($job->serializeBase64());
        }
    }

    /**
     * @param $callbacks
     * @param $value
     * @dataProvider provider_type()
     */
    public function test_extractQueue(array $callbacks, $value)
    {
        $this->object = new Extractor($this->queue);

        $this->addInQueue($callbacks, $value);

        $i = 0;
        while($this->object->extract()){
            $i++;
        };
        $this->assertEquals(count($callbacks), $i);
    }
}
