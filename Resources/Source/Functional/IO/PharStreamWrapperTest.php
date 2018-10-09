<?php
declare(strict_types = 1);
namespace TYPO3\CMS\Core\Tests\Functional\IO;

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

use TYPO3\CMS\Core\IO\PharStreamWrapper;
use TYPO3\CMS\Core\IO\PharStreamWrapperException;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class PharStreamWrapperTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3/sysext/core/Tests/Functional/Fixtures/Extensions/test_resources'
    ];

    /**
     * @var array
     */
    protected $pathsToLinkInTestInstance = [
        'typo3/sysext/core/Tests/Functional/Fixtures/Extensions/test_resources/bundle.phar' => 'fileadmin/bundle.phar'
    ];

    protected function setUp()
    {
        parent::setUp();

        if (!in_array('phar', stream_get_wrappers())) {
            $this->markTestSkipped('Phar stream wrapper is not registered');
        }

        stream_wrapper_unregister('phar');
        stream_wrapper_register('phar', PharStreamWrapper::class);
    }

    protected function tearDown()
    {
        stream_wrapper_restore('phar');
        parent::tearDown();
    }

    public function directoryActionAllowsInvocationDataProvider()
    {
        $allowedPath = 'typo3conf/ext/test_resources/bundle.phar';

        return [
            'root directory' => [
                $allowedPath,
                ['Classes', 'Resources']
            ],
            'Classes/Domain/Model directory' => [
                $allowedPath . '/Classes/Domain/Model',
                ['DemoModel.php']
            ],
            'Resources directory' => [
                $allowedPath . '/Resources',
                ['content.txt']
            ],
        ];
    }

    /**
     * @param string $path
     *
     * @test
     * @dataProvider directoryActionAllowsInvocationDataProvider
     */
    public function directoryOpenAllowsInvocation(string $path)
    {
        $path = $this->instancePath . '/' . $path;
        $handle = opendir('phar://' . $path);
        self::assertInternalType('resource', $handle);
    }

    /**
     * @param string $path
     * @param $expectation
     *
     * @test
     * @dataProvider directoryActionAllowsInvocationDataProvider
     */
    public function directoryReadAllowsInvocation(string $path, array $expectation)
    {
        $path = $this->instancePath . '/' . $path;

        $items = [];
        $handle = opendir('phar://' . $path);
        while (false !== $item = readdir($handle)) {
            $items[] = $item;
        }

        self::assertSame($expectation, $items);
    }

    /**
     * @param string $path
     * @param $expectation
     *
     * @test
     * @dataProvider directoryActionAllowsInvocationDataProvider
     */
    public function directoryCloseAllowsInvocation(string $path, array $expectation)
    {
        $path = $this->instancePath . '/' . $path;

        $handle = opendir('phar://' . $path);
        closedir($handle);

        self::assertFalse(is_resource($handle));
    }

    public function directoryActionDeniesInvocationDataProvider()
    {
        $deniedPath = 'fileadmin/bundle.phar';

        return [
            'root directory' => [
                $deniedPath,
                ['Classes', 'Resources']
            ],
            'Classes/Domain/Model directory' => [
                $deniedPath . '/Classes/Domain/Model',
                ['DemoModel.php']
            ],
            'Resources directory' => [
                $deniedPath . '/Resources',
                ['content.txt']
            ],
        ];
    }

    /**
     * @param string $path
     *
     * @test
     * @dataProvider directoryActionDeniesInvocationDataProvider
     */
    public function directoryActionDeniesInvocation(string $path)
    {
        self::expectException(PharStreamWrapperException::class);
        self::expectExceptionCode(1530103998);

        $path = $this->instancePath . '/' . $path;
        opendir('phar://' . $path);
    }

    /**
     * @return array
     */
    public function urlStatAllowsInvocationDataProvider(): array
    {
        $allowedPath = 'typo3conf/ext/test_resources/bundle.phar';

        return [
            'filesize base file' => [
                'filesize',
                $allowedPath,
                0, // Phar base file always has zero size when accessed through phar://
            ],
            'filesize Resources/content.txt' => [
                'filesize',
                $allowedPath . '/Resources/content.txt',
                21,
            ],
            'is_file base file' => [
                'is_file',
                $allowedPath,
                false, // Phar base file is not a file when accessed through phar://
            ],
            'is_file Resources/content.txt' => [
                'is_file',
                $allowedPath . '/Resources/content.txt',
                true,
            ],
            'is_dir base file' => [
                'is_dir',
                $allowedPath,
                true, // Phar base file is a directory when accessed through phar://
            ],
            'is_dir Resources/content.txt' => [
                'is_dir',
                $allowedPath . '/Resources/content.txt',
                false,
            ],
            'file_exists base file' => [
                'file_exists',
                $allowedPath,
                true
            ],
            'file_exists Resources/content.txt' => [
                'file_exists',
                $allowedPath . '/Resources/content.txt',
                true
            ],
        ];
    }

    /**
     * @param string $functionName
     * @param string $path
     * @param mixed $expectation
     *
     * @test
     * @dataProvider urlStatAllowsInvocationDataProvider
     */
    public function urlStatAllowsInvocation(string $functionName, string $path, $expectation)
    {
        $path = $this->instancePath . '/' . $path;

        self::assertSame(
            $expectation,
            call_user_func($functionName, 'phar://' . $path)
        );
    }

    /**
     * @return array
     */
    public function urlStatDeniesInvocationDataProvider(): array
    {
        $deniedPath = 'fileadmin/bundle.phar';

        return [
            'filesize base file' => [
                'filesize',
                $deniedPath,
                0, // Phar base file always has zero size when accessed through phar://
            ],
            'filesize Resources/content.txt' => [
                'filesize',
                $deniedPath . '/Resources/content.txt',
                21,
            ],
            'is_file base file' => [
                'is_file',
                $deniedPath,
                false, // Phar base file is not a file when accessed through phar://
            ],
            'is_file Resources/content.txt' => [
                'is_file',
                $deniedPath . '/Resources/content.txt',
                true,
            ],
            'is_dir base file' => [
                'is_dir',
                $deniedPath,
                true, // Phar base file is a directory when accessed through phar://
            ],
            'is_dir Resources/content.txt' => [
                'is_dir',
                $deniedPath . '/Resources/content.txt',
                false,
            ],
            'file_exists base file' => [
                'file_exists',
                $deniedPath,
                true
            ],
            'file_exists Resources/content.txt' => [
                'file_exists',
                $deniedPath . '/Resources/content.txt',
                true
            ],
        ];
    }

    /**
     * @param string $functionName
     * @param string $path
     * @param mixed $expectation
     *
     * @test
     * @dataProvider urlStatDeniesInvocationDataProvider
     */
    public function urlStatDeniesInvocation(string $functionName, string $path)
    {
        self::expectException(PharStreamWrapperException::class);
        self::expectExceptionCode(1530103998);

        $path = $this->instancePath . '/' . $path;
        call_user_func($functionName, 'phar://' . $path);
    }

    /**
     * @test
     */
    public function streamOpenAllowsInvocationForFileOpen()
    {
        $allowedPath = $this->instancePath . '/typo3conf/ext/test_resources/bundle.phar';
        $handle = fopen('phar://' . $allowedPath . '/Resources/content.txt', 'r');
        self::assertInternalType('resource', $handle);
    }

    /**
     * @test
     */
    public function streamOpenAllowsInvocationForFileRead()
    {
        $allowedPath = $this->instancePath . '/typo3conf/ext/test_resources/bundle.phar';
        $handle = fopen('phar://' . $allowedPath . '/Resources/content.txt', 'r');
        $content = fread($handle, 1024);
        self::assertSame('TYPO3 demo text file.', $content);
    }

    /**
     * @test
     */
    public function streamOpenAllowsInvocationForFileEnd()
    {
        $allowedPath = $this->instancePath . '/typo3conf/ext/test_resources/bundle.phar';
        $handle = fopen('phar://' . $allowedPath . '/Resources/content.txt', 'r');
        fread($handle, 1024);
        self::assertTrue(feof($handle));
    }

    /**
     * @test
     */
    public function streamOpenAllowsInvocationForFileClose()
    {
        $allowedPath = $this->instancePath . '/typo3conf/ext/test_resources/bundle.phar';
        $handle = fopen('phar://' . $allowedPath . '/Resources/content.txt', 'r');
        fclose($handle);
        self::assertFalse(is_resource($handle));
    }

    /**
     * @test
     */
    public function streamOpenAllowsInvocationForFileGetContents()
    {
        $allowedPath = $this->instancePath . '/typo3conf/ext/test_resources/bundle.phar';
        $content = file_get_contents('phar://' . $allowedPath . '/Resources/content.txt');
        self::assertSame('TYPO3 demo text file.', $content);
    }

    /**
     * @test
     */
    public function streamOpenAllowsInvocationForInclude()
    {
        $allowedPath = $this->instancePath . '/typo3conf/ext/test_resources/bundle.phar';
        include('phar://' . $allowedPath . '/Classes/Domain/Model/DemoModel.php');

        self::assertTrue(
            class_exists(
                \TYPO3Demo\Demo\Domain\Model\DemoModel::class,
                false
            )
        );
    }

    /**
     * @test
     */
    public function streamOpenDeniesInvocationForFileOpen()
    {
        self::expectException(PharStreamWrapperException::class);
        self::expectExceptionCode(1530103998);

        $allowedPath = $this->instancePath . '/fileadmin/bundle.phar';
        fopen('phar://' . $allowedPath . '/Resources/content.txt', 'r');
    }

    /**
     * @test
     */
    public function streamOpenDeniesInvocationForFileGetContents()
    {
        self::expectException(PharStreamWrapperException::class);
        self::expectExceptionCode(1530103998);

        $allowedPath = $this->instancePath . '/fileadmin/bundle.phar';
        file_get_contents('phar://' . $allowedPath . '/Resources/content.txt');
    }

    /**
     * @test
     */
    public function streamOpenDeniesInvocationForInclude()
    {
        self::expectException(PharStreamWrapperException::class);
        self::expectExceptionCode(1530103998);

        $allowedPath = $this->instancePath . '/fileadmin/bundle.phar';
        include('phar://' . $allowedPath . '/Classes/Domain/Model/DemoModel.php');
    }
}
