<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event\Notifier;

use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Enum\EventGroup;
use Devsk\DsNotifier\Event\AbstractEvent;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class CacheFlush
 */
#[NotifierEvent(
    label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Notifier\TaskFailure',
    group: EventGroup::NOTIFIER,
)]
class TaskFailure extends AbstractEvent
{

    #[Marker(label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Notifier\TaskFailure.marker.uid')]
    protected ?int $uid = 0;

    #[Marker(label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Notifier\TaskFailure.marker.name')]
    protected ?string $name = '';

    #[Marker(label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Notifier\TaskFailure.marker.time')]
    protected ?string $time = '';

    #[Marker(label: 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.event.Devsk\DsNotifier\Event\Notifier\TaskFailure.marker.error')]
    protected ?string $error = '';

    public function __construct(
        public readonly AbstractTask $task,
        public readonly string $errorMessage
    )
    {
        $this->uid = $task->getTaskUid();
        $this->name = $task->getTaskTitle();
        $this->time = date('Y-m-d H:i:s');
        $this->error = $errorMessage;
    }

    /**
     * @return int|null
     */
    public function getUid(): ?int
    {
        return $this->uid;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getTime(): ?string
    {
        return $this->time;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }


}
