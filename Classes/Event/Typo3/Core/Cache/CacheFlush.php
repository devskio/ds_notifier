<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event\Typo3\Core\Cache;

use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Enum\EventGroup;
use Devsk\DsNotifier\Event\AbstractEvent;
use TYPO3\CMS\Core\Cache\Event\CacheFlushEvent;

/**
 * Class TaskExecuted
 */
#[NotifierEvent(
    identifier: self::class,
    label: 'TYPO3: Cache flush',
    group: EventGroup::TYPO3,
)]
class CacheFlush extends AbstractEvent
{

    public function __construct(
        public readonly CacheFlushEvent $cacheFlushEvent
    )
    {
    }
}
