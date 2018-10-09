<?php
declare(strict_types = 1);
namespace TYPO3\CMS\Core\Tests\Unit\Configuration\FlexForm\Fixtures;

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
 * Fixture to test hooks from FlexFormTools
 */
class DataStructureIdentifierPostProcessHookReturnEmptyArray
{
    /**
     * Returns an empty array (no match for this hook)
     *
     * @param array $fieldTca
     * @param string $tableName
     * @param string $fieldName
     * @param array $row
     * @param array $identifier
     * @return array
     */
    public function getDataStructureIdentifierPostProcess(
        array $fieldTca,
        string $tableName,
        string $fieldName,
        array $row,
        array $identifier
    ): array {
        return [];
    }
}
