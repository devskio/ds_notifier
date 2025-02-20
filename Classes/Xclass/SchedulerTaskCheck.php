<?php

namespace Devsk\DsNotifier\Xclass;

use Devsk\DsNotifier\Event\Notifier\TaskFailure;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Scheduler;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class SchedulerTaskCheck extends Scheduler
{
    public function executeTask(AbstractTask $task): bool
    {
        try {
            return parent::executeTask($task);
        } catch (\Exception $e) {
            $eventDispatcher = GeneralUtility::makeInstance(EventDispatcher::class);
            $eventDispatcher->dispatch(new TaskFailure($task, $e->getMessage()));
            return false;
        }
    }
}

