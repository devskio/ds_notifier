<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\EventListener;

use Devsk\DsNotifier\Event\EventInterface;
use Psr\Log\LoggerInterface;
//use Symfony\Component\Notifier\Notification\Notification;
//use Symfony\Component\Notifier\NotifierInterface;
//use Symfony\Component\Notifier\Recipient\Recipient;

final class NotifierListener
{

    public function __construct(
        private readonly LoggerInterface $logger,
//        private readonly NotifierInterface $notifier
    )
    {}

    public function __invoke(EventInterface $event)
    {
        $this->logger->info("Event " . $event::class . " triggered");
        //TODO: Implement notifier notification handling via eg https://symfony.com/doc/current/notifier.html
//        $notification = new Notification('Dummy notification', ['email']);
//        $notification->content('Dummy content');
//
//        $recipient = new Recipient('development@devsk.io');
//
//        $this->notifier->send(
//            $notification,
//            $recipient
//        );
    }
}
