<?php
namespace TYPO3\CMS\Core\Tests\Unit\Resource\Rendering;

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

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\YouTubeHelper;
use TYPO3\CMS\Core\Resource\Rendering\YouTubeRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class YouTubeRendererTest
 */
class YouTubeRendererTest extends \CAG\CagTests\Core\Unit\UnitTestCase
{
    /**
     * @var YouTubeRenderer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * Set up the test
     */
    protected function setUp()
    {
        parent::setUp();
        GeneralUtility::flushInternalRuntimeCaches();
        $_SERVER['HTTP_HOST'] = 'test.server.org';

        /** @var YouTubeHelper|\PHPUnit_Framework_MockObject_MockObject $youTubeHelper */
        $youTubeHelper = $this->getAccessibleMock(YouTubeHelper::class, ['getOnlineMediaId'], ['youtube']);
        $youTubeHelper->expects($this->any())->method('getOnlineMediaId')->will($this->returnValue('7331'));

        $this->subject = $this->getAccessibleMock(YouTubeRenderer::class, ['getOnlineMediaHelper'], []);
        $this->subject ->expects($this->any())->method('getOnlineMediaHelper')->will($this->returnValue($youTubeHelper));
    }

    /**
     * @test
     */
    public function getPriorityReturnsCorrectValue()
    {
        $this->assertSame(1, $this->subject->getPriority());
    }

    /**
     * @test
     */
    public function canRenderReturnsTrueOnCorrectFile()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock1 */
        $fileResourceMock1 = $this->createMock(File::class);
        $fileResourceMock1->expects($this->any())->method('getMimeType')->will($this->returnValue('video/youtube'));
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock2 */
        $fileResourceMock2 = $this->createMock(File::class);
        $fileResourceMock2->expects($this->any())->method('getMimeType')->will($this->returnValue('video/unknown'));
        $fileResourceMock2->expects($this->any())->method('getExtension')->will($this->returnValue('youtube'));

        $this->assertTrue($this->subject->canRender($fileResourceMock1));
        $this->assertTrue($this->subject->canRender($fileResourceMock2));
    }

    /**
     * @test
     */
    public function canRenderReturnsFalseOnCorrectFile()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);
        $fileResourceMock->expects($this->any())->method('getMimeType')->will($this->returnValue('video/vimeo'));

        $this->assertFalse($this->subject->canRender($fileResourceMock));
    }

    /**
     * @test
     */
    public function renderOutputWithLoopIsCorrect()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        $this->assertSame(
            '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;loop=1&amp;playlist=7331&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
            $this->subject->render($fileResourceMock, '300m', '200', ['loop' => 1])
        );
    }

    /**
     * @test
     */
    public function renderOutputWithAutoplayIsCorrect()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        $this->assertSame(
            '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;autoplay=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="autoplay; fullscreen"></iframe>',
            $this->subject->render($fileResourceMock, '300m', '200', ['autoplay' => 1])
        );
    }

    /**
     * @test
     */
    public function renderOutputWithAutoplayFromFileReferenceIsCorrect()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        /** @var FileReference|\PHPUnit_Framework_MockObject_MockObject $fileReferenceMock */
        $fileReferenceMock = $this->createMock(FileReference::class);
        $fileReferenceMock->expects($this->any())->method('getProperty')->will($this->returnValue(1));
        $fileReferenceMock->expects($this->any())->method('getOriginalFile')->willReturn($fileResourceMock);

        $this->assertSame(
            '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;autoplay=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="autoplay; fullscreen"></iframe>',
            $this->subject->render($fileReferenceMock, '300m', '200')
        );
    }

    /**
     * @test
     */
    public function renderOutputWithAutoplayAndWithoutControlsIsCorrect()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        $this->assertSame(
            '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=0&amp;autoplay=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="autoplay; fullscreen"></iframe>',
            $this->subject->render($fileResourceMock, '300m', '200', ['controls' => 0, 'autoplay' => 1])
        );
    }

    public function renderOutputWithControlsDataProvider()
    {
        return [
            'no options given' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                []
            ],
            'with option controls = foo as invalid string' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => 'foo']
            ],
            'with option controls = true as string' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => 'true']
            ],
            'with option controls = false as string' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => 'false']
            ],
            'with option controls = true as boolean' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => true]
            ],
            'with option controls = false as boolean' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => false]
            ],
            'with option controls = 0 as string' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=0&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => '0']
            ],
            'with option controls = 1 as string' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => '1']
            ],
            'with option controls = 2 as string' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => '2']
            ],
            'with option controls = 3 as string' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => '3']
            ],
            'with option controls = negative number as string' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=0&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => '-42']
            ],
            'with option controls = 0 as int' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=0&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => 0]
            ],
            'with option controls = 1 as int' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => 1]
            ],
            'with option controls = 2 as int' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => 2]
            ],
            'with option controls = 3 as int' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => 3]
            ],
            'with option controls = negative number as int' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=0&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
                ['controls' => -42]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider renderOutputWithControlsDataProvider
     */
    public function renderOutputWithDefaultControlsIsCorrect($expected, $options)
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        $this->assertSame(
            $expected,
            $this->subject->render($fileResourceMock, '300m', '200', $options)
        );
    }

    /**
     * @test
     */
    public function renderOutputWithRelatedVideosTurnedOffIsCorrect()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        $this->assertSame(
            '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;rel=0&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
            $this->subject->render($fileResourceMock, '300m', '200', ['relatedVideos' => 0])
        );
    }

    /**
     * @test
     */
    public function renderOutputWithDisabledNoCookieIsCorrect()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        $this->assertSame(
            '<iframe src="https://www.youtube.com/embed/7331?autohide=1&amp;controls=0&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
            $this->subject->render($fileResourceMock, '300m', '200', ['controls' => 0, 'no-cookie' => 0])
        );
    }

    /**
     * @test
     */
    public function renderOutputWithModestbrandingIsCorrect()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        $this->assertSame(
            '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=2&amp;modestbranding=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="fullscreen"></iframe>',
            $this->subject->render($fileResourceMock, '300m', '200', ['controls' => 2, 'modestbranding' => 1])
        );
    }

    /**
     * @test
     */
    public function renderOutputWithCustomAllowIsCorrect()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        $this->assertSame(
            '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=0&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="foo; bar"></iframe>',
            $this->subject->render($fileResourceMock, '300m', '200', ['controls' => 0, 'allow' => 'foo; bar'])
        );
    }

    /**
     * @test
     */
    public function renderOutputWithCustomAllowAndAutoplayIsCorrect()
    {
        /** @var File|\PHPUnit_Framework_MockObject_MockObject $fileResourceMock */
        $fileResourceMock = $this->createMock(File::class);

        $this->assertSame(
            '<iframe src="https://www.youtube-nocookie.com/embed/7331?autohide=1&amp;controls=0&amp;autoplay=1&amp;enablejsapi=1&amp;origin=http%3A%2F%2Ftest.server.org&amp;showinfo=0" allowfullscreen width="300" height="200" allow="foo; bar"></iframe>',
            $this->subject->render($fileResourceMock, '300m', '200', ['controls' => 0, 'autoplay' => 1, 'allow' => 'foo; bar'])
        );
    }
}
