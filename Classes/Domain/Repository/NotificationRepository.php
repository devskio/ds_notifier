<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Repository;

use Devsk\DsNotifier\Event\EventInterface;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
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
        $constraints = [];
        $query = $this->createQuery();
        $event = ($event instanceof EventInterface) ? $event::identifier() : $event;

        $constraints[] = $query->equals('event', $event);
        $constraints[] = $query->logicalOr(
            $query->equals('sites', ''),
            $query->contains('sites', $this->site()?->getRootPageId())
        );

        $query->matching(
            $query->logicalAnd(...$constraints)
        );

        return $query->execute();
    }

    private function site(): ?SiteInterface
    {
        return isset($GLOBALS['TYPO3_REQUEST']) ? $GLOBALS['TYPO3_REQUEST']->getAttribute('site') : null;
    }

}
