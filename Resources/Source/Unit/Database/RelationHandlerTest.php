<?php
namespace TYPO3\CMS\Core\Tests\Unit\Database;

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
 * Test case for \TYPO3\CMS\Core\Database\RelationHandler
 */
class RelationHandlerTest extends \CAG\CagTests\Core\Unit\UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Core\Database\RelationHandler
     */
    protected $subject;

    /**
     */
    protected function setUp()
    {
        $this->subject = $this->getMockBuilder(\TYPO3\CMS\Core\Database\RelationHandler::class)
            ->setMethods(['purgeVersionedIds', 'purgeLiveVersionedIds'])
            ->getMock();
    }

    /**
     * @test
     */
    public function purgeItemArrayReturnsFalseIfVersioningForTableIsDisabled()
    {
        $GLOBALS['TCA']['sys_category']['ctrl']['versioningWS'] = false;

        $this->subject->tableArray = [
            'sys_category' => [1, 2, 3],
        ];

        $this->assertFalse($this->subject->purgeItemArray(0));
    }

    /**
     * @test
     */
    public function purgeItemArrayReturnsTrueIfItemsHaveBeenPurged()
    {
        $GLOBALS['TCA']['sys_category']['ctrl']['versioningWS'] = true;

        $this->subject->tableArray = [
            'sys_category' => [1, 2, 3],
        ];

        $this->subject->expects($this->once())
            ->method('purgeVersionedIds')
            ->with('sys_category', [1, 2, 3])
            ->will($this->returnValue([2]));

        $this->assertTrue($this->subject->purgeItemArray(0));
    }
}
