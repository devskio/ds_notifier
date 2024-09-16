<?php
declare(strict_types=1);

defined('TYPO3') or die();

call_user_func(static function ($extKey, $lll) {

    $tca = [
        'ctrl' => [
            'typeicon_classes' => [
                'contains-ds_notifier-notification' => 'content-elements-mailform',
                'contains-ds_notifier-recipient' => 'actions-envelope',
            ],
        ],
        'columns' => [
            'module' => [
                'config' => [
                    'items' => array_merge($GLOBALS['TCA']['pages']['columns']['module']['config']['items'], [
                        [
                            'label' => "{$lll}:pages.module.ds_notifier-notification",
                            'value' => 'ds_notifier-notification',
                            'icon' => 'content-elements-mailform',
                        ],
                        [
                            'label' => "{$lll}:pages.module.ds_notifier-recipient",
                            'value' => 'ds_notifier-recipient',
                            'icon' => 'actions-envelope',
                        ],
                    ]),
                ],
            ],
        ],
    ];

    $GLOBALS['TCA']['pages'] = array_replace_recursive($GLOBALS['TCA']['pages'], $tca);

}, 'ds_notifier', 'LLL:EXT:ds_notifier/Resources/Private/Language/locallang_db.xlf');
