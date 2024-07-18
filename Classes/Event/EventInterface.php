<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Event;

use Devsk\DsNotifier\Attribute\NotifierEvent;

interface EventInterface
{

    public static function identifier(): string;

    public static function getNotifierEventAttribute(): NotifierEvent;

    public static function getMarkerPlaceholders(): array;
}
