<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\EventListener;

use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Domain\Repository\NotificationRepository;
use Devsk\DsNotifier\Event\EventInterface;
use Devsk\DsNotifier\Event\Notifier\NotificationSendError;
use Devsk\DsNotifier\Exception\EventNotificationTerminatedException;
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
        $this->logger->info("Event " . $event::class . " triggered");

        $notifications = $this->notificationRepository->findByEvent($event);

        /** @var Notification $notification */
        foreach ($notifications as $notification) {
            try {
                $event->applyNotificationConfiguration($notification->getConfiguration());
                if ($event->isTerminated()) {
                    throw $event->getTerminationException();
                }
                $notification->send($event);
                $this->logger->info('Notification sent', [
                    'event' => $event::identifier(),
                    'notification' => [
                        'class' => $notification::class,
                        'uid' => $notification->getUid(),
                    ]
                ]);
            } catch (EventNotificationTerminatedException $e) {
                $this->logger->info('Notification terminated',[
                    'reason' => $e->getMessage(),
                    'event' => $event::identifier(),
                    'notification' => [
                        'class' => $notification::class,
                        'uid' => $notification->getUid(),
                    ]
                ]);
                continue;
            } catch (\Exception $e) {
                // @extensionScannerIgnoreLine
                $this->logger->error($e->getMessage(), [
                    'event' => $event::identifier(),
                    'notification' => [
                        'class' => $notification::class,
                        'uid' => $notification->getUid(),
                    ]
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
