<?php

declare(strict_types=1);

namespace In2code\In2publishCore\Domain\Service\Processor;

/*
 * Copyright notice
 *
 * (c) 2016 in2code.de and the following authors:
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

class SelectProcessor extends AbstractProcessor
{
    /**
     * @var bool
     */
    protected $canHoldRelations = true;

    public const ALLOW_NON_ID_VALUES = 'allowNonIdValues';
    public const FILE_FOLDER = 'fileFolder';
    public const SPECIAL = 'special';

    /**
     * @var array
     */
    protected $forbidden = [
        'itemsProcFunc is not supported' => self::ITEMS_PROC_FUNC,
        'fileFolder is not supported' => self::FILE_FOLDER,
        'allowNonIdValues can not be resolved by in2publish' => self::ALLOW_NON_ID_VALUES,
        'MM_oppositeUsage is not supported' => self::MM_OPPOSITE_USAGE,
        'MM_opposite_field is set on the foreign side of relations, which must not be resolved' => self::MM_OPPOSITE_FIELD,
        'special is not supported' => self::SPECIAL,
    ];

    /**
     * @var array
     */
    protected $required = [
        'Can not select without another table' => self::FOREIGN_TABLE,
    ];

    /**
     * @var array
     */
    protected $allowed = [
        self::FOREIGN_TABLE_WHERE,
        self::MM,
        self::MM_HAS_UID_FIELD,
        self::MM_MATCH_FIELDS,
        self::MM_TABLE_WHERE,
        self::ROOT_LEVEL,
    ];

    /**
     * Override: Detects and allows owning side relations to categories
     *
     * {@inheritDoc}
     */
    public function canPreProcess(array $config): bool
    {
        if ($this->isSysCategoryField($config)) {
            // Workaround for categories having `MM_opposite_field` set on both sides of the relation
            return true;
        }
        return parent::canPreProcess($config);
    }

    /**
     * Override: Adds `MM_opposite_field` to the preprocessed config for sys_category relations
     *
     * {@inheritDoc}
     */
    public function preProcess(array $config): array
    {
        $processed = parent::preProcess($config);
        if ($this->isSysCategoryField($config)) {
            /* @see \In2code\In2publishCore\Domain\Repository\CommonRepository::getLocalField */
            $processed['MM_opposite_field'] = $config['MM_opposite_field'];
        }
        return $processed;
    }

    /**
     * Determines if this field is the owning side of a sys_category relation. These relations are automatically
     * generated by `\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable` and therefore distinctive.
     *
     * @param array $config
     *
     * @return bool
     */
    protected function isSysCategoryField(array $config): bool
    {
        return isset($config['foreign_table'], $config['MM_opposite_field'], $config['MM'])
               && 'sys_category' === $config['foreign_table']
               && 'items' === $config['MM_opposite_field']
               && 'sys_category_record_mm' === $config['MM'];
    }
}
