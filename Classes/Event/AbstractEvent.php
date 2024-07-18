<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event;

use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Domain\Model\Event\Property;
use Devsk\DsNotifier\Domain\Model\Event\Property\Placeholder;
use Devsk\DsNotifier\Exception\NotifierException;
use ReflectionAttribute;
use ReflectionClass;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class AbstractEvent
 * @package Digitalwerk\DwIoeb\Notifier
 */
abstract class AbstractEvent implements EventInterface
{

    public static function modelName(): string
    {
        return static::getReflectionClass()->getShortName();
    }

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
     * @return Placeholder[]
     */
    public static function getMarkerPlaceholders(): array
    {
        $markers = [];
        foreach (static::getReflectionClass()->getProperties() as $property) {
            $marker = $property->getAttributes(Marker::class)[0] ?? null;
            if ($marker) {
                $markers[] = new Placeholder($marker->newInstance(), $property);
            }
        }

        return $markers;
    }

    /**
     * @return Property[]
     */
    public function getMarkerProperties(): array
    {
        $properties = [];


        foreach (static::getMarkerPlaceholders() as $placeholder) {
            if (ObjectAccess::isPropertyGettable($this, $placeholder->getPropertyName())) {
                $properties[$placeholder->getPropertyName()] = ObjectAccess::getProperty($this, $placeholder->getPropertyName());
            }
        }

        return $properties;
    }

    protected static function getReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }
}
