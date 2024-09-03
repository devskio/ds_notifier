<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\EventListener;

use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Domain\Repository\NotificationRepository;
use Devsk\DsNotifier\Event\EventInterface;
use Devsk\DsNotifier\Event\Notifier\NotificationSendError;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;


final class NotifierListener
{

    public function __construct(
        private readonly LoggerInterface $logger,
        protected readonly NotificationRepository $notificationRepository,
        protected readonly EventDispatcher $eventDispatcher,
    )
    {}

    public function __invoke(EventInterface $event)
    {
        if ($event->isTerminated()) {
            $this->logger->info("Event " . $event::class . " terminated");
            return;
        }

        $this->logger->info("Event " . $event::class . " triggered");

        $notifications = $this->notificationRepository->findByEvent($event);

        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            try {
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
                if (!($event instanceof NotificationSendError)) {
                    $this->eventDispatcher->dispatch(new NotificationSendError(
                        $e,
                        $event
                    ));
                }
            }
        }
    }
}
