<?php
namespace TYPO3\CMS\Frontend\Tests\Unit\ContentObject;

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
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Tests\Unit\ContentObject\Fixtures\DataProcessorFixture;

/**
 * Testcase for TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor
 */
class ContentDataProcessorTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var ContentDataProcessor
     */
    protected $contentDataProcessor = null;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->contentDataProcessor = new ContentDataProcessor();
    }

    /**
     * @test
     */
    public function throwsExceptionIfProcessorClassDoesNotExist()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1427455378);
        $contentObjectRendererStub = new ContentObjectRenderer();
        $config = [
            'dataProcessing.' => [
                '10' => 'fooClass'
            ]
        ];
        $variables = [];
        $this->contentDataProcessor->process($contentObjectRendererStub, $config, $variables);
    }

    /**
     * @test
     */
    public function throwsExceptionIfProcessorClassDoesNotImplementInterface()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1427455377);
        $contentObjectRendererStub = new ContentObjectRenderer();
        $config = [
            'dataProcessing.' => [
                '10' => get_class($this)
            ]
        ];
        $variables = [];
        $this->contentDataProcessor->process($contentObjectRendererStub, $config, $variables);
    }

    /**
     * @test
     */
    public function processorIsCalled()
    {
        $contentObjectRendererStub = new ContentObjectRenderer();
        $config = [
            'dataProcessing.' => [
                '10' => DataProcessorFixture::class,
                '10.' => ['foo' => 'bar'],
            ]
        ];
        $variables = [];
        $this->assertSame(['foo' => 'bar'], $this->contentDataProcessor->process($contentObjectRendererStub, $config, $variables));
    }
}
