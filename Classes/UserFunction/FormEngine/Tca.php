<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\UserFunction\FormEngine;

use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Domain\Model\Event\Property\Placeholder;
use Devsk\DsNotifier\Event\EventInterface;
use Devsk\DsNotifier\StructureScout\NotifierEventStructureScout;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Site\Entity\Site;

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
        $this->addEventEmails($params['items'], $params['row']['event'][0]);
        $this->addSiteGlobalEmails($params['items'], $params['site']);
    }

    /**
     * @param array $parsedEmails
     * @param string|EventInterface $eventClass
     * @return void
     */
    private function addEventEmails(array &$parsedEmails, string|EventInterface $eventClass)
    {
        if (is_subclass_of($eventClass, EventInterface::class)) {
            $emailPlaceholders = $eventClass::getEmailPlaceholders();
            $parsedEmails[] = [
                'label' => $eventClass::getNotifierEventAttribute()->getLabel(),
                'value' => '--div--'
            ];

            /** @var Placeholder $emailPlaceholder */
            foreach ($emailPlaceholders as $emailPlaceholder) {
                $parsedEmails[] = [
                    'label' => $emailPlaceholder->getLabel(),
                    'value' => $emailPlaceholder->getPropertyName()
                ];
            }

        }

    }

    /**
     * Add emails from Site settings.
     */
    private function addSiteGlobalEmails(array &$parsedEmails, $site)
    {
        if($site instanceof Site){
            $siteSettings = $site->getSettings()->get('ds_notifier');
            $emails = $siteSettings['channel']['email']['recipients'] ?? [];

            if (!empty($emails)) {
                $parsedEmails[] = [
                    'label' => 'Global email recipients',
                    'value' => '--div--'
                ];
                $emails = Address::createArray($emails);

                foreach ($emails as $email) {
                    $parsedEmails[] = [
                        'label' => $email->toString(),
                        'value' => $email->getAddress(),
                    ];
                }
            }

        }
    }
}
