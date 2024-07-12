<?php
$lll = 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf';

return [
    'ctrl' => [
        'label' => 'title',
        'tstamp' => 'tstamp',
        'title' => "{$lll}:tx_dsnotifier_domain_model_notification",
        'crdate' => 'crdate',
        'hideAtCopy' => true,
        'delete' => 'deleted',
        'default_sortby' => 'title',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'disable',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'type' => 'channel',
        'typeicon_column' => 'channel',
        'typeicon_classes' => [
            '0' => 'install-test-mail',
        ],
        'searchFields' => 'title',
    ],
    'columns' => [
        'title' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.title",
            'config' => [
                'type' => 'input',
                'required' => true,
                'max' => 255,
            ],
        ],
        'channel' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.channel",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => "{$lll}:tx_dsnotifier_domain_model_notification.channel.email",
                        'value' => \Devsk\DsNotifier\Enum\Channel::EMAIL->value,
                        'icon' => 'content-elements-mailform'
                    ]
                ],
            ],
        ],
        'event' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.event",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'itemsProcFunc' => Devsk\DsNotifier\UserFunction\FormEngine\Tca::class . '->notificationEventItemsProcFunc',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title, channel, event,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                '
        ],
    ],
    'palettes' => [
        'access' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access',
            'showitems' => 'disable,
                           --linebreak--, starttime, endtime'
        ],
    ],
];
