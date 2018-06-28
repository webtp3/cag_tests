<?php
namespace TYPO3\CMS\Extbase\Tests\Unit\Object\Container;

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
use TYPO3\CMS\Extbase\Object\Container\Exception\UnknownObjectException;

/**
 * Test case
 */
class ClassInfoFactoryTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\Container\ClassInfoFactory
     */
    protected $classInfoFactory;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->classInfoFactory = new \TYPO3\CMS\Extbase\Object\Container\ClassInfoFactory();
    }

    /**
     * @test
     */
    public function buildClassInfoFromClassNameThrowsExceptionIfGivenClassNameCantBeReflected()
    {
        $this->expectException(UnknownObjectException::class);
        $this->expectExceptionCode(1289386765);
        $this->classInfoFactory->buildClassInfoFromClassName('SomeNonExistingClass');
    }

    /**
     * @test
     */
    public function buildClassInfoDoesNotIncludeInjectSettingsMethodInListOfInjectMethods()
    {
        $classInfo = $this->classInfoFactory->buildClassInfoFromClassName('t3lib_object_tests_class_with_injectsettings');
        $this->assertEquals(['injectFoo' => 't3lib_object_tests_resolveablecyclic1'], $classInfo->getInjectMethods());
    }

    /**
     * @test
     */
    public function buildClassInfoDetectsPropertiesToInjectByAnnotation()
    {
        $classInfo = $this->classInfoFactory->buildClassInfoFromClassName(\TYPO3\CMS\Extbase\Tests\Fixture\ClassWithInjectProperties::class);
        $this->assertEquals(['secondDummyClass' => \TYPO3\CMS\Extbase\Tests\Fixture\SecondDummyClass::class], $classInfo->getInjectProperties());
    }

    /**
     * @test
     */
    public function buildClassInfoReturnsCustomClassInfoForDateTime()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject | \TYPO3\CMS\Extbase\Object\Container\ClassInfoFactory $classInfoFactory */
        $classInfoFactory = $this->getMockBuilder(\TYPO3\CMS\Extbase\Object\Container\ClassInfoFactory::class)
            ->setMethods(['getConstructorArguments'])
            ->getMock();
        $classInfoFactory->expects($this->never())->method('getConstructorArguments');

        $classInfo = $classInfoFactory->buildClassInfoFromClassName('DateTime');
        $this->assertEquals(
            new \TYPO3\CMS\Extbase\Object\Container\ClassInfo('DateTime', [], [], false, false, []),
            $classInfo
        );
    }
}
