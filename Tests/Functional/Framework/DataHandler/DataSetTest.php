<?php
declare(strict_types = 1);
namespace CAG\CagTests\Core\Tests\Unit\Functional\Framework\DataHandler;

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

use CAG\CagTests\Core\Functional\Framework\DataHandling\DataSet;
use CAG\CagTests\Core\Unit\UnitTestCase;


/**
 * Test Case
 */
class DataSetTest extends UnitTestCase
{

    /**
     * @test
     */
    public function handlesUtf8WithoutBom()
    {
        $csvFile = __DIR__ . '/../../../Unit/Core/Fixtures/BOM/WithoutBom.csv';
        $dataSet = DataSet::read($csvFile);
        $tableName = $dataSet->getTableNames()[0];
        $this->assertEquals(strlen('pages'), strlen($tableName));
    }

    /**
     * @test
     */
    public function handlesUtf8WithBom()
    {
        $csvFile = __DIR__ . '/../../../Unit/Core/Fixtures/BOM/WithBom.csv';
        $dataSet = DataSet::read($csvFile);
        $tableName = $dataSet->getTableNames()[0];
        $this->assertNotEquals(strlen('pages'), strlen($tableName));
    }

}
