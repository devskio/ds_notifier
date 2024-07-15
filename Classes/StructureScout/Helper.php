<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\StructureScout;

use TYPO3\CMS\Core\Core\Environment;

/**
 * Class Helper
 * @package Devsk\DsNotifier\StructureScout
 */
class Helper
{

    public static function getDiscoveryDirectory(): string
    {
        return Environment::getProjectPath();
    }

    public static function getCacheDirectory(): string
    {
        return Environment::getVarPath() . '/cache/data/ds_notifier/';
    }
}
