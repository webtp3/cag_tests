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

use TYPO3\CMS\Core\Locking\Exception\LockCreateException;
use TYPO3\CMS\Core\Locking\FileLockStrategy;
use TYPO3\CMS\Core\Locking\LockFactory;
use TYPO3\CMS\Core\Locking\LockingStrategyInterface;
use TYPO3\CMS\Core\Locking\SemaphoreLockStrategy;
use TYPO3\CMS\Core\Tests\Unit\Locking\Fixtures\DummyLock;

/**
 * Testcase for \TYPO3\CMS\Core\Locking\LockFactory
 */
class LockFactoryTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var LockFactory|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\TestingFramework\Core\AccessibleObjectInterface
     */
    protected $mockFactory;

    /**
     * Set up the tests
     */
    protected function setUp()
    {
        $this->mockFactory = $this->getAccessibleMock(LockFactory::class, ['dummy']);
    }

    /**
     * @test
     */
    public function addLockingStrategyAddsTheClassNameToTheInternalArray()
    {
        $this->mockFactory->addLockingStrategy(DummyLock::class);
        $this->assertArrayHasKey(DummyLock::class, $this->mockFactory->_get('lockingStrategy'));
    }

    /**
     * @test
     */
    public function addLockingStrategyThrowsExceptionIfInterfaceIsNotImplemented()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1425990198);

        $this->mockFactory->addLockingStrategy(\stdClass::class);
    }

    /**
     * @test
     */
    public function getLockerReturnsExpectedClass()
    {
        $this->mockFactory->_set('lockingStrategy', [FileLockStrategy::class => true, DummyLock::class => true]);
        $locker = $this->mockFactory->createLocker('id', LockingStrategyInterface::LOCK_CAPABILITY_EXCLUSIVE | LockingStrategyInterface::LOCK_CAPABILITY_SHARED);
        $this->assertInstanceOf(FileLockStrategy::class, $locker);
    }

    /**
     * @test
     */
    public function getLockerReturnsClassWithHighestPriority()
    {
        $this->mockFactory->_set('lockingStrategy', [SemaphoreLockStrategy::class => true, DummyLock::class => true]);
        $locker = $this->mockFactory->createLocker('id');
        $this->assertInstanceOf(DummyLock::class, $locker);
    }

    /**
     * @test
     */
    public function getLockerThrowsExceptionIfNoMatchFound()
    {
        $this->expectException(LockCreateException::class);
        $this->expectExceptionCode(1425990190);

        $this->mockFactory->createLocker('id', 32);
    }
}
