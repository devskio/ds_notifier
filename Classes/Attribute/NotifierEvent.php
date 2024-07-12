<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Attribute;

use Attribute;
use Devsk\DsNotifier\Enum\EventGroup;

#[Attribute(Attribute::TARGET_CLASS)]
class NotifierEvent
{

    public function __construct(
        protected string $identifier,
        protected string $label,
//        protected ?string $description = null, // TODO: Is Required?
        protected ?EventGroup $group = EventGroup::DEFAULT,
    )
    {}

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getGroup(): ?EventGroup
    {
        return $this->group;
    }
}
