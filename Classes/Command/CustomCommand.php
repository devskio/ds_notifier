<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Devsk\DsNotifier\Command;

use Devsk\DsNotifier\Event\Custom\CustomEvent;
use Devsk\DsNotifier\Event\Typo3\Core\Cache\CacheFlush;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Testing command for notifier
 *
 */
class CustomCommand extends Command
{
    /**
     * Defines the allowed options for this command
     */
    protected function configure()
    {
        $this
            ->addOption('cancelled', null, InputOption::VALUE_REQUIRED, 'Should event be cancelled?')
            ->setAliases(['notifier:custom']);
    }

    /**
     * Executes the mailer command
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $input->getOption('cancelled');

        $eventDispatcher = GeneralUtility::makeInstance(EventDispatcher::class);
        $eventDispatcher->dispatch(
            new CustomEvent(
                'emailtest@email.com',
                '',
                'Test Event',
                boolval($input->getOption('cancelled'))
            ),
        );

        return Command::SUCCESS;
    }
}
