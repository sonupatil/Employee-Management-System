<?php

namespace SlmQueueTest\Listener\Strategy;

use PHPUnit_Framework_TestCase;
use SlmQueue\Strategy\InterruptStrategy;
use SlmQueue\Worker\WorkerEvent;
use SlmQueueTest\Asset\SimpleJob;

class InterruptStrategyTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var InterruptStrategy
     */
    protected $listener;

    /**
     * @var WorkerEvent
     */
    protected $event;

    public function setUp()
    {
        $queue = $this->getMockBuilder('SlmQueue\Queue\AbstractQueue')
            ->disableOriginalConstructor()
            ->getMock();
        $worker = $this->getMock('SlmQueue\Worker\WorkerInterface');

        $ev    = new WorkerEvent($worker, $queue);
        $job   = new SimpleJob();

        $ev->setJob($job);

        $this->listener = new InterruptStrategy();
        $this->event    = $ev;
    }

    public function testListenerInstanceOfAbstractStrategy()
    {
        $this->assertInstanceOf('SlmQueue\Strategy\AbstractStrategy', $this->listener);
    }

    public function testListensToCorrectEvents()
    {
        $evm = $this->getMock('Zend\EventManager\EventManagerInterface');

        $evm->expects($this->at(0))->method('attach')
            ->with(WorkerEvent::EVENT_PROCESS_IDLE, array($this->listener, 'onStopConditionCheck'));
        $evm->expects($this->at(1))->method('attach')
            ->with(WorkerEvent::EVENT_PROCESS_QUEUE, array($this->listener, 'onStopConditionCheck'));
        $evm->expects($this->at(2))->method('attach')
            ->with(WorkerEvent::EVENT_PROCESS_STATE, array($this->listener, 'onReportQueueState'));

        $this->listener->attach($evm);
    }

    public function testOnStopConditionCheckHandler_NoSignal()
    {
        $this->listener->onStopConditionCheck($this->event);
        $this->assertFalse($this->listener->onReportQueueState($this->event));
        $this->assertFalse($this->event->shouldExitWorkerLoop());

    }

    public function testOnStopConditionCheckHandler_SIGTERM()
    {
        $this->listener->onPCNTLSignal(SIGTERM);
        $this->listener->onStopConditionCheck($this->event);
        $this->assertContains('interrupt by an external signal', $this->listener->onReportQueueState($this->event));
        $this->assertTrue($this->event->shouldExitWorkerLoop());
    }

    public function testOnStopConditionCheckHandler_SIGINT()
    {
        $this->listener->onPCNTLSignal(SIGTERM);
        $this->listener->onStopConditionCheck($this->event);
        $this->assertContains('interrupt by an external signal', $this->listener->onReportQueueState($this->event));
        $this->assertTrue($this->event->shouldExitWorkerLoop());
    }
}
