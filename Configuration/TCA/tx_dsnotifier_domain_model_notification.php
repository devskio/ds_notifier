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
            'default' => 'content-elements-mailform',
            \Devsk\DsNotifier\Domain\Model\Notification\Email::class => 'content-elements-mailform',
        ],
        'searchFields' => 'title',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => '',
                        'value' => 0,
                    ],
                ],
                'foreign_table' => 'tx_dsnotifier_domain_model_notification',
                'foreign_table_where' => 'AND {#tx_dsnotifier_domain_model_notification}.{#pid}=###CURRENT_PID### AND {#tx_dsnotifier_domain_model_notification}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => '',
            ],
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
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
            'onChange' => 'reload',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => "{$lll}:tx_dsnotifier_domain_model_notification.channel.email",
                        'value' => \Devsk\DsNotifier\Domain\Model\Notification\Email::class,
                        'icon' => 'content-elements-mailform'
                    ],
                    [
                        'label' => "{$lll}:tx_dsnotifier_domain_model_notification.channel.slack",
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
        'event' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.event",
            'onChange' => 'reload',
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => '']
                ],
                'default' => '',
                'required' => true,
                'itemsProcFunc' => Devsk\DsNotifier\UserFunction\FormEngine\Tca::class . '->notificationEventItemsProcFunc',
            ],
        ],
        'sites' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.sites",
            'l10n_mode' => 'exclude',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'foreign_table' => 'pages',
                'foreign_table_where' => 'AND {#pages}.{#is_siteroot} = 1 AND {#pages}.{#sys_language_uid} = 0 ORDER BY sorting',
            ],
        ],
        'subject' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.subject",
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'required' => true,
                'max' => 255,
            ],
        ],
        'body' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.body",
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
                'required' => true,
                'rows' => 10,
            ],
        ],
        'markers' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.markers",
            'config' => [
                'type' => 'user',
                'renderType' => 'notifierMarkers',
                'templatePath' => \Devsk\DsNotifier\Form\Element\NotifierMarkersElement::DEFAULT_TEMPLATE_PATH,
            ],
        ],
        'layout' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.layout",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => "{$lll}:tx_dsnotifier_domain_model_notification.layout.default",
                        'value' => '',
                    ]
                ],
            ],
        ],
        'configuration' => array_merge_recursive(
            ['label' => "{$lll}:tx_dsnotifier_domain_model_notification.configuration"],
            \Devsk\DsNotifier\UserFunction\FormEngine\Tca::flexFormTcaConfiguration(),
        ),
        'email_to' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.email_to",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_dsnotifier_domain_model_recipient',
                'foreign_table_where' => 'AND {#tx_dsnotifier_domain_model_recipient}.{#pid} IN (###CURRENT_PID###, ###SITEROOT###, ###SITE:settings.ds_notifier.recipients.storagePid###)
                                          AND {#tx_dsnotifier_domain_model_recipient}.{#channel} = ' . \Devsk\DsBoilerplate\Utility\BoilerplateUtility::escapeFQCNForTCA(Devsk\DsNotifier\Domain\Model\Notification\Email::class),
                'foreign_table_item_group' => 'channel',
                'allowNonIdValues' => true,
                'minitems' => 1,
                'itemsProcFunc' => Devsk\DsNotifier\UserFunction\FormEngine\Tca::class . '->notificationEmailItemsProcFunc',
                'itemGroups' => [
                    'event' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.event",
                    Devsk\DsNotifier\Domain\Model\Notification\Email::class => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.db",
                    'site' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.site",
                ],
            ],
        ],
        'email_cc' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.email_cc",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_dsnotifier_domain_model_recipient',
                'foreign_table_where' => 'AND {#tx_dsnotifier_domain_model_recipient}.{#pid} IN (###CURRENT_PID###, ###SITEROOT###, ###SITE:settings.ds_notifier.recipients.storagePid###)
                                          AND {#tx_dsnotifier_domain_model_recipient}.{#channel} = ' . \Devsk\DsBoilerplate\Utility\BoilerplateUtility::escapeFQCNForTCA(Devsk\DsNotifier\Domain\Model\Notification\Email::class),
                'foreign_table_item_group' => 'channel',
                'allowNonIdValues' => true,
                'itemsProcFunc' => Devsk\DsNotifier\UserFunction\FormEngine\Tca::class . '->notificationEmailItemsProcFunc',
                'itemGroups' => [
                    'event' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.event",
                    Devsk\DsNotifier\Domain\Model\Notification\Email::class => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.db",
                    'site' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.site",
                ],
            ],
        ],
        'email_bcc' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.email_bcc",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_dsnotifier_domain_model_recipient',
                'foreign_table_where' => 'AND {#tx_dsnotifier_domain_model_recipient}.{#pid} IN (###CURRENT_PID###, ###SITEROOT###, ###SITE:settings.ds_notifier.recipients.storagePid###)
                                          AND {#tx_dsnotifier_domain_model_recipient}.{#channel} = ' . \Devsk\DsBoilerplate\Utility\BoilerplateUtility::escapeFQCNForTCA(Devsk\DsNotifier\Domain\Model\Notification\Email::class),
                'foreign_table_item_group' => 'channel',
                'allowNonIdValues' => true,
                'itemsProcFunc' => Devsk\DsNotifier\UserFunction\FormEngine\Tca::class . '->notificationEmailItemsProcFunc',
                'itemGroups' => [
                    'event' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.event",
                    Devsk\DsNotifier\Domain\Model\Notification\Email::class => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.db",
                    'site' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.site",
                ],
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
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                '
        ],
        \Devsk\DsNotifier\Domain\Model\Notification\Email::class => [
            'showitem' => "
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                --div--;{$lll}:tx_dsnotifier_domain_model_notification.tab.email,
                    --palette--;;email,
                --div--;{$lll}:tx_dsnotifier_domain_model_notification.tab.configuration,
                    --palette--;;configuration,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                ",
            'subtype_value_field' => 'event'
        ],
    ],
    'palettes' => [
        'general' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.palette.general",
            'showitem' => 'title,
                          --linebreak--, channel,
                          --linebreak--, event,
                          --linebreak--, sites
                          '
        ],
        'configuration' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.palette.configuration",
            'showitem' => 'configuration'
        ],
        'email' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.palette.email",
            'showitem' => 'subject, layout,
                          --linebreak--, body, markers,
                          --linebreak--, email_to,
                          --linebreak--, email_cc,
                          --linebreak--, email_bcc
                          '
        ],
        'access' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access',
            'showitem' => 'disable,
                           --linebreak--, starttime, endtime'
        ],
        'language' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language',
            'showitem' => 'sys_language_uid, l10n_parent',
        ],
    ],
];
