<?php

declare(strict_types=1);

use Devsk\DsNotifier\Domain\Model;

return [
    Model\Notification::class => [
        'subclasses' => [
            Model\Notification\Email::class => Model\Notification\Email::class,
            Model\Notification\Slack::class => Model\Notification\Slack::class,
        ],
    ],
    Model\Notification\Email::class => [
        'recordType' => \Devsk\DsNotifier\Enum\Channel::EMAIL->value,
        'properties' => [
            'to' => [
                'fieldName' => 'email_to',
            ],
            'cc' => [
                'fieldName' => 'email_cc',
            ],
            'bcc' => [
                'fieldName' => 'email_bcc',
            ],
        ],
    ],
    Model\Notification\Slack::class => [
        'recordType' => \Devsk\DsNotifier\Enum\Channel::SLACK->value,
    ],
    Model\Site::class => [
        'tableName' => 'pages',
    ],

];
