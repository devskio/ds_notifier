<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\EventListener\Typo3\Core\Cache;

use Devsk\DsNotifier\Event\Typo3\Core\Cache\CacheFlush;
use TYPO3\CMS\Core\Cache\Event\CacheFlushEvent;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;

final class CacheFlushListener
{

    public function __construct(
        protected readonly EventDispatcher $eventDispatcher,
    ){}

    public function __invoke(CacheFlushEvent $event)
    {
        $this->eventDispatcher->dispatch(new CacheFlush($event));
    }
}
