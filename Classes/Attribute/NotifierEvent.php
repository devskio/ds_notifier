<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Attribute;

use Attribute;
use Devsk\DsNotifier\Enum\EventGroup;
use Devsk\DsNotifier\Enum\EventGroupInterface;

#[Attribute(Attribute::TARGET_CLASS)]
class NotifierEvent
{

    public function __construct(
        protected string $label,
        protected ?EventGroupInterface $group = EventGroup::DEFAULT,
        protected ?string $flexibleConfigurationFile = null,
    )
    {}

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getGroup(): ?EventGroupInterface
    {
        return $this->group;
    }

    public function getFlexibleConfigurationFile(): ?string
    {
        return $this->flexibleConfigurationFile;
    }
}
