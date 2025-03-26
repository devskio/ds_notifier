<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event\Form;

use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Domain\Model\Notification\FlexibleConfiguration;
use Devsk\DsNotifier\Enum\EventGroup;
use Devsk\DsNotifier\Event\AbstractEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Form\Domain\Finishers\FinisherContext;
use TYPO3\CMS\Form\Domain\Model\Renderable\AbstractRenderable;
use TYPO3\CMS\Form\Mvc\Configuration\ConfigurationManagerInterface as FormConfigurationManagerInterface;
use TYPO3\CMS\Form\Mvc\Persistence\FormPersistenceManagerInterface;

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

    public function getEmailProperties(): array
    {
        $properties = parent::getEmailProperties();

        /** @var AbstractRenderable $renderable */
        foreach ($this->finisherContext->getFormRuntime()->getFormDefinition()->getRenderablesRecursively() as $renderable) {
            if ($renderable->getType() === 'Email') {
                $properties['form'][$renderable->getIdentifier()] =
                    $this->formValues[$renderable->getIdentifier()];
            }
        }

        return $properties;
    }

    public static function getMarkerPlaceholders(?array $notificationData = null): array
    {
        $markerPlaceholders = parent::getMarkerPlaceholders();
        $formPersistenceIdentifier = $notificationData['configuration']['data']['sDEF']['lDEF']['formDefinition']['vDEF'][0] ?? null;

        if ($formPersistenceIdentifier) {
            $formPersistenceManager = GeneralUtility::makeInstance(FormPersistenceManagerInterface::class);
            [$formSettings, $typoScriptSettings] = self::getFormSettings();
            $form = $formPersistenceManager->load($formPersistenceIdentifier, $formSettings, $typoScriptSettings);
            $markerPlaceholders = array_merge($markerPlaceholders, self::getRenderablePlaceholders($form));
        }

        return $markerPlaceholders;
    }

    private static function getRenderablePlaceholders(array $data)
    {
        $markerPlaceholders = [];
        foreach ($data['renderables'] as $renderable) {
            if (isset($renderable['renderables'])) {
                $markerPlaceholders = array_merge($markerPlaceholders, self::getRenderablePlaceholders($renderable));
            } else {
                $markerPlaceholders[] = [
                    'placeholder' => '{formValues.' . $renderable['identifier'] . '}',
                    'label' => $renderable['label']
                ];
            }
        }

        return $markerPlaceholders;
    }

    public static function getFormSettings(): array
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
        $extFormConfigurationManager = GeneralUtility::makeInstance(FormConfigurationManagerInterface::class);
        $typoScriptSettings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'form');
        $formSettings = $extFormConfigurationManager->getYamlConfiguration($typoScriptSettings, false);
        if (!isset($formSettings['formManager'])) {
            // Config sub array formManager is crucial and should always exist. If it does
            // not, this indicates an issue in config loading logic. Except in this case.
            throw new \LogicException('Configuration could not be loaded', 1723717461);
        }
        return [$formSettings, $typoScriptSettings];
    }


}
