<?php
$lll = 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf';

return [
    'ctrl' => [
        'label' => 'name',
        'label_alt' => 'webhook',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'title' => "{$lll}:tx_dsnotifier_domain_model_slack_workspace",
        'crdate' => 'crdate',
        'hideAtCopy' => true,
        'delete' => 'deleted',
        'default_sortby' => 'name',
        'enablecolumns' => [
            'disabled' => 'disable',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'type' => '0',
        'typeicon_classes' => [
            'default' => 'actions-brand-slack',
        ],
        'searchFields' => 'name',
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
        'webhook' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_slack_workspace.webhook",
            'config' => [
                'type' => 'input',
                'required' => true,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'name' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_slack_workspace.name",
            'config' => [
                'type' => 'input',
                'max' => 255,
                'eval' => 'trim',
            ],
        ],

    ],
    'types' => [
        '0' => [
            'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                           --palette--;;general,
                           --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                           --palette--;;access',
        ],
    ],
    'palettes' => [
        'general' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_slack_workspace.palette.general",
            'showitem' => 'name, webhook'
        ],
        'access' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access',
            'showitem' => 'disable,
                           --linebreak--, starttime, endtime'
        ],
    ],
];
