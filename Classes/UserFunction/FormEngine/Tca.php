<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\UserFunction\FormEngine;

use Devsk\DsNotifier\Attribute\Event\Email;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Event\EventInterface;
use Devsk\DsNotifier\StructureScout\NotifierEventStructureScout;
use TYPO3\CMS\Core\Site\Entity\NullSite;

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

    public function notificationEventEmailsItemsProcFunc(&$params)
    {
        $parsedEmails = [];
        $this->addEventEmails($parsedEmails, $params['row']['event'][0]);
        $this->addYamlConfigEmails($parsedEmails, $params['site']);
        $params['items'] = $parsedEmails;
    }

    /**
     * Add emails from event classes.
     */
    private function addEventEmails(array &$parsedEmails, $eventId)
    {
        foreach (NotifierEventStructureScout::create()->get() as $eventClass) {
            if ($eventClass::identifier() == $eventId) {
                $emailsProperties = $eventClass::getEmailProperties();
                if (!empty($emailsProperties)) {
                    $parsedEmails[] = ['label' => 'Email Markers', 'value' => '--div--'];
                    foreach ($emailsProperties as $emailProperty) {
                        $parsedEmails[] = ['label' => $emailProperty['label'], 'value' => $emailProperty['value']];
                    }
                }
                break;
            }
        }
    }

    /**
     * Add emails from YAML configuration.
     */
    private function addYamlConfigEmails(array &$parsedEmails, $site)
    {
        if(!$site instanceof NullSite){
            $siteSettings = $site->getSettings()->get('ds_notifier');
            $emails = $siteSettings['channel']['email']['recipients'] ?? [];
            if (!empty($emails)) {
                $parsedEmails[] = ['label' => 'YAML Configuration Emails', 'value' => '--div--'];
                foreach ($emails as $email => $name) {
                    if (is_string($email) && preg_match('/^(.+?):(.+)$/', $email, $matches)) {
                        $emailAddress = trim($matches[1]);
                        $recipientName = trim($matches[2]);
                        $parsedEmails[] = [$emailAddress, "$recipientName<$emailAddress>"];
                    } elseif (is_string($name)) {
                        $this->parseEmailString($parsedEmails, $name);
                    } elseif (is_array($name)) {
                        foreach ($name as $emailAddress => $recipientName) {
                            $parsedEmails[] = [$emailAddress, "$recipientName<$emailAddress>"];
                        }
                    }
                }
            }
        }
    }

    /**
     * Parse an email string and add it to the parsed emails array.
     */
    private function parseEmailString(array &$parsedEmails, $emailString)
    {
        if (filter_var($emailString, FILTER_VALIDATE_EMAIL)) {
            $parsedEmails[] = [$emailString, $emailString];
        } elseif (preg_match('/^(.+?)<(.+?)>$/', $emailString, $matches)) {
            $recipientName = trim($matches[1]);
            $emailAddress = $matches[2];
            $parsedEmails[] = [$emailAddress, "$recipientName<$emailAddress>"];
        }
    }
}
