<?php

declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Event\EventInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException;

/**
 * Class Email
 */
class Email extends Notification
{
    protected ?Notification\Email\Recipients $to = null;
    protected ?Notification\Email\Recipients $cc = null;
    protected ?Notification\Email\Recipients $bcc = null;

    public function send(EventInterface $event): void
    {
        $message = new FluidEmail();

        $message->setTemplate($event::modelName())
            ->assignMultiple([
                '_notification' => $this,
                '_subject' => $this->subject,
                '_body' => $this->body,
                ...$event->getMarkerProperties(),
            ])
            ->subject($this->getCompiledSubject($event->getMarkerProperties()))
            ->to(...$this->getTo($event))
            ->cc(...$this->getCc($event))
            ->bcc(...$this->getBcc($event));

        if (isset($GLOBALS['TYPO3_REQUEST']) && $GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface) {
            $message->setRequest($GLOBALS['TYPO3_REQUEST']);
        }

        // Try to compile email body for Event specific template if not exists fallback to Default template
        try {
            $message->getBody();
        } catch (InvalidTemplateResourceException) {
            $message->setTemplate('Default');
        }

        GeneralUtility::makeInstance(MailerInterface::class)->send($message);
    }

    private function getCompiledRecipientsFor(?Notification\Email\Recipients $recipients, ?EventInterface $event): Notification\Email\Recipients
    {
        if ($event) {
            foreach ($recipients as $key => $recipient) {
                if (is_string($recipient) && str_contains($recipient, '{') && str_contains($recipient, '}')) {
                    $parsedEmail = $this->parseTemplateString($recipient, $event->getEmailProperties(), false);
                    $recipients->updateRecipient(
                        Address::create($parsedEmail),
                        $key
                    );
                }
            }
        }

        return $recipients;
    }

    public function getTo(?EventInterface $event): ?Email\Recipients
    {
        return $this->getCompiledRecipientsFor($this->to, $event);
    }

    public function getCc(?EventInterface $event): ?Email\Recipients
    {
        return $this->getCompiledRecipientsFor($this->cc, $event);
    }

    public function getBcc(?EventInterface $event): ?Email\Recipients
    {
        return $this->getCompiledRecipientsFor($this->bcc, $event);
    }
}
