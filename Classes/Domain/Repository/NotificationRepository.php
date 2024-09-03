<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Repository;

use Devsk\DsNotifier\Event\EventInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class NotificationRepository
 * @package Devsk\DsNotifier\Domain\Repository
 */
class NotificationRepository extends Repository
{

    public function initializeObject()
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function findByEvent(string|EventInterface $event): QueryResultInterface
    {
        $event = ($event instanceof EventInterface) ? $event::identifier() : $event;

        return $this->findBy([
            'event' => $event
        ]);
    }

}
