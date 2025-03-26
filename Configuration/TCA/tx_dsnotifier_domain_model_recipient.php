<?php

$lll = 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf';

return [
    'ctrl' => [
        'label' => 'name',
        'label_alt' => 'email,slack_channel',
        'tstamp' => 'tstamp',
        'title' => "{$lll}:tx_dsnotifier_domain_model_recipient",
        'crdate' => 'crdate',
        'hideAtCopy' => true,
        'delete' => 'deleted',
        'default_sortby' => 'name',
        'enablecolumns' => [
            'disabled' => 'disable',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'type' => 'channel',
        'typeicon_column' => 'channel',
        'typeicon_classes' => [
            'default' => 'actions-envelope-open-text',
            \Devsk\DsNotifier\Domain\Model\Notification\Email::class => 'actions-envelope',
            \Devsk\DsNotifier\Domain\Model\Notification\Slack::class => 'actions-brand-slack',
        ],
        'searchFields' => 'name,email',
    ],
    'columns' => [
        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'disable' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'channel' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_recipient.channel",
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => '',
                        'value' => '',
                    ],
                    [
                        'label' => "{$lll}:tx_dsnotifier_domain_model_recipient.channel.email",
                        'value' => \Devsk\DsNotifier\Domain\Model\Notification\Email::class,
                        'icon' => 'content-elements-mailform'
                    ],
                   [
                        'label' => "{$lll}:tx_dsnotifier_domain_model_recipient.channel.slack",
                        'value' => \Devsk\DsNotifier\Domain\Model\Notification\Slack::class,
                        'icon' => 'actions-brand-slack'
                    ],
                ],
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'email' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_recipient.email",
            'config' => [
                'type' => 'email',
                'required' => true,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'name' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_recipient.name",
            'config' => [
                'type' => 'input',
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'slack_channel' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_recipient.slack_channel",
            'config' => [
                'required' => true,
                'type' => 'input',
                'size' => 255,
                'eval' => 'trim',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                '
        ],
        \Devsk\DsNotifier\Domain\Model\Notification\Email::class => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general, --palette--;;email,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                '
        ],
        \Devsk\DsNotifier\Domain\Model\Notification\Slack::class => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general, --palette--;;slack,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                '
        ],
    ],
    'palettes' => [
        'general' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_recipient.palette.general",
            'showitem' => 'channel'
        ],
        'email' => [
            'showitem' => 'email, name'
        ],
        'access' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access',
            'showitem' => 'disable,
                           --linebreak--, starttime, endtime'
        ],
        'slack' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.palette.slack",
            'showitem' => 'slack_channel,name'
        ],
    ],
];
