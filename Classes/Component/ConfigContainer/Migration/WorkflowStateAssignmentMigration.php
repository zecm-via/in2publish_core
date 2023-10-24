<?php

declare(strict_types=1);

namespace In2code\In2publishCore\Component\ConfigContainer\Migration;

/*
 * Copyright notice
 *
 * (c) 2023 in2code.de and the following authors:
 * Oliver Eglseder <oliver.eglseder@in2code.de>
 * Christine Zoglmeier <christine.zoglmeier@in2code.de>
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

class WorkflowStateAssignmentMigration extends AbstractMigration
{
    protected const MIGRATION_MESSAGE = 'The configuration path "workflow.states.assignment" has been changed to "features.workflowStateAssignment". Please update your settings. Your settings have been migrated on the fly.';

    public function migrate(array $config): array
    {
        if (isset($config['workflow']['states']['assignment'])) {
            // Migrate the configuration
            $config['features']['workflowStateAssignment'] = $config['workflow']['states']['assignment'];
            unset($config['workflow']['states']['assignment']);

            // Inform the user about the migration
            $this->addMessage(self::MIGRATION_MESSAGE);
        }
        return $config;
    }
}