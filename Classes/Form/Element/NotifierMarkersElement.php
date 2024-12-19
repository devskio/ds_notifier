<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Form\Element;

use Devsk\DsNotifier\Event\EventInterface;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Form\Domain\Model\FormDefinition;
use TYPO3\CMS\Form\Mvc\Persistence\FormPersistenceManagerInterface;

/**
 * Class NotifierMarkersElement
 */
class NotifierMarkersElement extends AbstractFormElement
{

    const string DEFAULT_TEMPLATE_PATH = 'EXT:ds_notifier/Resources/Private/Templates/Form/Element/NotifierMarkers.html';

    public function render()
    {
        $row = $this->data['databaseRow'];
        $eventClass = $row['event'][0] ?? null;
        $view = $this->getMarkersView();
        $formValues = [];
        if (is_subclass_of($eventClass, EventInterface::class)) {
            if ($eventClass == 'Devsk\DsNotifier\Event\Form\SubmitFinisherEvent') {
                $formPersistenceIdentifier = $row['configuration']['data']['sDEF']['lDEF']['formDefinition']['vDEF'][0]?? null;
                if ($formPersistenceIdentifier) {
                    $formPersistenceManager = GeneralUtility::makeInstance(FormPersistenceManagerInterface::class);
                    if ($formPersistenceManager->exists($formPersistenceIdentifier)) {
                        $form = $formPersistenceManager->load($formPersistenceIdentifier);
                        foreach ($form['renderables']['0']['renderables'] as $renderable) {
                            if ($renderable['type'] != 'GridRow' && $renderable['type'] != 'Fieldset') {
                                $formValues[] = ['identifier' => '{formValues}.{' . $renderable['identifier'] . '}', 'label' => $renderable['label']];
                            }
                        }
                    }
                }
            }
            $view->assignMultiple([
                'event' => [
                    'identifier' => $eventClass,
                    'attribute' => $eventClass::getNotifierEventAttribute(),
                    'markers' => $eventClass::getMarkerPlaceholders(),
                    'formValues' => $formValues,
                ],
            ]);
        }

        $fieldInformationResult = $this->renderFieldInformation();
        $resultArray = $this->mergeChildReturnIntoExistingResult($this->initializeResultArray(), $fieldInformationResult, false);

        $resultArray['html'] = $view->render();

        return $resultArray;
    }

    protected function getMarkersView(): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($this->data['parameterArray']['fieldConf']['config']['templatePath']
            ?? self::DEFAULT_TEMPLATE_PATH
        );

        return $view;
    }
}
