<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(function ($extKey) {
    /**
     * Cache configuration
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extKey] ??= [];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extKey]['backend']
        ??= \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class;

    /**
     * Custom node registrations
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1721218749] = [
        'nodeName' => 'notifierMarkers',
        'priority' => 40,
        'class' => \Devsk\DsNotifier\Form\Element\NotifierMarkersElement::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Scheduler\Scheduler::class] = [
        'className' => \Devsk\DsNotifier\Xclass\SchedulerTaskCheck::class
    ];

    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][1721307957]
        = "EXT:{$extKey}/Resources/Private/Templates/Email";
    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths'][1721307957]
        = "EXT:{$extKey}/Resources/Private/Partials/Email";
    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths'][1721307957]
        = "EXT:{$extKey}/Resources/Private/Layouts/Email";

    if (ExtensionManagementUtility::isLoaded('form')) {
        ExtensionManagementUtility::addTypoScriptSetup("<![CDATA[
            plugin.tx_form {
                settings {
                    yamlConfigurations {
                            1730364095 = EXT:{$extKey}/Configuration/Form/Backend.yaml
                        }
                    }
                }
            }
            module.tx_form {
                settings {
                    yamlConfigurations {
                            1730364095 = EXT:{$extKey}/Configuration/Form/Backend.yaml
                        }
                    }
                }
            ]]>"
        );
    }
}, 'ds_notifier');
