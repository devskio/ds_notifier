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

    public static function collectFormEmailRenderables(array $renderables): array
    {
        $emailRenderables = [];

        foreach ($renderables as $renderable) {
            if (isset($renderable['renderables'])) {
                $emailRenderables = array_merge(
                    $emailRenderables,
                    static::collectFormEmailRenderables($renderable['renderables'])
                );
            } elseif (isset($renderable['type'])
                && $renderable['type'] === 'Email'
            ) {
                $emailRenderables[] = $renderable;
            }
        }

        return $emailRenderables;
    }
}
