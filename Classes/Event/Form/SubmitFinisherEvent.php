<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event\Form;

use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Domain\Model\Notification\FlexibleConfiguration;
use Devsk\DsNotifier\Enum\EventGroup;
use Devsk\DsNotifier\Event\AbstractEvent;
use TYPO3\CMS\Form\Domain\Finishers\FinisherContext;

/**
 * Class NotificationSendError
 */
#[NotifierEvent(
    label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.'.__CLASS__,
    group: EventGroup::FORM,
    flexibleConfigurationFile: 'FILE:EXT:ds_notifier/Configuration/FlexForm/Event/SubmitFinisherEvent.xml',
)]
class SubmitFinisherEvent extends AbstractEvent
{

    #[Marker('LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.'.__CLASS__.'.marker.formValues')]
    protected array $formValues = [];

    public function __construct(private FinisherContext $finisherContext)
    {
        if ($this->finisherContext->isCancelled()) {
            $this->terminateEventNotification("Form {$finisherContext->getFormRuntime()->getIdentifier()} finisher was cancelled");
        }

        $this->formValues = $this->finisherContext->getFormValues();
    }

    public function applyNotificationConfiguration(?FlexibleConfiguration $configuration): void
    {
        if ($configuration->getFormDefinition() !== $this->finisherContext->getFormRuntime()->getFormDefinition()->getPersistenceIdentifier()) {
            $this->terminateEventNotification("Form notification not configured for form {$configuration->getFormDefinition()}");
        }
    }

    public function getFormValues(): array
    {
        return $this->formValues;
    }
}
