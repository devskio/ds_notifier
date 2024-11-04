<?php

declare(strict_types=1);

namespace Devsk\DsNotifier\FormFramework\Finisher;

use Devsk\DsNotifier\Event\Form\SubmitFinisherEvent;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

class DsNotifierFormFrameworkFinisher extends AbstractFinisher
{
    protected function executeInternal()
    {
        GeneralUtility::makeInstance(EventDispatcher::class)
            ->dispatch(new SubmitFinisherEvent($this->finisherContext));
    }

    public static function getIdentifier(): string
    {
        return (new \ReflectionClass(self::class))->getShortName();
    }
}
