<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\EventListener\Typo3\Core\Cache;

use Devsk\DsNotifier\StructureScout\Helper;
use Psr\Log\LoggerInterface;
use Spatie\StructureDiscoverer\Support\StructureScoutManager;
use TYPO3\CMS\Core\Cache\Event\CacheWarmupEvent;

final class CacheWarmupListener
{

    public function __construct(
        protected readonly LoggerInterface $logger
    ){}

    public function __invoke(CacheWarmupEvent $event)
    {
        $this->logger->info('Caching structure scouts...');

        $cached = StructureScoutManager::cache([
            Helper::getDiscoveryDirectory()
        ]);

        collect($cached)
            ->each(fn (string $identifier) => $this->logger->info($identifier));

        $this->logger->info('Caching structure scouts done');
    }
}
