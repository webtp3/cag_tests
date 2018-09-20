<?php
namespace TYPO3\CMS\Backend\Tests\Unit\Form\FormDataProvider;

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

use TYPO3\CMS\Backend\Form\FormDataProvider\InitializeProcessedTca;

/**
 * Test case
 */
class InitializeProcessedTcaTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var InitializeProcessedTca
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new InitializeProcessedTca();
    }

    /**
     * @test
     */
    public function addDataSetsTableTcaFromGlobalsInResult()
    {
        $input = [
            'tableName' => 'aTable',
        ];
        $expected = [
            'columns' => []
        ];
        $GLOBALS['TCA'][$input['tableName']] = $expected;
        $result = $this->subject->addData($input);
        $this->assertEquals($expected, $result['processedTca']);
    }

    /**
     * @test
     */
    public function addDataKeepsGivenProcessedTca()
    {
        $input = [
            'tableName' => 'aTable',
            'processedTca' => [
                'columns' => [
                    'afield' => [],
                ],
            ],
        ];
        $expected = $input;
        $this->assertEquals($expected, $this->subject->addData($input));
    }

    /**
     * @test
     */
    public function addDataThrowsExceptionIfGlobalTableTcaIsNotSet()
    {
        $input = [
            'tableName' => 'aTable',
        ];

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1437914223);

        $this->subject->addData($input);
    }

    /**
     * @test
     */
    public function addDataThrowsExceptionIfGlobalTableTcaIsNotAnArray()
    {
        $input = [
            'tableName' => 'aTable',
        ];
        $GLOBALS['TCA'][$input['tableName']] = 'foo';
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1437914223);

        $this->subject->addData($input);
    }
}
