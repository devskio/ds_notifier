<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Domain\Model\Event\Property\Placeholder;
use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Event\EventInterface;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException;

/**
 * Class Email
 * @package Devsk\DsNotifier\Domain\Model
 */
class Email extends Notification
{

    protected ?string $to = null;
    protected ?string $cc = null;
    protected ?string $bcc = null;

    public function send(EventInterface $event): void
    {
        $message = new FluidEmail();
        $message->setTemplate($event::modelName())
            ->assignMultiple(array_merge([
                'title' => $event::getNotifierEventAttribute()->getLabel(),
                'body' => $this->body,
            ], $event->getMarkerProperties()));

        // Replace placeholders in to, cc, and bcc
        $this->replaceEmailPlaceholders($event);

        $recipients = [
            'to' => $this->to,
            'cc' => $this->cc,
            'bcc' => $this->bcc
        ];

        foreach ($recipients as $type => $address) {
            if (!empty($address)) {
                $recipientsObj = new Notification\Email\Recipients($address);
                $recipientMethod = [$message, $type];
                $recipientMethod($recipientsObj->__toString());
            }
        }

        // Replace placeholders in the subject
        $this->replaceSubjectPlaceholders($event);

        $message->subject($this->subject);

        try {
            $message->getBody();
        } catch (InvalidTemplateResourceException) {
            $message->setTemplate('Default');
        }

        GeneralUtility::makeInstance(MailerInterface::class)->send($message);
    }

    private function replaceEmailPlaceholders(EventInterface $event): void
    {
        $emailProperties = $event->getEmailProperties();
        foreach ($emailProperties as $emailProperty) {
            $getter = 'get' . ucfirst($emailProperty['name']);

            foreach (['to', 'cc', 'bcc'] as $field) {
                if (str_contains($this->{$field}, $emailProperty['value'])) {
                    $this->{$field} = str_replace($emailProperty['value'], $event->{$getter}(), $this->{$field});
                }
            }
        }
    }

    private function replaceSubjectPlaceholders(EventInterface $event): void
    {
        $markers = $event->getMarkerPlaceholders();
        foreach ($markers as $marker) {
            $getter = 'get' . ucfirst($marker->getPropertyName());
            if (str_contains($this->subject, $marker->getPlaceholder())) {
                $this->subject = str_replace($marker->getPlaceholder(), $event->{$getter}(), $this->subject);
            }
        }
    }

}
