<?php

namespace SlmQueue\Strategy;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SlmQueue\Worker\WorkerEvent;
use Zend\EventManager\EventManagerInterface;

class FileWatchStrategy extends AbstractStrategy
{
    /**
     * @var string
     */
    protected $pattern = '/^\.\/(config|module).*\.(php|phtml)$/';

    /**
     * Watching these files
     *
     * @var array
     */
    protected $files = array();

    /**
     * Seconds between checks while idling
     *
     * @var int defaults to 5 minutes
     */
    protected $idleThrottleTime = 300;

    /**
     * Time the previous idle event occured and a check on the stop condition occured
     *
     * @var float
     */
    protected $previousIdlingTime;

    /**
     * @param string $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        $this->files   = array();
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param int $idle_throttle_time
     */
    public function setIdleThrottleTime($idleThrottleTime)
    {
        $this->idleThrottleTime = $idleThrottleTime;
    }

    /**
     * Files being watched
     *
     * @return array|null
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            WorkerEvent::EVENT_PROCESS_IDLE,
            array($this, 'onStopConditionCheck'),
            $priority
        );
        $this->listeners[] = $events->attach(
            WorkerEvent::EVENT_PROCESS_JOB,
            array($this, 'onStopConditionCheck'),
            1000
        );
        $this->listeners[] = $events->attach(
            WorkerEvent::EVENT_PROCESS_STATE,
            array($this, 'onReportQueueState'),
            $priority
        );
    }

    /**
     * @param  WorkerEvent $event
     * @return void
     */
    public function onStopConditionCheck(WorkerEvent $event)
    {
        if ($event->getName() == WorkerEvent::EVENT_PROCESS_IDLE) {
            if ($this->previousIdlingTime + $this->idleThrottleTime > microtime(true)) {
                return;
            } else {
                $this->previousIdlingTime = microtime(true);
            }
        }

        if (!count($this->files)) {
            $this->constructFileList();

            $this->state = sprintf("watching %s files for modifications", count($this->files));
        }

        foreach ($this->files as $checksum => $file) {
            if (!file_exists($file) || !is_readable($file) || (string) $checksum !== hash_file('crc32', $file)) {
                $event->exitWorkerLoop();

                $this->state = sprintf("file modification detected for '%s'", $file);
            }
        }
    }

    /**
     * @return void
     */
    protected function constructFileList()
    {
        $iterator   = new RecursiveDirectoryIterator('.', RecursiveDirectoryIterator::FOLLOW_SYMLINKS);
        $files      = new RecursiveIteratorIterator($iterator);

        /** @var $file \SplFileInfo  */
        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }

            if (!preg_match($this->pattern, $file)) {
                continue;
            }

            $this->files[hash_file('crc32', $file)] = (string) $file;
        }
    }
}
