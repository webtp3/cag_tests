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

use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew;

/**
 * Test case
 */
class DatabaseRowInitializeNewTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var DatabaseRowInitializeNew
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new DatabaseRowInitializeNew();
    }

    /**
     * @test
     */
    public function addDataReturnSameDataIfCommandIsEdit()
    {
        $input = [
            'command' => 'edit',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [
                'uid' => 42,
            ],
            'userTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'uid' => 23,
                    ],
                ],
            ],
        ];
        $this->assertSame($input, $this->subject->addData($input));
    }

    /**
     * @test
     */
    public function addDataThrowsExceptionIfDatabaseRowIsNotArray()
    {
        $input = [
            'command' => 'new',
            'databaseRow' => 'not-an-array',
        ];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1444431128);
        $this->subject->addData($input);
    }

    /**
     * @test
     */
    public function addDataKeepsGivenDefaultsIfCommandIsNew()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [
                'aField' => 42,
            ],
        ];
        $expected = $input;
        $expected['databaseRow']['pid'] = 23;
        $this->assertSame($expected, $this->subject->addData($input));
    }

    /**
     * @test
     */
    public function addDataSetsDefaultDataFromUserTsIfColumnIsDefinedInTca()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'userTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'userTsValue',
                    ],
                ],
            ],
            'processedTca' => [
                'columns' => [
                    'aField' => [],
                ],
            ]
        ];
        $expected = [
            'aField' => 'userTsValue',
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataDoesNotSetDefaultDataFromUserTsIfColumnIsMissingInTca()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'userTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'userTsValue',
                    ],
                ],
            ],
            'processedTca' => [
                'columns' => [],
            ]
        ];
        $expected = [
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataSetsDefaultDataFromPageTsIfColumnIsDefinedInTca()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'pageTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'pageTsValue',
                    ],
                ],
            ],
            'processedTca' => [
                'columns' => [
                    'aField' => [],
                ],
            ]
        ];
        $expected = [
            'aField' => 'pageTsValue',
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataDoesNotSetDefaultDataFromPageTsIfColumnIsMissingInTca()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'pageTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'pageTsValue',
                    ],
                ],
            ],
            'processedTca' => [
                'columns' => [],
            ]
        ];
        $expected = [
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataSetsDefaultDataOverrulingFromPageTs()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'pageTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'pageTsValue',
                    ],
                ],
            ],
            'userTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'userTsValue',
                    ],
                ],
            ],
            'processedTca' => [
                'columns' => [
                    'aField' => [],
                ],
            ]
        ];
        $expected = [
            'aField' => 'pageTsValue',
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataSetsDefaultFromNeighborRow()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'neighborRow' => [
                'aField' => 'valueFromNeighbor',
            ],
            'processedTca' => [
                'ctrl' => [
                    'useColumnsForDefaultValues' => 'aField',
                ],
                'columns' => [
                    'aField' => [],
                ],
            ],
        ];
        $expected = [
            'aField' => 'valueFromNeighbor',
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataSetsDefaultDataOverrulingFromNeighborRow()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'neighborRow' => [
                'aField' => 'valueFromNeighbor',
            ],
            'pageTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'pageTsValue',
                    ],
                ],
            ],
            'userTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'userTsValue',
                    ],
                ],
            ],
            'processedTca' => [
                'ctrl' => [
                    'useColumnsForDefaultValues' => 'aField',
                ],
                'columns' => [
                    'aField' => [],
                ],
            ],
        ];
        $expected = [
            'aField' => 'valueFromNeighbor',
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataSetsDefaultDataFromGetIfColumnIsDefinedInTca()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'processedTca' => [
                'columns' => [
                    'aField' => [],
                ],
            ]
        ];
        $GLOBALS['_GET'] = [
            'defVals' => [
                'aTable' => [
                    'aField' => 'getValue',
                ],
            ],
        ];
        $expected = [
            'aField' => 'getValue',
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataSetsDefaultDataFromPostIfColumnIsDefinedInTca()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'processedTca' => [
                'columns' => [
                    'aField' => [],
                ],
            ]
        ];
        $GLOBALS['_POST'] = [
            'defVals' => [
                'aTable' => [
                    'aField' => 'postValue',
                ],
            ],
        ];
        $expected = [
            'aField' => 'postValue',
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataSetsPrioritizesDefaultPostOverDefaultGet()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'processedTca' => [
                'columns' => [
                    'aField' => [],
                ],
            ]
        ];
        $GLOBALS['_GET'] = [
            'defVals' => [
                'aTable' => [
                    'aField' => 'getValue',
                ],
            ],
        ];
        $GLOBALS['_POST'] = [
            'defVals' => [
                'aTable' => [
                    'aField' => 'postValue',
                ],
            ],
        ];
        $expected = [
            'aField' => 'postValue',
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataDoesNotSetDefaultDataFromGetPostIfColumnIsMissingInTca()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'userTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'pageTsValue',
                    ],
                ],
            ],
            'processedTca' => [
                'columns' => [],
            ]
        ];
        $GLOBALS['_GET'] = [
            'defVals' => [
                'aTable' => [
                    'aField' => 'getValue',
                ],
            ],
        ];
        $GLOBALS['_POST'] = [
            'defVals' => [
                'aTable' => [
                    'aField' => 'postValue',
                ],
            ],
        ];
        $expected = [
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataSetsDefaultDataOverrulingGetPost()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'neighborRow' => [
                'aField' => 'valueFromNeighbor',
            ],
            'pageTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'pageTsValue',
                    ],
                ],
            ],
            'userTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'aField' => 'userTsValue',
                    ],
                ],
            ],
            'processedTca' => [
                'ctrl' => [
                    'useColumnsForDefaultValues' => 'aField',
                ],
                'columns' => [
                    'aField' => [],
                ],
            ],
        ];
        $GLOBALS['_POST'] = [
            'defVals' => [
                'aTable' => [
                    'aField' => 'postValue',
                ],
            ],
        ];
        $expected = [
            'aField' => 'postValue',
            'pid' => 23,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataThrowsExceptionWithGivenChildChildUidButMissingInlineConfig()
    {
        $input = [
            'command' => 'new',
            'databaseRow' => [],
            'inlineChildChildUid' => 42,
        ];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1444434102);
        $this->subject->addData($input);
    }

    /**
     * @test
     */
    public function addDataThrowsExceptionWithGivenChildChildUidButIsNotInteger()
    {
        $input = [
            'command' => 'new',
            'databaseRow' => [],
            'inlineChildChildUid' => '42',
        ];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1444434103);
        $this->subject->addData($input);
    }

    /**
     * @test
     */
    public function addDataSetsForeignSelectorFieldToValueOfChildChildUid()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'databaseRow' => [],
            'inlineChildChildUid' => 42,
            'inlineParentConfig' => [
                'foreign_selector' => 'theForeignSelectorField',
            ],
            'processedTca' => [
                'columns' => [
                    'theForeignSelectorField' => [
                        'config' => [
                            'type' => 'group',
                        ],
                    ],
                ],
            ],
        ];
        $expected = $input;
        $expected['databaseRow']['theForeignSelectorField'] = 42;
        $expected['databaseRow']['pid'] = null;
        $this->assertSame($expected, $this->subject->addData($input));
    }

    /**
     * @test
     */
    public function addDataThrowsExceptionIfForeignSelectorDoesNotPointToGroupOrSelectField()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'databaseRow' => [],
            'inlineChildChildUid' => 42,
            'inlineParentConfig' => [
                'foreign_selector' => 'theForeignSelectorField',
            ],
            'processedTca' => [
                'columns' => [
                    'theForeignSelectorField' => [
                        'config' => [
                            'type' => 'input',
                        ],
                    ],
                ],
            ],
        ];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1444434104);
        $this->subject->addData($input);
    }

    /**
     * @test
     */
    public function addDataThrowsExceptionIfInlineParentLanguageIsNoInteger()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'databaseRow' => [],
            'inlineParentConfig' => [
                'inline' => [
                    'parentSysLanguageUid' => 'not-an-integer',
                ],
            ],
            'processedTca' => [
                'ctrl' => [
                    'languageField' => 'sys_language_uid',
                    'transOrigPointerField' => 'l10n_parent',
                ],
                'columns' => [],
            ],
        ];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1490360772);
        $this->subject->addData($input);
    }

    /**
     * @test
     */
    public function addDataSetsSysLanguageUidFromParent()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 1,
            'databaseRow' => [],
            'inlineParentConfig' => [
                'inline' => [
                    'parentSysLanguageUid' => '42',
                ],
            ],
            'processedTca' => [
                'ctrl' => [
                    'languageField' => 'sys_language_uid',
                    'transOrigPointerField' => 'l10n_parent',
                ],
                'columns' => [],
            ],
        ];
        $expected = $input;
        $expected['databaseRow'] = [
            'sys_language_uid' => 42,
            'pid' => 1,
        ];
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function addDataSetsPidToVanillaUid()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
        ];
        $expected['pid'] = 23;
        $result = $this->subject->addData($input);
        $this->assertSame($expected, $result['databaseRow']);
    }

    /**
     * @test
     */
    public function addDataDoesNotUsePageTsValueForPidIfRecordIsNotInlineChild()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'pageTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'pid' => '42',
                    ],
                ],
            ],
            'isInlineChild' => false,
        ];
        $expected = $input;
        $expected['databaseRow']['pid'] = 23;
        $this->assertSame($expected, $this->subject->addData($input));
    }

    /**
     * @test
     */
    public function addDataThrowsExceptionIfPageTsConfigPidValueCanNotBeInterpretedAsInteger()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'pageTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'pid' => 'notAnInteger',
                    ],
                ],
            ],
            'isInlineChild' => true,
        ];
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1461598332);
        $this->subject->addData($input);
    }

    /**
     * @test
     */
    public function addDataDoesUsePageTsValueForPidIfRecordIsInlineChild()
    {
        $input = [
            'command' => 'new',
            'tableName' => 'aTable',
            'vanillaUid' => 23,
            'databaseRow' => [],
            'pageTsConfig' => [
                'TCAdefaults.' => [
                    'aTable.' => [
                        'pid' => '42',
                    ],
                ],
            ],
            'isInlineChild' => true,
        ];
        $expected = $input;
        $expected['databaseRow']['pid'] = 42;
        $this->assertSame($expected, $this->subject->addData($input));
    }
}
