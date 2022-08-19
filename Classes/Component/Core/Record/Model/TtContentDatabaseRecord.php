<?php

declare(strict_types=1);

namespace In2code\In2publishCore\Component\Core\Record\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_unique;
use function strrpos;
use function substr;

class TtContentDatabaseRecord extends DatabaseRecord
{
    public function calculateDependencies(): array
    {
        $dependencies = parent::calculateDependencies();

        $referencedRecords = '';
        if (($this->localProps['CType'] ?? null) === 'shortcut') {
            $referencedRecords .= $this->localProps['records'];
        }
        if (($this->foreignProps['CType'] ?? null) === 'shortcut') {
            $referencedRecords .= ',' . $this->foreignProps['records'];
        }
        $resolveShortcutDependencies = $this->resolveShortcutDependencies($referencedRecords);
        foreach ($resolveShortcutDependencies as $dependency) {
            $dependencies[] = $dependency;
        }

        return $dependencies;
    }

    /**
     * @return array<Dependency>
     */
    protected function resolveShortcutDependencies(string $recordList): array
    {
        $dependencies = [];
        $records = array_unique(GeneralUtility::trimExplode(',', $recordList, true));
        foreach ($records as $record) {
            $position = strrpos($record, '_');
            if (false === $position) {
                continue;
            }
            $table = substr($record, 0, $position);
            $id = substr($record, $position + 1);

            $dependencies[] = new Dependency(
                $this,
                $table,
                ['uid' => $id],
                Dependency::REQ_FULL_PUBLISHED,
                'LLL:EXT:in2publish_core/Resources/Private/Language/locallang.xlf:record.reason.shortcut_record',
                fn(Record $record): array => [
                    $record->__toString() ?: "{$record->getClassification()} [{$record->getId()}]",
                    $this->__toString() ?: "{$this->getClassification()} [{$this->getId()}]",
                ]
            );
        }

        return $dependencies;
    }
}
