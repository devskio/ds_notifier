<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event\Custom;

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
    label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Custom\CustomEvent',
    group: EventGroup::CUSTOM,
)]
class CustomEvent extends AbstractEvent
{

    #[Email (label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Custom\CustomEvent.email.emailTo')]
    protected ?string $emailTo;

    #[Email (label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Custom\CustomEvent.email.emailFrom')]
    protected ?string $emailFrom;

    #[Marker (label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Custom\CustomEvent.marker.name')]
    protected ?string $name;

    /**
     * @param string|null $emailTo
     * @param string|null $emailFrom
     * @param string|null $name
     */
    public function __construct(?string $emailTo = '', ?string $emailFrom = '', ?string $name = '', ?bool $cancelled = false)
    {
        $this->emailTo = $emailTo;
        $this->emailFrom = $emailFrom;
        $this->name = $name;
        $this->cancelled = $cancelled;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmailTo(): ?string
    {
        return $this->emailTo;
    }

    public function getEmailFrom(): ?string
    {
        return $this->emailFrom;
    }
}
