<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\StructureScout;

use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Event\EventInterface;
use Spatie\StructureDiscoverer\Cache\DiscoverCacheDriver;
use Spatie\StructureDiscoverer\Cache\FileDiscoverCacheDriver;
use Spatie\StructureDiscoverer\Discover;
use Spatie\StructureDiscoverer\StructureScout;

/**
 * Class NotifierEventStructureScout
 * @package Devsk\DsNotifier\StructureScout
 */
class NotifierEventStructureScout extends StructureScout
{

    protected function definition(): Discover
    {
        return Discover::in(Helper::getDiscoveryDirectory())
            ->withAttribute(NotifierEvent::class)
            ->implementing(EventInterface::class);
    }

    public function cacheDriver(): DiscoverCacheDriver
    {
        return new FileDiscoverCacheDriver(Helper::getCacheDirectory());
    }
}
