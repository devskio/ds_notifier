<?php

declare(strict_types=1);

defined('TYPO3') or die();

call_user_func(function (string $extKey): void {
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
        'className' => \Devsk\DsNotifier\Xclass\SchedulerTaskCheck::class,
    ];

    /**
     * Register Form Data Provider to inject dynamic flex form DS before TcaFlexPrepare
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']
        [\Devsk\DsNotifier\Form\FormDataProvider\NotifierFlexFormDataProvider::class] = [
            'before' => [\TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexPrepare::class],
        ];

    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][1721307957]
        = "EXT:{$extKey}/Resources/Private/Templates/Email";
    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths'][1721307957]
        = "EXT:{$extKey}/Resources/Private/Partials/Email";
    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths'][1721307957]
        = "EXT:{$extKey}/Resources/Private/Layouts/Email";

}, 'ds_notifier');
