<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\EventListener;

use Devsk\DsNotifier\Event\AbstractEvent;

final class NotifierListener
{

    public function __invoke(AbstractEvent $event)
    {
        //TODO: Implement notifier notification handling via https://symfony.com/doc/current/notifier.html
    }
}
