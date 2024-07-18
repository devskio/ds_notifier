<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model;

use Reflection;
use ReflectionProperty;
use function sprintf;

/**
 * Class Marker
 * @package Devsk\DsNotifier\Domain\Model
 */
class Marker
{

    public function __construct(
        protected readonly \Devsk\DsNotifier\Attribute\Event\Marker $markerAttribute,
        protected readonly ReflectionProperty $property
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
