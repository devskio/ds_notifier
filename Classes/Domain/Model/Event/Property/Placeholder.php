<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Event\Property;

use Devsk\DsNotifier\Attribute\Event\Marker;
use ReflectionProperty;

/**
 * Class Marker
 * @package Devsk\DsNotifier\Domain\Model
 */
readonly class Placeholder
{

    public function __construct(
        protected Marker $markerAttribute,
        protected ReflectionProperty $property,
    )
    {
    }

    public function getPropertyName(): string
    {
        return $this->property->getName();
    }

    public function getPlaceholder(): string
    {
        return sprintf("{%s}", $this->getPropertyName());
    }

    public function getLabel(): string
    {
        return $this->markerAttribute->getLabel();
    }
}
