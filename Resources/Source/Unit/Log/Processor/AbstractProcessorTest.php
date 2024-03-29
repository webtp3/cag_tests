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
use TYPO3\CMS\Core\Log\Exception\InvalidLogProcessorConfigurationException;
use TYPO3\CMS\Core\Tests\Unit\Log\Fixtures\ProcessorFixture;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case
 */
class AbstractProcessorTest extends \CAG\CagTests\Core\Unit\UnitTestCase
{
    /**
     * @test
     */
    public function processorRefusesInvalidConfigurationOptions()
    {
        $this->expectException(InvalidLogProcessorConfigurationException::class);
        $this->expectExceptionCode(1321696151);

        $invalidConfiguration = [
            'foo' => 'bar'
        ];
        GeneralUtility::makeInstance(ProcessorFixture::class, $invalidConfiguration);
    }

    /**
     * @test
     */
    public function loggerExecutesProcessors()
    {
        $logger = new \TYPO3\CMS\Core\Log\Logger('test.core.log');
        $writer = new \TYPO3\CMS\Core\Log\Writer\NullWriter();
        $level = \TYPO3\CMS\Core\Log\LogLevel::DEBUG;
        $logRecord = new \TYPO3\CMS\Core\Log\LogRecord('dummy', $level, 'message');
        $processor = $this->getMockBuilder(\TYPO3\CMS\Core\Log\Processor\ProcessorInterface::class)
            ->setMethods(['processLogRecord'])
            ->getMock();
        $processor->expects($this->once())->method('processLogRecord')->willReturn($logRecord);

        $logger->addWriter($level, $writer);
        $logger->addProcessor($level, $processor);
        $logger->warning('test');
    }
}
