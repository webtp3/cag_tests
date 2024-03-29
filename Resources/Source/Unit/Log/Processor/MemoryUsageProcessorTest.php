<?php
namespace TYPO3\CMS\Core\Tests\Unit\Log\Processor;

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

/**
 * Test case
 */
class MemoryUsageProcessorTest extends \CAG\CagTests\Core\Unit\UnitTestCase
{
    /**
     * @test
     */
    public function memoryUsagePRocessorAddsMemoryUsageDataToLogRecord()
    {
        $logRecord = new \TYPO3\CMS\Core\Log\LogRecord('test.core.log', \TYPO3\CMS\Core\Log\LogLevel::DEBUG, 'test');
        $processor = new \TYPO3\CMS\Core\Log\Processor\MemoryUsageProcessor();
        $logRecord = $processor->processLogRecord($logRecord);
        $this->assertArrayHasKey('memoryUsage', $logRecord['data']);
    }
}
