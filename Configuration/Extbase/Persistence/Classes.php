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
        'recordType' => Model\Notification\Email::class,
        'tableName' => Model\Notification\Email::tableName(),
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
        'recordType' => Model\Notification\Slack::class,
        'tableName' => Model\Notification\Slack::tableName(),
    ],
    Model\Site::class => [
        'tableName' => Model\Site::tableName(),
    ],
    Model\Notification\Slack\Workspace::class => [
        'recordType' => Model\Notification\Slack\Workspace::class,
        'tableName' => 'tx_dsnotifier_domain_model_slack_workspace',
    ],

];
