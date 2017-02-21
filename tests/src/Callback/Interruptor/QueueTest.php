<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.01.17
 * Time: 16:11
 */

namespace rollun\test\skeleton\Interruptor\Callback;

use rollun\callback\Callback\Interruptor\Queue as QueueInterruptor;
use rollun\test\skeleton\Queues\ExtractorTest;

class QueueTest extends ExtractorTest
{

    /**
     * @param array $callbacks
     * @param $value
     */
    public function addInQueue(array $callbacks, $value)
    {
        foreach ($callbacks as $callback) {
            $interruptor = new QueueInterruptor($callback, $this->queue);
            $interruptor($value);
        }
    }

}
