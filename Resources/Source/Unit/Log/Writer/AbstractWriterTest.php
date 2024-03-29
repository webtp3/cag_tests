<?php
namespace TYPO3\CMS\Core\Tests\Unit\Log\Writer;

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
use TYPO3\CMS\Core\Log\Exception\InvalidLogWriterConfigurationException;

/**
 * Test case
 */
class AbstractWriterTest extends \CAG\CagTests\Core\Unit\UnitTestCase
{
    /**
     * @test
     */
    public function constructThrowsExceptionWithInvalidConfigurationOption()
    {
        $this->expectException(InvalidLogWriterConfigurationException::class);
        $this->expectExceptionCode(1321696152);

        $invalidConfiguration = [
            'foo' => 'bar'
        ];
        $this->getMockForAbstractClass(\TYPO3\CMS\Core\Log\Writer\AbstractWriter::class, [$invalidConfiguration]);
    }
}
