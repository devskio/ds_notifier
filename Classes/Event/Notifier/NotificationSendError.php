<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event\Notifier;

use Devsk\DsNotifier\Attribute\Event\Email;
use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Enum\EventGroup;
use Devsk\DsNotifier\Event\AbstractEvent;
use Devsk\DsNotifier\Event\EventInterface;
use Exception;

/**
 * Class NotificationSendError
 */
#[NotifierEvent(
    label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.'.__CLASS__,
    group: EventGroup::NOTIFIER,
)]
class NotificationSendError extends AbstractEvent
{
    #[Marker('LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.' . __CLASS__ . '.marker.error.message')]
    protected string $errorMessage = '';

    #[Marker('LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.' . __CLASS__ . '.marker.error.code')]
    protected ?int $errorCode = null;

    public function __construct(
        Exception $exception,
        #[Marker('LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.' . __CLASS__ . '.marker.event')]
        protected EventInterface $event,
        #[Email('LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.' . __CLASS__ . '.email.emergencyEmail')]
        protected ?string $emergencyEmail = null,
    )
    {
        $this->errorMessage = $exception->getMessage();
        $this->errorCode = $exception->getCode();
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    public function getEmergencyEmail(): string
    {
        return $this->emergencyEmail;
    }

    public function getEvent(): string
    {
        return (string)$this->event;
    }
}
