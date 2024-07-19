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

    protected ?Notification\Email\Recipients $to = null;
    protected ?string $cc = null;
    protected ?string $bcc = null;

    public function send(EventInterface $event): void
    {
        $message = new FluidEmail();
        $message->to('test@test.at')
            ->subject($this->subject)
            ->setTemplate($event::modelName())
//            ->setRequest()
            ->assignMultiple([
                'title' => $event::getNotifierEventAttribute()->getLabel(),
                'body' => $this->body,
            ])
            ->assignMultiple($event->getMarkerProperties());

        try {
            $message->getBody();
        } catch (InvalidTemplateResourceException) {
            $message->setTemplate('Default');
        }

        GeneralUtility::makeInstance(MailerInterface::class)->send($message);
    }
}
