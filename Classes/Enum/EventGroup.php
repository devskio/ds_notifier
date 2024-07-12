<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Enum;

/**
 * Class EventGroup
 * @package Digitalwerk\DwIoeb\Notifier
 */
enum EventGroup implements EventGroupInterface
{
    case DEFAULT;
    case TYPO3;

    public function getLabel(): string
    {
        return "LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.eventGroup.{$this->name}";
    }
}
