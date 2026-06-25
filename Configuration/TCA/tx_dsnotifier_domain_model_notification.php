<?php

use Devsk\DsNotifier\Utility\NotifierUtility;

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
            \Devsk\DsNotifier\Domain\Model\Notification\Slack::class => 'actions-brand-slack',
            \Devsk\DsNotifier\Domain\Model\Notification\Discord::class => 'actions-brand-discord',
        ],
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
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.transOrigPointerField',
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
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.field.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'searchable' => false,
            ],
        ],
        'endtime' => [
            'exclude' => false,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.field.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'searchable' => false,
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
                    [
                        'label' => "{$lll}:tx_dsnotifier_domain_model_notification.channel.discord",
                        'value' => \Devsk\DsNotifier\Domain\Model\Notification\Discord::class,
                        'icon' => 'actions-brand-discord'
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
                'required' => true,
                'max' => 255,
                'searchable' => false,
            ],
        ],
        'body' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.body",
            'config' => [
                'type' => 'text',
                'required' => true,
                'rows' => 14,
                'searchable' => false,
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
        'configuration' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.configuration",
            'config' => [
                'type' => 'flex',
                'ds_pointerField' => 'event',
                'ds' => [
                    'default' => 'FILE:EXT:ds_notifier/Configuration/FlexForm/Event/Default.xml',
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'searchable' => false,
            ],
        ],
        'email_to' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.email_to",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_dsnotifier_domain_model_recipient',
                'foreign_table_where' => 'AND {#tx_dsnotifier_domain_model_recipient}.{#pid} IN (###CURRENT_PID###, ###SITEROOT###, ###SITE:settings.ds_notifier.recipients.storagePid###)
                                          AND {#tx_dsnotifier_domain_model_recipient}.{#channel} = ' . NotifierUtility::escapeFQCNForTCA(Devsk\DsNotifier\Domain\Model\Notification\Email::class),
                'foreign_table_item_group' => 'channel',
                'allowNonIdValues' => true,
                'minitems' => 1,
                'itemsProcFunc' => Devsk\DsNotifier\UserFunction\FormEngine\Tca::class . '->notificationEmailItemsProcFunc',
                'itemGroups' => [
                    'event' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.event",
                    Devsk\DsNotifier\Domain\Model\Notification\Email::class => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.db",
                    'site' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.site",
                    'form' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.form",
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
                                          AND {#tx_dsnotifier_domain_model_recipient}.{#channel} = ' . NotifierUtility::escapeFQCNForTCA(Devsk\DsNotifier\Domain\Model\Notification\Email::class),
                'foreign_table_item_group' => 'channel',
                'allowNonIdValues' => true,
                'itemsProcFunc' => Devsk\DsNotifier\UserFunction\FormEngine\Tca::class . '->notificationEmailItemsProcFunc',
                'itemGroups' => [
                    'event' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.event",
                    Devsk\DsNotifier\Domain\Model\Notification\Email::class => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.db",
                    'site' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.site",
                    'form' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.form",
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
                                          AND {#tx_dsnotifier_domain_model_recipient}.{#channel} = ' . NotifierUtility::escapeFQCNForTCA(Devsk\DsNotifier\Domain\Model\Notification\Email::class),
                'foreign_table_item_group' => 'channel',
                'allowNonIdValues' => true,
                'itemsProcFunc' => Devsk\DsNotifier\UserFunction\FormEngine\Tca::class . '->notificationEmailItemsProcFunc',
                'itemGroups' => [
                    'event' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.event",
                    Devsk\DsNotifier\Domain\Model\Notification\Email::class => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.db",
                    'site' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.site",
                    'form' => "{$lll}:tx_dsnotifier_domain_model_notification.email.itemGroup.form",
                ],
            ],
        ],
        'slack_channels' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.slack_channels",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_dsnotifier_domain_model_recipient',
                'foreign_table_where' => 'AND {#tx_dsnotifier_domain_model_recipient}.{#pid} IN (###CURRENT_PID###, ###SITEROOT###, ###SITE:settings.ds_notifier.recipients.storagePid###)
                                          AND {#tx_dsnotifier_domain_model_recipient}.{#channel} = ' . NotifierUtility::escapeFQCNForTCA(Devsk\DsNotifier\Domain\Model\Notification\Slack::class),
                'foreign_table_item_group' => 'channel',
                'allowNonIdValues' => true,
            ],
        ],
        'discord_channels' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.discord_channels",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_dsnotifier_domain_model_recipient',
                'foreign_table_where' => 'AND {#tx_dsnotifier_domain_model_recipient}.{#pid} IN (###CURRENT_PID###, ###SITEROOT###, ###SITE:settings.ds_notifier.recipients.storagePid###)
                                          AND {#tx_dsnotifier_domain_model_recipient}.{#channel} = ' . NotifierUtility::escapeFQCNForTCA(Devsk\DsNotifier\Domain\Model\Notification\Discord::class),
                'foreign_table_item_group' => 'channel',
                'allowNonIdValues' => true,
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    --palette--;;general,
                --div--;core.form.tabs:access,
                    --palette--;;access,
                --div--;core.form.tabs:language,
                    --palette--;;language,
                --div--;core.form.tabs:extended,
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
            // 'subtype_value_field' => 'event',
            'columnsOverrides' => [
                'body' => [
                    'config' => [
                        'renderType' => 'codeEditor',
                        'format' => 'html',
                    ],
                ],
            ],
        ],
        \Devsk\DsNotifier\Domain\Model\Notification\Slack::class => [
            'showitem' => "
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                --div--;{$lll}:tx_dsnotifier_domain_model_notification.tab.slack,
                    --palette--;;slack,
                --div--;LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.tab.configuration,
                    --palette--;;configuration,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                ",
            'columnsOverrides' => [
                'body' => [
                    'config' => [
                        'renderType' => 'codeEditor',
                        'format' => 'html',
                    ],
                ],
            ],
        ],
        \Devsk\DsNotifier\Domain\Model\Notification\Discord::class => [
            'showitem' => "
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                --div--;{$lll}:tx_dsnotifier_domain_model_notification.tab.discord,
                    --palette--;;discord,
                --div--;LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf:tx_dsnotifier_domain_model_notification.tab.configuration,
                    --palette--;;configuration,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                ",
            'columnsOverrides' => [
                'body' => [
                    'config' => [
                        'renderType' => 'codeEditor',
                        'format' => 'html',
                    ],
                ],
            ],
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
        'slack' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.palette.slack",
            'showitem' => 'body, markers,
                --linebreak--, slack_channels',
        ],
        'discord' => [
            'label' => "{$lll}:tx_dsnotifier_domain_model_notification.palette.discord",
            'showitem' => 'body, markers,
                --linebreak--, discord_channels',
        ],
    ],
];
