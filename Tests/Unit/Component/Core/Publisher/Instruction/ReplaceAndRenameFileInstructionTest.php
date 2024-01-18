<?php

declare(strict_types=1);

namespace In2code\In2publishCore\Tests\Unit\Component\Core\Publisher\Instruction;

use In2code\In2publishCore\Component\Core\DemandResolver\Filesystem\Service\FalDriverService;
use In2code\In2publishCore\Component\Core\Publisher\Instruction\ReplaceAndRenameFileInstruction;
use In2code\In2publishCore\Tests\UnitTestCase;
use TYPO3\CMS\Core\Resource\Driver\LocalDriver;

/**
 * @coversDefaultClass \In2code\In2publishCore\Component\Core\Publisher\Instruction\ReplaceAndRenameFileInstruction
 */
class ReplaceAndRenameFileInstructionTest extends UnitTestCase
{
    /**
     * @covers ::execute
     */
    public function testExecutionCreatesFolder(): void
    {
        $driver = $this->createMock(LocalDriver::class);
        $driver->expects($this->once())->method('folderExists')->with('someOtherFolder')->willReturn(true);
        $driver->expects($this->once())->method('moveFileWithinStorage')->with(
            'new_folder/oldFile.txt',
            'someOtherFolder',
            'newFileName.txt',
        );

        $falDriverService = $this->createMock(FalDriverService::class);
        $falDriverService->method('getDriver')->willReturn($driver);
        $instruction = new ReplaceAndRenameFileInstruction(1, 'new_folder/oldFile.txt', 'someOtherFolder/newFileName.txt', '/tmp/foo.txt');
        $instruction->execute($falDriverService);
    }
}
