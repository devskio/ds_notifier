<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

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
            ->assignMultiple([
                '_subject' => $this->subject,
                '_body' => $this->body,
                ...$event->getMarkerProperties(),
            ])
            ->subject($this->parseTemplateString($this->subject, $event->getMarkerProperties()))
            ->to(...$this->getRecipients($this->to, $event))
            ->cc(...$this->getRecipients($this->cc, $event))
            ->bcc(...$this->getRecipients($this->bcc, $event));

        # Try to compile email body for Event specific template if not exists fallback to Default template
        try {
            $message->getBody();
        } catch (InvalidTemplateResourceException) {
            $message->setTemplate('Default');
        }

        GeneralUtility::makeInstance(MailerInterface::class)->send($message);
    }

    protected function getRecipients(?string $recipients, EventInterface $event): Notification\Email\Recipients
    {
        return new Notification\Email\Recipients(
            $this->parseTemplateString($recipients, $event->getEmailProperties(), false)
        );
    }
}
