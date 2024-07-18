<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event;

use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Exception\NotifierException;
use ReflectionAttribute;
use ReflectionClass;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use function sprintf;
use function stat;

/**
 * Class AbstractEvent
 * @package Digitalwerk\DwIoeb\Notifier
 */
abstract class AbstractEvent implements EventInterface
{

    public static function identifier(): string
    {
        return static::class;
    }

    public static function getNotifierEventAttribute(): NotifierEvent
    {
        $attributes = static::getReflectionClass()
            ->getAttributes(NotifierEvent::class);

        if ($attributes[0] instanceof ReflectionAttribute) {
            return $attributes[0]->newInstance();
        }

        throw new NotifierException(
            sprintf("Event %s has no %s attribute defined", static::class, NotifierEvent::class),
            1721219875
        );
    }

    /**
     * @return Marker[]
     */
    public static function getMarkers(): array
    {
        $markers = [];
        foreach (static::getReflectionClass()->getProperties() as $property) {
            $marker = $property->getAttributes(Marker::class)[0] ?? null;
            if ($marker) {
                $markers[] = new \Devsk\DsNotifier\Domain\Model\Marker(
                    $marker->newInstance(),
                    $property
                );
            }
        }

        return $markers;
    }

    protected static function getReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }
}
