<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event;

use Devsk\DsNotifier\Attribute\Event\Email;
use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Domain\Model\Event\Property;
use Devsk\DsNotifier\Domain\Model\Event\Property\Placeholder;
use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Exception\EventNotificationTerminatedException;
use Devsk\DsNotifier\Exception\NotifierException;
use ReflectionAttribute;
use ReflectionClass;
use Stringable;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class AbstractEvent
 * @package Digitalwerk\DwIoeb\Notifier
 */
abstract class AbstractEvent implements EventInterface, Stringable
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
    private static function getPlaceholders(string $placeholderType = null): array
    {
        $placeholders = [];
        foreach (static::getReflectionClass()->getProperties() as $property) {
            $placeholder = $property->getAttributes($placeholderType)[0] ?? null;
            if ($placeholder) {
                $placeholders[] = new Placeholder($placeholder->newInstance(), $property);
            }
        }

        return $placeholders;
    }

    /**
     * @return Placeholder[]
     */
    public static function getMarkerPlaceholders(): array
    {
        return static::getPlaceholders(Marker::class);
    }

    /**
     * @return Placeholder[]
     */
    public static function getEmailPlaceholders(): array
    {
        return static::getPlaceholders(Email::class);
    }

    /**
     * @param Placeholder[] $placeholders
     * @return array
     * @throws \TYPO3\CMS\Extbase\Reflection\Exception\PropertyNotAccessibleException
     */
    private function getProperties(array $placeholders): array
    {
        $properties = [];

        foreach ($placeholders as $placeholder) {
            if ($placeholder instanceof Placeholder
                && ObjectAccess::isPropertyGettable($this, $placeholder->getPropertyName())
            ) {
                $properties[$placeholder->getPropertyName()] = ObjectAccess::getProperty($this, $placeholder->getPropertyName());
            }
        }

        return $properties;
    }

    /**
     * @return Property[]
     */
    public function getMarkerProperties(): array
    {
        return $this->getProperties(static::getMarkerPlaceholders());
    }

    /**
     * @return Property[]
     */
    public function getEmailProperties(): array
    {
        return $this->getProperties(static::getEmailPlaceholders());
    }

    protected static function getReflectionClass(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }

    public function terminateEventNotification(string $reason = ''): void
    {
        throw new EventNotificationTerminatedException($reason, 1725633639);
    }

    public function applyNotificationConfiguration(?Notification\FlexibleConfiguration $configuration): void
    {
    }

    public function __toString(): string
    {
        return static::class;
    }
}
