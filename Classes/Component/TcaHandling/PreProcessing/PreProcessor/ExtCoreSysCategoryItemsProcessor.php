<?php

declare(strict_types=1);

namespace In2code\In2publishCore\Component\TcaHandling\PreProcessing\PreProcessor;

use In2code\In2publishCore\Component\TcaHandling\PreProcessing\ProcessingResult;
use In2code\In2publishCore\Component\TcaHandling\Resolver\NoOpResolver;
use In2code\In2publishCore\Component\TcaHandling\Resolver\Resolver;

class ExtCoreSysCategoryItemsProcessor extends AbstractProcessor
{
    protected NoOpResolver $noOpResolver;
    protected string $type = 'group';

    public function injectNoOpResolver(NoOpResolver $noOpResolver): void
    {
        $this->noOpResolver = $noOpResolver;
    }

    public function getTable(): string
    {
        return 'sys_category';
    }

    public function getColumn(): string
    {
        return 'items';
    }

    public function process(string $table, string $column, array $tca): ProcessingResult
    {
        return new ProcessingResult(
            ProcessingResult::INCOMPATIBLE,
            'The field items is configured as owning side but actually is the foreign side. Relations will be resolved from the records which are categorized.'
        );
    }

    protected function buildResolver(string $table, string $column, array $processedTca): Resolver
    {
        return clone $this->noOpResolver;
    }
}
