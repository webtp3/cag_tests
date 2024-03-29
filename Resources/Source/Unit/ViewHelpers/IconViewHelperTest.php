<?php
namespace TYPO3\CMS\Core\Tests\Unit\ViewHelpers;

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
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Type\Icon\IconState;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\ViewHelpers\IconViewHelper;
use CAG\CagTests\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

/**
 * Test case
 */
class IconViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var IconViewHelper
     */
    protected $viewHelper;

    protected function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->getAccessibleMock(IconViewHelper::class, ['renderChildren']);
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
    }

    /**
     * @test
     */
    public function renderCallsIconFactoryWithDefaultSizeAndDefaultStateAndReturnsResult()
    {
        $iconFactoryProphecy = $this->prophesize(IconFactory::class);
        GeneralUtility::addInstance(IconFactory::class, $iconFactoryProphecy->reveal());
        $iconProphecy = $this->prophesize(Icon::class);

        $iconFactoryProphecy->getIcon('myIdentifier', Icon::SIZE_SMALL, null, IconState::cast(IconState::STATE_DEFAULT))->shouldBeCalled()->willReturn($iconProphecy->reveal());
        $iconProphecy->render(null)->shouldBeCalled()->willReturn('htmlFoo');

        $this->viewHelper->setArguments([
            'identifier' => 'myIdentifier',
            'size' => Icon::SIZE_SMALL
        ]);

        $this->assertSame('htmlFoo', $this->viewHelper->render());
    }

    /**
     * @test
     */
    public function renderCallsIconFactoryWithGivenSizeAndReturnsResult()
    {
        $iconFactoryProphecy = $this->prophesize(IconFactory::class);
        GeneralUtility::addInstance(IconFactory::class, $iconFactoryProphecy->reveal());
        $iconProphecy = $this->prophesize(Icon::class);

        $iconFactoryProphecy->getIcon('myIdentifier', Icon::SIZE_LARGE, null, IconState::cast(IconState::STATE_DEFAULT))->shouldBeCalled()->willReturn($iconProphecy->reveal());
        $iconProphecy->render(null)->shouldBeCalled()->willReturn('htmlFoo');

        $this->viewHelper->setArguments([
            'identifier' => 'myIdentifier',
            'size' => Icon::SIZE_LARGE
        ]);

        $this->assertSame('htmlFoo', $this->viewHelper->render());
    }

    /**
     * @test
     */
    public function renderCallsIconFactoryWithGivenStateAndReturnsResult()
    {
        $iconFactoryProphecy = $this->prophesize(IconFactory::class);
        GeneralUtility::addInstance(IconFactory::class, $iconFactoryProphecy->reveal());
        $iconProphecy = $this->prophesize(Icon::class);

        $iconFactoryProphecy->getIcon('myIdentifier', Icon::SIZE_SMALL, null, IconState::cast(IconState::STATE_DISABLED))->shouldBeCalled()->willReturn($iconProphecy->reveal());
        $iconProphecy->render(null)->shouldBeCalled()->willReturn('htmlFoo');

        $this->viewHelper->setArguments([
            'identifier' => 'myIdentifier',
            'size' => Icon::SIZE_SMALL,
            'overlay' => null,
            'state' => IconState::cast(IconState::STATE_DISABLED)
        ]);

        $this->assertSame('htmlFoo', $this->viewHelper->render());
    }

    /**
     * @test
     */
    public function renderCallsIconFactoryWithGivenOverlayAndReturnsResult()
    {
        $iconFactoryProphecy = $this->prophesize(IconFactory::class);
        GeneralUtility::addInstance(IconFactory::class, $iconFactoryProphecy->reveal());
        $iconProphecy = $this->prophesize(Icon::class);

        $iconFactoryProphecy->getIcon('myIdentifier', Argument::any(), 'overlayString', IconState::cast(IconState::STATE_DEFAULT))->shouldBeCalled()->willReturn($iconProphecy->reveal());
        $iconProphecy->render(null)->shouldBeCalled()->willReturn('htmlFoo');

        $this->viewHelper->setArguments([
            'identifier' => 'myIdentifier',
            'size' => Icon::SIZE_LARGE,
            'overlay' => 'overlayString'
        ]);
        $this->assertSame('htmlFoo', $this->viewHelper->render());
    }
}
