<?php
namespace TYPO3\CMS\Core\Tests\Unit\Locking;

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

use TYPO3\CMS\Core\Locking\SimpleLockStrategy;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Testcase for \TYPO3\CMS\Core\Locking\SimpleLockStrategy
 */
class SimpleLockStrategyTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @test
     */
    public function constructorCreatesLockDirectoryIfNotExisting()
    {
        GeneralUtility::rmdir(PATH_site . SimpleLockStrategy::FILE_LOCK_FOLDER, true);
        new SimpleLockStrategy('999999999');
        $this->assertTrue(is_dir(PATH_site . SimpleLockStrategy::FILE_LOCK_FOLDER));
    }

    /**
     * @test
     */
    public function constructorSetsResourceToPathWithIdIfUsingSimpleLocking()
    {
        $lock = $this->getAccessibleMock(SimpleLockStrategy::class, ['dummy'], ['999999999']);
        $this->assertSame(PATH_site . SimpleLockStrategy::FILE_LOCK_FOLDER . 'simple_' . md5('999999999'), $lock->_get('filePath'));
    }

    /**
     * @test
     */
    public function acquireFixesPermissionsOnLockFile()
    {
        if (TYPO3_OS === 'WIN') {
            $this->markTestSkipped('Test not available on Windows.');
        }
        // Use a very high id to be unique
        /** @var SimpleLockStrategy|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface $lock */
        $lock = $this->getAccessibleMock(SimpleLockStrategy::class, ['dummy'], ['999999999']);

        $pathOfLockFile = $lock->_get('filePath');

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fileCreateMask'] = '0777';

        // Acquire lock, get actual file permissions and clean up
        $lock->acquire();
        clearstatcache();
        $resultFilePermissions = substr(decoct(fileperms($pathOfLockFile)), 2);
        $lock->release();
        $this->assertEquals($resultFilePermissions, '0777');
    }

    /**
     * @test
     */
    public function releaseRemovesLockfileInTypo3TempLocks()
    {
        /** @var SimpleLockStrategy|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface $lock */
        $lock = $this->getAccessibleMock(SimpleLockStrategy::class, ['dummy'], ['999999999']);

        $pathOfLockFile = $lock->_get('filePath');

        $lock->acquire();
        $lock->release();

        $this->assertFalse(is_file($pathOfLockFile));
    }

    /**
     * Dataprovider for releaseDoesNotRemoveFilesNotWithinTypo3TempLocksDirectory
     */
    public function invalidFileReferences()
    {
        return [
            'not within PATH_site' => [tempnam(sys_get_temp_dir(), 'foo')],
            'directory traversal' => [PATH_site . 'typo3temp/../typo3temp/var/locks/foo'],
            'directory traversal 2' => [PATH_site . 'typo3temp/var/locks/../../var/locks/foo'],
            'within uploads' => [PATH_site . 'uploads/TYPO3-Lock-Test']
        ];
    }

    /**
     * @test
     * @dataProvider invalidFileReferences
     * @param string $file
     * @throws \PHPUnit_Framework_SkippedTestError
     */
    public function releaseDoesNotRemoveFilesNotWithinTypo3TempLocksDirectory($file)
    {
        // Create test file
        touch($file);
        if (!is_file($file)) {
            $this->markTestIncomplete('releaseDoesNotRemoveFilesNotWithinTypo3TempLocksDirectory() skipped: Test file could not be created');
        }
        // Create instance, set lock file to invalid path
        /** @var SimpleLockStrategy|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface $lock */
        $lock = $this->getAccessibleMock(SimpleLockStrategy::class, ['dummy'], ['999999999']);
        $lock->_set('filePath', $file);
        $lock->_set('isAcquired', true);

        // Call release method
        $lock->release();
        // Check if file is still there and clean up
        $fileExists = is_file($file);
        if (is_file($file)) {
            unlink($file);
        }
        $this->assertTrue($fileExists);
    }
}
