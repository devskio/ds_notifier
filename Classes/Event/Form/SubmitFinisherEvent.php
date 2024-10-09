<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event\Form;

use Devsk\DsNotifier\Attribute\Event\Email;
use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Enum\EventGroup;
use Devsk\DsNotifier\Event\AbstractEvent;
use Devsk\DsNotifier\Event\EventInterface;
use Exception;
use TYPO3\CMS\Form\Domain\Finishers\FinisherContext;

/**
 * Class NotificationSendError
 */
#[NotifierEvent(
    label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.'.__CLASS__,
    group: EventGroup::NOTIFIER,
)]
class SubmitFinisherEvent extends AbstractEvent
{
    #[Marker('LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.' . __CLASS__ . '.marker.error.message')]
    protected string $errorMessage = '';

    public function __construct(FinisherContext $context)
    {

    }
}
