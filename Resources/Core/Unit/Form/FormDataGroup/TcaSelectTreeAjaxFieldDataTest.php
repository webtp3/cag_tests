<?php
namespace TYPO3\CMS\Backend\Tests\Unit\Form\FormDataGroup;

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
use Prophecy\Prophecy\ObjectProphecy;
use TYPO3\CMS\Backend\Form\FormDataGroup\TcaSelectTreeAjaxFieldData;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Service\DependencyOrderingService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use CAG\CagTests\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class TcaSelectTreeAjaxFieldDataTest extends UnitTestCase
{
    /**
     * @var TcaSelectTreeAjaxFieldData
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new TcaSelectTreeAjaxFieldData();
    }

    /**
     * @test
     */
    public function compileReturnsIncomingData()
    {
        /** @var DependencyOrderingService|ObjectProphecy $orderingServiceProphecy */
        $orderingServiceProphecy = $this->prophesize(DependencyOrderingService::class);
        GeneralUtility::addInstance(DependencyOrderingService::class, $orderingServiceProphecy->reveal());
        $orderingServiceProphecy->orderByDependencies(Argument::cetera())->willReturnArgument(0);

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaSelectTreeAjaxFieldData'] = [];

        $input = ['foo'];

        $this->assertEquals($input, $this->subject->compile($input));
    }

    /**
     * @test
     */
    public function compileReturnsResultChangedByDataProvider()
    {
        /** @var DependencyOrderingService|ObjectProphecy $orderingServiceProphecy */
        $orderingServiceProphecy = $this->prophesize(DependencyOrderingService::class);
        GeneralUtility::addInstance(DependencyOrderingService::class, $orderingServiceProphecy->reveal());
        $orderingServiceProphecy->orderByDependencies(Argument::cetera())->willReturnArgument(0);

        /** @var FormDataProviderInterface|ObjectProphecy $formDataProviderProphecy */
        $formDataProviderProphecy = $this->prophesize(FormDataProviderInterface::class);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaSelectTreeAjaxFieldData'] = [
            FormDataProviderInterface::class => [],
        ];
        GeneralUtility::addInstance(FormDataProviderInterface::class, $formDataProviderProphecy->reveal());
        $providerResult = ['foo'];
        $formDataProviderProphecy->addData(Argument::cetera())->shouldBeCalled()->willReturn($providerResult);

        $this->assertEquals($providerResult, $this->subject->compile([]));
    }

    /**
     * @test
     */
    public function compileThrowsExceptionIfDataProviderDoesNotImplementInterface()
    {
        /** @var DependencyOrderingService|ObjectProphecy $orderingServiceProphecy */
        $orderingServiceProphecy = $this->prophesize(DependencyOrderingService::class);
        GeneralUtility::addInstance(DependencyOrderingService::class, $orderingServiceProphecy->reveal());
        $orderingServiceProphecy->orderByDependencies(Argument::cetera())->willReturnArgument(0);

        /** @var FormDataProviderInterface|ObjectProphecy $formDataProviderProphecy */
        $formDataProviderProphecy = $this->prophesize(\stdClass::class);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaSelectTreeAjaxFieldData'] = [
            \stdClass::class => [],
        ];
        GeneralUtility::addInstance(\stdClass::class, $formDataProviderProphecy->reveal());

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1485299408);

        $this->subject->compile([]);
    }
}
