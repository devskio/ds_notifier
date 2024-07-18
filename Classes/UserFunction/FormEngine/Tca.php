<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\UserFunction\FormEngine;

use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Event\EventInterface;
use Devsk\DsNotifier\StructureScout\NotifierEventStructureScout;

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
}
