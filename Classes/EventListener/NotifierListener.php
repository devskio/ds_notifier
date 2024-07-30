<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\EventListener;

use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Domain\Repository\NotificationRepository;
use Devsk\DsNotifier\Event\EventInterface;
use Devsk\DsNotifier\Exception\EventCancelledException;
use Psr\Log\LoggerInterface;


final class NotifierListener
{

    public function __construct(
        private readonly LoggerInterface $logger,
        protected readonly NotificationRepository $notificationRepository,
    )
    {}

    public function __invoke(EventInterface $event)
    {
        $this->logger->info("Event " . $event::class . " triggered");

        $notifications = $this->notificationRepository->findByEvent($event);

        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            try {
                $event->checkIfCancelled($notification);
                $notification->send($event);
                $this->logger->info('Notification sent', [
                    'event' => $event::identifier(),
                    'notification' => $notification->getUid(),
                ]);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), [
                    'event' => $event::identifier(),
                    'notification' => $notification->getUid(),
                ]);
            }
        }

    }
}
