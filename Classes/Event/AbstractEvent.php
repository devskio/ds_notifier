<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event;

use Devsk\DsNotifier\Attribute\Event\Email;
use Devsk\DsNotifier\Attribute\Event\Marker;
use Devsk\DsNotifier\Attribute\NotifierEvent;
use Devsk\DsNotifier\Domain\Model\Event\Property;
use Devsk\DsNotifier\Domain\Model\Event\Property\Placeholder;
use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Exception\EventCancelledException;
use Devsk\DsNotifier\Exception\NotifierException;
use ReflectionAttribute;
use ReflectionClass;
use TYPO3\CMS\Core\Site\Entity\NullSite;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class AbstractEvent
 * @package Digitalwerk\DwIoeb\Notifier
 */
abstract class AbstractEvent implements EventInterface
{

    protected ?bool $cancelled = false;
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

    public static function getEmailProperties():array
    {
        $emails = [];
        foreach (static::getReflectionClass()->getProperties() as $property) {
            $email = $property->getAttributes(Email::class)[0] ?? null;
            if ($email) {
                $emails[] = [
                    'label' => $email->newInstance()->getLabel(),
                    'name' => $property->getName(),
                    'value' => sprintf("{%s}", $property->getName())
                ];
            }
        }

        return $emails;
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

    /**
     * @throws EventCancelledException
     */
    public function checkIfCancelled(Notification $notification): void
    {
        if ($notification->getSites()->count() > 0) {
            $request = $GLOBALS['TYPO3_REQUEST'];
            if ($request) {
                $site = $request->getAttribute('site');
                if ($site instanceof NullSite) {
                    $this->cancelled = true;
                }
                foreach ($notification->getSites() as $notificationSite) {
                    if ($site->getRootPageId() === $notificationSite->getUid()) {
                        $this->cancelled = false;
                        break;
                    } else {
                        $this->cancelled = true;
                    }
                }
            } else {
                $this->cancelled = true;
            }
        }
        if ($this->cancelled) {
            throw new EventCancelledException();
        }
    }
}
