<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event\Typo3\Core\Cache;

use Devsk\DsNotifier\Attribute\Event\Email;
use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Enum\EventGroup;
use Devsk\DsNotifier\Event\AbstractEvent;
use TYPO3\CMS\Core\Cache\Event\CacheFlushEvent;

/**
 * Class CacheFlush
 */
#[NotifierEvent(
    label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Typo3\Core\Cache\CacheFlush',
    group: EventGroup::TYPO3,
)]
class CacheFlush extends AbstractEvent
{

    #[Email]
    protected ?string $email = null;

    #[Marker(label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Typo3\Core\Cache\CacheFlush.marker.groups')]
    protected ?string $groups = null;

    public function __construct(
        public readonly CacheFlushEvent $cacheFlushEvent
    )
    {
        $this->groups = implode(', ', $this->cacheFlushEvent->getGroups());
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getGroups(): ?string
    {
        return $this->groups;
    }
}
