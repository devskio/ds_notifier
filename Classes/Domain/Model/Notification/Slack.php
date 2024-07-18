<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Event\EventInterface;

/**
 * Class Slack
 * @package Devsk\DsNotifier\Domain\Model
 */
class Slack extends Notification
{
    public function send(EventInterface $event): void
    {
        // TODO: Implement send() method.
    }
}
