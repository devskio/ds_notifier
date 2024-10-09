<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Finisher;

use Devsk\DsNotifier\Event\Form\SubmitFinisherEvent;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

class DispatchFormNotifierFinisher extends AbstractFinisher
{

    protected function executeInternal()
    {
        GeneralUtility::makeInstance(EventDispatcher::class)
            ->dispatch(new SubmitFinisherEvent($this->finisherContext));
    }
}
