<?php
declare(strict_types=1);
namespace In2code\In2publishCore\Command\Status;

/*
 * Copyright notice
 *
 * (c) 2019 in2code.de and the following authors:
 * Oliver Eglseder <oliver.eglseder@in2code.de>
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 */

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Console\CommandRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AllCommand extends Command
{
    public const IDENTIFIER = 'in2publish_core:status:all';

    protected function configure()
    {
        $this->setDescription('Prints the configured fileCreateMask and folderCreateMask')
             ->setHidden(true);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandRegistry = GeneralUtility::makeInstance(CommandRegistry::class);
        $commandRegistry->getCommandByIdentifier(VersionCommand::IDENTIFIER)->execute($input, $output);
        $commandRegistry->getCommandByIdentifier(CreateMasksCommand::IDENTIFIER)->execute($input, $output);
        $commandRegistry->getCommandByIdentifier(GlobalConfigurationCommand::IDENTIFIER)->execute($input, $output);
        $commandRegistry->getCommandByIdentifier(Typo3VersionCommand::IDENTIFIER)->execute($input, $output);
        $commandRegistry->getCommandByIdentifier(DbInitQueryEncodedCommand::IDENTIFIER)->execute($input, $output);
        $commandRegistry->getCommandByIdentifier(ShortSiteConfigurationCommand::IDENTIFIER)->execute($input, $output);
        $commandRegistry->getCommandByIdentifier(DbConfigTestCommand::IDENTIFIER)->execute($input, $output);
    }
}