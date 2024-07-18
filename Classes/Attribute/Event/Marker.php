<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Attribute\Event;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Marker
{
    public function __construct(
        protected ?string $label = null
    ){}

    public function getLabel(): string
    {
        return $this->label;
    }
}
