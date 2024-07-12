<?php

declare(strict_types=1);

defined('TYPO3') or die();

call_user_func(function ($extKey) {

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extKey] ??= [];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$extKey]['backend']
        ??= \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class;

}, 'ds_notifier');
