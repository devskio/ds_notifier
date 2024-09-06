<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model;

use Devsk\DsNotifier\Domain\Model\Notification\FlexForm;
use Devsk\DsNotifier\Event\EventInterface;

interface NotificationInterface
{
    public function send(EventInterface $event): void;

    public function getConfiguration(): ?FlexForm;
}
