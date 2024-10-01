<?php
declare(strict_types=1);
namespace Devsk\DsNotifier\Utility;

class NotifierUtility
{
    /**
     * Escape string for TCA
     * Example:
     *  Source: Devsk\DsNotifier\Domain\Model\Event
     *  Target: Devsk\\\\DsPageTypes\\\\Domain\\\\Model\\\\Event
     * @param string $fqcn
     * @return string
     */
    public static function escapeFQCNForTCA(string $fqcn): string
    {
        return '"' . \str_replace('\\', '\\\\', $fqcn) . '"';
    }
}
