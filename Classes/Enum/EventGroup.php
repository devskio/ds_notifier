<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Enum;

/**
 * Class EventGroup
 * @package Digitalwerk\DwIoeb\Notifier
 */
enum EventGroup
{
    case DEFAULT;
    case TYPO3;

    public function getLabel(): string
    {
        return $this->name;
    }
}
