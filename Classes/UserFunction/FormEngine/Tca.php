<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\UserFunction\FormEngine;

use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\StructureScout\NotifierEventStructureScout;
use ReflectionAttribute;
use ReflectionClass;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class Tca
 */
class Tca
{

    public function notificationEventItemsProcFunc(&$params)
    {
        foreach (NotifierEventStructureScout::create()->get() as $eventClass) {
            /** @var NotifierEvent $notifierEventAttribute */
            $notifierEventAttribute = (new ReflectionClass($eventClass))
                ->getAttributes(NotifierEvent::class)[0]
                ->newInstance();

            $params['items'][] = [
                'label' => $notifierEventAttribute->getGroup()->getLabel(),
                'value' => '--div--',
            ];
            $params['items'][] = [
                'label' => $notifierEventAttribute->getLabel(),
                'value' => $notifierEventAttribute->getIdentifier(),
            ];
        }

    }
}
