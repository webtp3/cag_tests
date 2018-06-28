<?php
namespace TYPO3\CMS\Extbase\Tests\Unit\Mvc\Controller;

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
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;

/**
 * Test case
 */
class CommandControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Controller\CommandController|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface
     */
    protected $commandController;

    /**
     * \Symfony\Component\Console\Output\ConsoleOutput|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockConsoleOutput;

    protected function setUp()
    {
        $this->commandController = $this->getAccessibleMock(\TYPO3\CMS\Extbase\Mvc\Controller\CommandController::class, ['dummyCommand']);
        $this->mockConsoleOutput = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput::class)->disableOriginalConstructor()->getMock();
        $this->commandController->_set('output', $this->mockConsoleOutput);
    }

    /**
     * @test
     */
    public function outputAppendsGivenStringToTheResponseContent()
    {
        $this->mockConsoleOutput->expects($this->once())->method('output')->with('some text');
        $this->commandController->_call('output', 'some text');
    }

    /**
     * @test
     */
    public function outputReplacesArgumentsInGivenString()
    {
        $this->mockConsoleOutput->expects($this->once())->method('output')->with('%2$s %1$s', ['text', 'some']);
        $this->commandController->_call('output', '%2$s %1$s', ['text', 'some']);
    }

    /**
     * @test
     */
    public function quitThrowsStopActionException()
    {
        $this->expectException(StopActionException::class);
        // @TODO expectExceptionCode is 0
        $mockResponse = $this->createMock(\TYPO3\CMS\Extbase\Mvc\Cli\Response::class);
        $this->commandController->_set('response', $mockResponse);
        $this->commandController->_call('quit');
    }

    /**
     * @test
     */
    public function quitSetsResponseExitCode()
    {
        $this->expectException(StopActionException::class);
        // @TODO expectExceptionCode is 0
        $mockResponse = $this->createMock(\TYPO3\CMS\Extbase\Mvc\Cli\Response::class);
        $mockResponse->expects($this->once())->method('setExitCode')->with(123);
        $this->commandController->_set('response', $mockResponse);
        $this->commandController->_call('quit', 123);
    }
}
