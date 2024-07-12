<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\StructureScout;

use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Event\EventInterface;
use Spatie\StructureDiscoverer\Cache\DiscoverCacheDriver;
use Spatie\StructureDiscoverer\Cache\FileDiscoverCacheDriver;
use Spatie\StructureDiscoverer\Discover;
use Spatie\StructureDiscoverer\StructureScout;
use TYPO3\CMS\Core\Core\Environment;

/**
 * Class NotifierEventStructureScout
 * @package Devsk\DsNotifier\StructureScout
 */
class NotifierEventStructureScout extends StructureScout
{

    protected function definition(): Discover
    {
        return Discover::in(Environment::getProjectPath())
            ->withAttribute(NotifierEvent::class)
            ->implementing(EventInterface::class);
    }

    public function cacheDriver(): DiscoverCacheDriver
    {
        return new FileDiscoverCacheDriver(Environment::getVarPath() . '/cache/data/ds_notifier/');
    }
}
