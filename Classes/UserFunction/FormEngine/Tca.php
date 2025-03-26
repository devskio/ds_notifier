<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\UserFunction\FormEngine;

use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Domain\Model\Event\Property\Placeholder;
use Devsk\DsNotifier\Event\EventInterface;
use Devsk\DsNotifier\Event\Form\SubmitFinisherEvent;
use Devsk\DsNotifier\FormFramework\Finisher\DsNotifierFormFrameworkFinisher;
use Devsk\DsNotifier\StructureScout\NotifierEventStructureScout;
use Devsk\DsNotifier\Utility\NotifierUtility;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Form\Mvc\Configuration\ConfigurationManagerInterface as FormConfigurationManagerInterface;
use TYPO3\CMS\Form\Mvc\Persistence\FormPersistenceManagerInterface;

/**
 * Class Tca
 */
class Tca
{

    /**
     * Populate items with discovered Notifier Events and group them
     * @param $params
     * @return void
     * @throws \ReflectionException
     */
    public function notificationEventItemsProcFunc(&$params)
    {
        $groups = [];

        //TODO: Maybe nicer way to group items
        /** @var EventInterface $eventClass */
        foreach (NotifierEventStructureScout::create()->get() as $eventClass) {
            /** @var NotifierEvent $notifierEventAttribute */
            $notifierEventAttribute = $eventClass::getNotifierEventAttribute();

            $groups[$notifierEventAttribute->getGroup()->getLabel()][] = [
                'label' => $notifierEventAttribute->getLabel(),
                'value' => $eventClass::identifier(),
            ];
        }

        if (!empty($groups)) {
            foreach ($groups as $group => $groupItems) {
                $params['items'][] = [
                    'label' => $group,
                    'value' => '--div--',
                ];
                foreach ($groupItems as $item) {
                    $params['items'][] = $item;
                }
            }
        }

    }

    /**
     * Populate items with discovered Notifier Emails and Global recipients
     * @param $params
     * @return void
     */
    public function notificationEmailItemsProcFunc(&$params)
    {
        $this->addEventEmails($params['items'], $params['row']['event'][0] ?? '', $params['row']);
        $this->addSiteGlobalEmails($params['items'], $params['site']);
    }

    /**
     * Generate dynamic TCA FlexForm configuration
     * @return array
     */
    public static function flexFormTcaConfiguration(): array
    {
        $definitionsStructures = [];

        /** @var EventInterface $eventClass */
        foreach (NotifierEventStructureScout::create()->get() as $eventClass) {
            if ($flexibleConfigurationFile = $eventClass::getNotifierEventAttribute()->getFlexibleConfigurationFile()) {
                $definitionsStructures[$eventClass::identifier()] = $flexibleConfigurationFile;
            }
        }

        if (!empty($definitionsStructures)) {
            $definitionsStructures['default'] = 'FILE:EXT:ds_notifier/Configuration/FlexForm/Event/Default.xml';

            return [
                'displayCond' => 'FIELD:event:IN:'.implode(',', array_keys($definitionsStructures)),
                'config' => [
                    'type' => 'flex',
                    'ds_pointerField' => 'event',
                    'ds' => $definitionsStructures,
                    'behaviour' => [
                        'allowLanguageSynchronization' => true,
                    ],
                ],
            ];
        }

        return [
            'config' => [
                'type' => 'passthrough',
            ],
        ];
    }

    /**
     * @param array $parsedEmails
     * @param string|EventInterface $eventClass
     * @return void
     */
    private function addEventEmails(array &$parsedEmails, string|EventInterface $eventClass, array $row = [])
    {
        if (is_subclass_of($eventClass, EventInterface::class)) {
            $emailPlaceholders = $eventClass::getEmailPlaceholders();


            /** @var Placeholder $emailPlaceholder */
            foreach ($emailPlaceholders as $emailPlaceholder) {
                $parsedEmails[] = [
                    'label' => $emailPlaceholder->getLabel(),
                    'value' => $emailPlaceholder->getPlaceholder(),
                    'group' => 'event',
                ];
            }

            if ($eventClass === SubmitFinisherEvent::class) {
                $formPersistenceIdentifier = $row['configuration']['data']['sDEF']['lDEF']['formDefinition']['vDEF'][0] ?? null;
                if ($formPersistenceIdentifier) {
                    $formPersistenceManager = GeneralUtility::makeInstance(FormPersistenceManagerInterface::class);
                    [$formSettings, $typoScriptSettings] = $this->getFormSettings();
                    $formDefinition = $formPersistenceManager->load($formPersistenceIdentifier, $formSettings, $typoScriptSettings);
                    foreach (NotifierUtility::collectFormEmailRenderables($formDefinition['renderables']) as $emailRenderable) {
                        $parsedEmails[] = [
                            'label' => $emailRenderable['label'],
                            'value' => "{form.{$emailRenderable['identifier']}}",
                            'group' => 'form',
                        ];
                    }
                }
            }
        }
    }



    /**
     * Add emails from Site settings.
     */
    private function addSiteGlobalEmails(array &$parsedEmails, $site)
    {
        if ($site instanceof Site) {
            $siteSettings = $site->getSettings()->get('ds_notifier');
            $emails = $siteSettings['channel']['email']['recipients'] ?? [];

            if (!empty($emails)) {
                $emails = Address::createArray($emails);

                foreach ($emails as $email) {
                    $parsedEmails[] = [
                        'label' => $email->toString(),
                        'value' => $email->toString(),
                        'group' => 'site'
                    ];
                }
            }
        }
    }

    public function formDefinitionItemsProcFunc(&$params)
    {
        $formPersistenceManager = GeneralUtility::makeInstance(FormPersistenceManagerInterface::class);
        [$formSettings, $typoScriptSettings] = $this->getFormSettings();
        foreach ($formPersistenceManager->listForms($formSettings) as $form) {
            $persistenceIdentifier = $form['persistenceIdentifier'];
            $formDefinition = $formPersistenceManager->load($persistenceIdentifier, $formSettings, $typoScriptSettings);
            $finishers = $formDefinition['finishers'] ?? [];

            foreach ($finishers as $finisher) {
                if ($finisher['identifier'] === DsNotifierFormFrameworkFinisher::getIdentifier()) {
                    $params['items'][] = [
                        'label' => $formDefinition['label'] . ' (' . $persistenceIdentifier . ')',
                        'value' => $persistenceIdentifier,
                    ];
                }
            }
        }
    }

    protected function getFormSettings(): array
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
