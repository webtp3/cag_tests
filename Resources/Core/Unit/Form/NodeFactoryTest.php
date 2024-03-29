<?php
namespace TYPO3\CMS\Backend\Tests\Unit\Form;

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

use TYPO3\CMS\Backend\Form\Element;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Backend\Form\NodeInterface;
use TYPO3\CMS\Backend\Form\NodeResolverInterface;

/**
 * Test case
 */
class NodeFactoryTest extends \CAG\CagTests\Core\Unit\UnitTestCase
{
    /**
     * @test
     */
    public function constructThrowsExceptionIfOverrideMissesNodeNameKey()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1432207533);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            1433089391 => [
                'class' => 'foo',
                'priority' => 23,
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructThrowsExceptionIfOverrideMissesPriorityKey()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1432207533);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            1433089393 => [
                'nodeName' => 'foo',
                'class' => 'bar',
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructThrowsExceptionIfOverrideMissesClassKey()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1432207533);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            1433089392 => [
                'nodeName' => 'foo',
                'priority' => 23,
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructThrowsExceptionIfOverridePriorityIsLowerThanZero()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1432223531);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            1433089394 => [
                'nodeName' => 'foo',
                'class' => 'bar',
                'priority' => -23,
            ],
        ];
        new NodeFactory();
    }
    /**
     * @test
     */
    public function constructThrowsExceptionIfOverridePriorityIsHigherThanHundred()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1432223531);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            1433089395 => [
                'nodeName' => 'foo',
                'class' => 'bar',
                'priority' => 142,
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructorThrowsExceptionIfOverrideTwoNodesWithSamePriorityAndSameNodeNameAreRegistered()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1432223893);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            1433089396 => [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => 'fooClass',
            ],
            1433089397 => [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => 'barClass',
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructThrowsExceptionIfResolverMissesNodeNameKey()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1433155522);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433154905 => [
                'class' => 'foo',
                'priority' => 23,
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructThrowsExceptionIfResolverMissesPriorityKey()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1433155522);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433154905 => [
                'nodeName' => 'foo',
                'class' => 'bar',
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructThrowsExceptionIfResolverMissesClassKey()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1433155522);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433154906 => [
                'nodeName' => 'foo',
                'priority' => 23,
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructThrowsExceptionIfResolverPriorityIsLowerThanZero()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1433155563);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433154907 => [
                'nodeName' => 'foo',
                'class' => 'bar',
                'priority' => -23,
            ],
        ];
        new NodeFactory();
    }
    /**
     * @test
     */
    public function constructThrowsExceptionIfResolverPriorityIsHigherThanHundred()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1433155563);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433154908 => [
                'nodeName' => 'foo',
                'class' => 'bar',
                'priority' => 142,
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructorThrowsExceptionIfResolverTwoNodesWithSamePriorityAndSameNodeNameAreRegistered()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1433155705);
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433154909 => [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => 'fooClass',
            ],
            1433154910 => [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => 'barClass',
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function constructorThrowsNoExceptionIfResolverWithSamePriorityButDifferentNodeNameAreRegistered()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433154909 => [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => 'fooClass',
            ],
            1433154910 => [
                'nodeName' => 'bar',
                'priority' => 20,
                'class' => 'barClass',
            ],
        ];
        new NodeFactory();
    }

    /**
     * @test
     */
    public function createThrowsExceptionIfRenderTypeIsNotGiven()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1431452406);
        $subject = new NodeFactory();
        $subject->create([]);
    }

    /**
     * @test
     */
    public function createThrowsExceptionIfNodeDoesNotImplementNodeInterface()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1431872546);
        $mockNode = new \stdClass();
        /** @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject $mockSubject */
        $mockSubject = $this->getMockBuilder(NodeFactory::class)
            ->setMethods(['instantiate'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockSubject->expects($this->once())->method('instantiate')->will($this->returnValue($mockNode));
        $mockSubject->create(['renderType' => 'foo']);
    }

    /**
     * @test
     */
    public function createReturnsInstanceOfUnknownElementIfTypeIsNotRegistered()
    {
        $subject = new NodeFactory();
        $this->assertInstanceOf(Element\UnknownElement::class, $subject->create(['renderType' => 'foo']));
    }

    /**
     * @test
     */
    public function createReturnsInstanceOfSelectTreeElementIfNeeded()
    {
        $data = [
            'type' => 'select',
            'renderType' => 'selectTree',
        ];
        $subject = new NodeFactory();
        $this->assertInstanceOf(Element\SelectTreeElement::class, $subject->create($data));
    }

    /**
     * @test
     */
    public function createReturnsInstanceOfSelectSingleElementIfNeeded()
    {
        $data = [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'parameterArray' => [
                'fieldConf' => [
                    'config' => [],
                ],
            ],
        ];
        $subject = new NodeFactory();
        $this->assertInstanceOf(Element\SelectSingleElement::class, $subject->create($data));
    }

    /**
     * @test
     */
    public function createInstantiatesNewRegisteredElement()
    {
        $data = ['renderType' => 'foo'];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => \stdClass::class,
            ],
        ];
        $mockNode = $this->createMock(NodeInterface::class);
        /** @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject $mockSubject */
        $mockSubject = $this->getMockBuilder(NodeFactory::class)
            ->setMethods(['instantiate'])
            ->getMock();
        $mockSubject->expects($this->once())->method('instantiate')->with('stdClass')->will($this->returnValue($mockNode));
        $mockSubject->create($data);
    }

    /**
     * @test
     */
    public function createInstantiatesElementRegisteredWithHigherPriorityWithOneGivenOrder()
    {
        $data = ['renderType' => 'foo'];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            1433089467 => [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => 'foo1Class',
            ],
            1433089468 => [
                'nodeName' => 'foo',
                'priority' => 30,
                'class' => 'foo2Class',
            ],
        ];
        $mockNode = $this->createMock(NodeInterface::class);
        /** @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject $mockSubject */
        $mockSubject = $this->getMockBuilder(NodeFactory::class)
            ->setMethods(['instantiate'])
            ->getMock();
        $mockSubject->expects($this->once())->method('instantiate')->with('foo2Class')->will($this->returnValue($mockNode));
        $mockSubject->create($data);
    }

    /**
     * @test
     */
    public function createInstantiatesElementRegisteredWithHigherPriorityWithOtherGivenOrder()
    {
        $data = ['renderType' => 'foo'];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            1433089469 => [
                'nodeName' => 'foo',
                'priority' => 30,
                'class' => 'foo2Class',
            ],
            1433089470 => [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => 'foo1Class',
            ],
        ];
        $mockNode = $this->createMock(NodeInterface::class);
        /** @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject $mockSubject */
        $mockSubject = $this->getMockBuilder(NodeFactory::class)
            ->setMethods(['instantiate'])
            ->getMock();
        $mockSubject->expects($this->once())->method('instantiate')->with('foo2Class')->will($this->returnValue($mockNode));
        $mockSubject->create($data);
    }

    /**
     * @test
     */
    public function createThrowsExceptionIfResolverDoesNotImplementNodeResolverInterface()
    {
        $this->expectException(\TYPO3\CMS\Backend\Form\Exception::class);
        $this->expectExceptionCode(1433157422);
        $data = ['renderType' => 'foo'];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433156887 => [
                'nodeName' => 'foo',
                'priority' => 10,
                'class' => 'fooClass',
            ],
        ];
        $mockResolver = $this->getMockBuilder(\stdClass::class)->getMock();

        /** @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject $mockSubject */
        $mockSubject = $this->getMockBuilder(NodeFactory::class)
            ->setMethods(['instantiate'])
            ->getMock();
        $mockSubject->expects($this->at(0))->method('instantiate')->will($this->returnValue($mockResolver));
        $mockSubject->create($data);
    }

    /**
     * @test
     */
    public function createInstantiatesResolverWithHighestPriorityFirstWithOneGivenOrder()
    {
        $data = ['renderType' => 'foo'];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => \stdClass::class,
            ],
        ];
        $mockNode = $this->createMock(NodeInterface::class);

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433156887 => [
                'nodeName' => 'foo',
                'priority' => 10,
                'class' => 'foo1Class',
            ],
            1433156888 => [
                'nodeName' => 'foo',
                'priority' => 30,
                'class' => 'foo2Class',
            ],
        ];
        $mockResolver1 = $this->getMockBuilder(NodeResolverInterface::class)->getMock();
        $mockResolver2 = $this->getMockBuilder(NodeResolverInterface::class)->getMock();

        /** @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject $mockSubject */
        $mockSubject = $this->getMockBuilder(NodeFactory::class)
            ->setMethods(['instantiate'])
            ->getMock();
        $mockSubject->expects($this->at(0))->method('instantiate')->with('foo2Class')->will($this->returnValue($mockResolver2));
        $mockSubject->expects($this->at(1))->method('instantiate')->with('foo1Class')->will($this->returnValue($mockResolver1));
        $mockSubject->expects($this->at(2))->method('instantiate')->will($this->returnValue($mockNode));
        $mockSubject->create($data);
    }

    /**
     * @test
     */
    public function createInstantiatesResolverWithHighestPriorityFirstWithOtherGivenOrder()
    {
        $data = ['renderType' => 'foo'];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => \stdClass::class,
            ],
        ];
        $mockNode = $this->createMock(NodeInterface::class);

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433156887 => [
                'nodeName' => 'foo',
                'priority' => 30,
                'class' => 'foo1Class',
            ],
            1433156888 => [
                'nodeName' => 'foo',
                'priority' => 10,
                'class' => 'foo2Class',
            ],
        ];
        $mockResolver1 = $this->getMockBuilder(NodeResolverInterface::class)->getMock();
        $mockResolver2 = $this->getMockBuilder(NodeResolverInterface::class)->getMock();

        /** @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject $mockSubject */
        $mockSubject = $this->getMockBuilder(NodeFactory::class)
            ->setMethods(['instantiate'])
            ->getMock();
        $mockSubject->expects($this->at(0))->method('instantiate')->with('foo1Class')->will($this->returnValue($mockResolver1));
        $mockSubject->expects($this->at(1))->method('instantiate')->with('foo2Class')->will($this->returnValue($mockResolver2));
        $mockSubject->expects($this->at(2))->method('instantiate')->will($this->returnValue($mockNode));
        $mockSubject->create($data);
    }

    /**
     * @test
     */
    public function createInstantiatesNodeClassReturnedByResolver()
    {
        $data = ['renderType' => 'foo'];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => \stdClass::class,
            ],
        ];
        $mockNode = $this->createMock(NodeInterface::class);

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433156887 => [
                'nodeName' => 'foo',
                'priority' => 30,
                'class' => 'foo1Class',
            ],
        ];
        $mockResolver1 = $this->getMockBuilder(NodeResolverInterface::class)->getMock();
        $mockResolver1->expects($this->once())->method('resolve')->will($this->returnValue('fooNodeClass'));

        /** @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject $mockSubject */
        $mockSubject = $this->getMockBuilder(NodeFactory::class)
            ->setMethods(['instantiate'])
            ->getMock();
        $mockSubject->expects($this->at(0))->method('instantiate')->will($this->returnValue($mockResolver1));
        $mockSubject->expects($this->at(1))->method('instantiate')->with('fooNodeClass')->will($this->returnValue($mockNode));
        $mockSubject->create($data);
    }

    /**
     * @test
     */
    public function createDoesNotCallSecondResolverWithLowerPriorityIfFirstResolverReturnedClassName()
    {
        $data = ['renderType' => 'foo'];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'] = [
            [
                'nodeName' => 'foo',
                'priority' => 20,
                'class' => \stdClass::class,
            ],
        ];
        $mockNode = $this->createMock(NodeInterface::class);

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'] = [
            1433156887 => [
                'nodeName' => 'foo',
                'priority' => 30,
                'class' => 'foo1Class',
            ],
            1433156888 => [
                'nodeName' => 'foo',
                'priority' => 10,
                'class' => 'foo2Class',
            ],
        ];
        $mockResolver1 = $this->getMockBuilder(NodeResolverInterface::class)->getMock();
        $mockResolver1->expects($this->once())->method('resolve')->will($this->returnValue('fooNodeClass'));

        /** @var NodeFactory|\PHPUnit_Framework_MockObject_MockObject $mockSubject */
        $mockSubject = $this->getMockBuilder(NodeFactory::class)
            ->setMethods(['instantiate'])
            ->getMock();
        $mockSubject->expects($this->at(0))->method('instantiate')->with('foo1Class')->will($this->returnValue($mockResolver1));
        $mockSubject->expects($this->at(1))->method('instantiate')->with('fooNodeClass')->will($this->returnValue($mockNode));
        $mockSubject->create($data);
    }
}
