<?php

declare(strict_types=1);

namespace Devsk\DsNotifier\EventListener\FlexForm;

use Devsk\DsNotifier\StructureScout\NotifierEventStructureScout;
use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureIdentifierInitializedEvent;
use TYPO3\CMS\Core\Configuration\Event\BeforeFlexFormDataStructureParsedEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hooks into TYPO3's FlexFormTools to dynamically resolve the correct
 * FlexForm data structure based on the selected notifier event.
 */
class NotifierFlexFormListener
{
    private const TABLE = 'tx_dsnotifier_domain_model_notification';
    private const FIELD = 'configuration';

    /**
     * Override the dataStructureKey in the FlexForm identifier based on the
     * row's event value. This ensures the correct DS key is used even when
     * $result['processedTca']['ds'] only contains 'default'.
     */
    public function modifyIdentifier(AfterFlexFormDataStructureIdentifierInitializedEvent $event): void
    {
        if ($event->getTableName() !== self::TABLE || $event->getFieldName() !== self::FIELD) {
            return;
        }

        $row = $event->getRow();
        $eventValue = $row['event'] ?? '';
        if (is_array($eventValue)) {
            $eventValue = (string)($eventValue[0] ?? '');
        }

        if (empty($eventValue)) {
            return;
        }

        foreach (NotifierEventStructureScout::create()->get() as $eventClass) {
            if ($eventClass::identifier() === $eventValue
                && $eventClass::getNotifierEventAttribute()->getFlexibleConfigurationFile()
            ) {
                $identifier = $event->getIdentifier();
                $identifier['dataStructureKey'] = $eventValue;
                $event->setIdentifier($identifier);
                break;
            }
        }
    }

    /**
     * Provide the parsed FlexForm data structure directly, bypassing the
     * $GLOBALS['TCA'] lookup in FlexFormTools::parseDataStructureByIdentifier().
     */
    public function provideDataStructure(BeforeFlexFormDataStructureParsedEvent $event): void
    {
        $identifier = $event->getIdentifier();

        if (($identifier['type'] ?? '') !== 'tca'
            || ($identifier['tableName'] ?? '') !== self::TABLE
            || ($identifier['fieldName'] ?? '') !== self::FIELD
        ) {
            return;
        }

        $dataStructureKey = $identifier['dataStructureKey'] ?? 'default';
        if ($dataStructureKey === 'default') {
            return;
        }

        foreach (NotifierEventStructureScout::create()->get() as $eventClass) {
            if ($eventClass::identifier() === $dataStructureKey) {
                $flexFile = $eventClass::getNotifierEventAttribute()->getFlexibleConfigurationFile();
                if ($flexFile) {
                    $filePath = str_starts_with($flexFile, 'FILE:') ? substr($flexFile, 5) : $flexFile;
                    $absolutePath = GeneralUtility::getFileAbsFileName($filePath);
                    if (file_exists($absolutePath)) {
                        $dataStructure = GeneralUtility::xml2array((string)file_get_contents($absolutePath));
                        if (is_array($dataStructure)) {
                            $event->setDataStructure($dataStructure);
                        }
                    }
                }
                break;
            }
        }
    }
}

