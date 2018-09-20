<?php
namespace typo3\sysext\backend\Tests\Unit\Form\Element;

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

use Prophecy\Argument;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Form\Element\InputDateTimeElement;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Test case
 */
class InputDateTimeElementTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var string Selected timezone backup
     */
    protected $timezoneBackup = '';

    /**
     * We're fiddling with hard timestamps in the tests, but time methods in
     * the system under test do use timezone settings. Therefore we backup the
     * current timezone setting, set it to UTC explicitly and reconstitute it
     * again in tearDown()
     */
    protected function setUp()
    {
        $this->timezoneBackup = date_default_timezone_get();
    }

    /**
     * Tear down
     */
    protected function tearDown()
    {
        date_default_timezone_set($this->timezoneBackup);
        parent::tearDown();
    }

    /**
     * Data provider for renderAppliesCorrectTimestampConversion
     *
     * @return array
     */
    public function renderAppliesCorrectTimestampConversionDataProvider()
    {
        // Three elements: input (UTC), timezone of output, expected output
        return [
            // German standard time (without DST) is one hour ahead of UTC
            'date in 2016 in German timezone' => [
                1457103519, 'Europe/Berlin', '2016-03-04T15:58:39+00:00'
            ],
            'date in 1969 in German timezone' => [
                -7200, 'Europe/Berlin', '1969-12-31T23:00:00+00:00'
            ],
            // Los Angeles is 8 hours behind UTC
            'date in 2016 in Los Angeles timezone' => [
                1457103519, 'America/Los_Angeles', '2016-03-04T06:58:39+00:00'
            ],
            'date in UTC' => [
                1457103519, 'UTC', '2016-03-04T14:58:39+00:00'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider renderAppliesCorrectTimestampConversionDataProvider
     * @param int $input
     * @param string $serverTimezone
     * @param int $expectedOutput
     */
    public function renderAppliesCorrectTimestampConversion($input, $serverTimezone, $expectedOutput)
    {
        date_default_timezone_set($serverTimezone);
        $data = [
            'parameterArray' => [
                'tableName' => 'table_foo',
                'fieldName' => 'field_bar',
                'fieldConf' => [
                    'config' => [
                        'type' => 'input',
                        'dbType' => 'datetime',
                        'eval' => 'datetime',
                        'default' => '0000-00-00 00:00:00'
                    ]
                ],
                'itemFormElValue' => $input
            ]
        ];
        $abstractNode = $this->prophesize(AbstractNode::class);
        $abstractNode->render(Argument::cetera())->willReturn([
            'additionalJavaScriptPost' => [],
            'additionalJavaScriptSubmit' => [],
            'additionalHiddenFields' => [],
            'stylesheetFiles' => [],
        ]);
        $nodeFactoryProphecy = $this->prophesize(NodeFactory::class);
        $nodeFactoryProphecy->create(Argument::cetera())->willReturn($abstractNode->reveal());
        $languageService = $this->prophesize(LanguageService::class);
        $GLOBALS['LANG'] = $languageService->reveal();
        $subject = new InputDateTimeElement($nodeFactoryProphecy->reveal(), $data);
        $result = $subject->render();
        $this->assertContains('<input type="hidden" name="" value="' . $expectedOutput . '" />', $result['html']);
    }
}
