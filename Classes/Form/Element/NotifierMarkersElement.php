<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Form\Element;

use Devsk\DsNotifier\Event\EventInterface;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Class NotifierMarkersElement
 */
class NotifierMarkersElement extends AbstractFormElement
{

    const string DEFAULT_TEMPLATE_PATH = 'EXT:ds_notifier/Resources/Private/Templates/Form/Element/NotifierMarkers.html';

    public function render(): array
    {
        $row = $this->data['databaseRow'];
        $eventClass = $row['event'][0] ?? null;
        $view = $this->getMarkersView();

        if (is_subclass_of($eventClass, EventInterface::class)) {
            $view->assignMultiple([
                'event' => [
                    'identifier' => $eventClass,
                    'attribute' => $eventClass::getNotifierEventAttribute(),
                    'markers' => $eventClass::getMarkerPlaceholders()
                ],
            ]);
        }

        $fieldInformationResult = $this->renderFieldInformation();
        $resultArray = $this->mergeChildReturnIntoExistingResult($this->initializeResultArray(), $fieldInformationResult, false);

        $resultArray['html'] = $view->render();

        return $resultArray;
    }

    protected function getMarkersView(): ViewInterface
    {
        $templatePath = $this->data['parameterArray']['fieldConf']['config']['templatePath']
            ?? self::DEFAULT_TEMPLATE_PATH;

        if ((new Typo3Version())->getMajorVersion() < 13) {
            // @extensionScannerIgnoreLine
            // @todo: Remove this once we drop support for TYPO3 v12
            $view = GeneralUtility::makeInstance(StandaloneView::class);
        } else {
            $view = GeneralUtility::makeInstance(ViewFactoryInterface::class)
                ->create(new ViewFactoryData());
        }

        $view->setTemplatePathAndFilename($templatePath);

        return $view;
    }
}
