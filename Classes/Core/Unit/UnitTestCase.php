<?php
declare(strict_types=1);
namespace CAG\CagTests\Core\Unit;

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

use CAG\CagTests\Core\BaseTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Base test case for unit tests.
 *
 * This class currently only inherits the base test case. However, it is recommended
 * to extend this class for unit test cases instead of the base test case because if,
 * at some point, specific behavior needs to be implemented for unit tests, your test cases
 * will profit from it automatically.
 */
abstract class UnitTestCase extends BaseTestCase
{
    /**
     * @todo make LoadedExtensionsArray serializable instead
     *
     * @var array
     */
    protected $backupGlobalsBlacklist = ['TYPO3_LOADED_EXT'];

    /**
     * Absolute path to files that should be removed after a test.
     * Handled in tearDown. Tests can register here to get any files
     * within typo3temp/ or typo3conf/ext cleaned up again.
     *
     * @var array
     */
    protected $testFilesToDelete = [];

    /**
     * Unset all additional properties of test classes to help PHP
     * garbage collection. This reduces memory footprint with lots
     * of tests.
     *
     * If overwriting tearDown() in test classes, please call
     * parent::tearDown() at the end. Unsetting of own properties
     * is not needed this way.
     *
     * @throws \RuntimeException
     * @return void
     */
    protected function tearDown()
    {
        // Flush the two static $indpEnvCache and $idnaStringCache
        // between test runs to prevent side effects from these caches.
        GeneralUtility::flushInternalRuntimeCaches();

        // Unset properties of test classes to safe memory
        $reflection = new \ReflectionObject($this);
        foreach ($reflection->getProperties() as $property) {
            $declaringClass = $property->getDeclaringClass()->getName();
            if (
                !$property->isStatic()
                && $declaringClass !== self::class
                && $declaringClass !== \CAG\CagTests\Core\BaseTestCase::class
                && strpos($property->getDeclaringClass()->getName(), 'PHPUnit') !== 0
            ) {
                $propertyName = $property->getName();
                unset($this->$propertyName);
            }
        }
        unset($reflection);

        // Delete registered test files and directories
        foreach ($this->testFilesToDelete as $absoluteFileName) {
            $absoluteFileName = GeneralUtility::fixWindowsFilePath(PathUtility::getCanonicalPath($absoluteFileName));
            if (!GeneralUtility::validPathStr($absoluteFileName)) {
                throw new \RuntimeException('tearDown() cleanup: Filename contains illegal characters', 1410633087);
            }
            if (strpos($absoluteFileName, PATH_site . 'typo3temp/var/') !== 0) {
                throw new \RuntimeException(
                    'tearDown() cleanup:  Files to delete must be within typo3temp/var/',
                    1410633412
                );
            }
            // file_exists returns false for links pointing to not existing targets, so handle links before next check.
            if (@is_link($absoluteFileName) || @is_file($absoluteFileName)) {
                unlink($absoluteFileName);
            } elseif (@is_dir($absoluteFileName)) {
                GeneralUtility::rmdir($absoluteFileName, true);
            } else {
                throw new \RuntimeException('tearDown() cleanup: File, link or directory does not exist', 1410633510);
            }
        }
        $this->testFilesToDelete = [];
    }
}
