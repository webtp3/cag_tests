<?php
declare(strict_types=1);

return (function () {
    static $classLoader;
    if ($classLoader) {
        return $classLoader;
    }

    // $classLoader = require rtrim(realpath($instancePath . '/typo3'), '\\/') . '/../../Build/vendor/autoload.php';
    if (file_exists($typo3AutoLoadFile = realpath(($rootPath = dirname(__DIR__, 3)) . '/typo3') . '/../../Build/vendor/autoload.php')) {
        // Console is root package, thus vendor folder is .Build/vendor
        putenv('TYPO3_PATH_ROOT=' . $rootPath);
        $classLoader = require $typo3AutoLoadFile;
        $compatClassLoader = require __DIR__ . '/Build/vendor/autoload.php';
    } else if (file_exists($typo3AutoLoadFile = realpath(($rootPath = dirname(__DIR__, 3)) . '/typo3') . '/../../build/vendor/autoload.php')) {
        putenv('TYPO3_PATH_ROOT=' . $rootPath);
        $classLoader = require $typo3AutoLoadFile;
        $compatClassLoader = require __DIR__ . '/Build/vendor/autoload.php';
    } else if (file_exists($typo3AutoLoadFile = realpath(($rootPath = dirname(__DIR__, 3)) . '/typo3') . '/../../.build/vendor/autoload.php')) {
        putenv('TYPO3_PATH_ROOT=' . $rootPath);
        $classLoader = require $typo3AutoLoadFile;
        $compatClassLoader = require __DIR__ . '/Build/vendor/autoload.php';
    } else if (file_exists($typo3AutoLoadFile = realpath(($rootPath = dirname(__DIR__, 3)) . '/typo3') . '/../../.Build/vendor/autoload.php')) {
        putenv('TYPO3_PATH_ROOT=' . $rootPath);
        $classLoader = require $typo3AutoLoadFile;
        $compatClassLoader = require __DIR__ . '/Build/vendor/autoload.php';
    } else {
        echo 'Could not find autoload.php file. TYPO3 Console needs to be installed with composer' . PHP_EOL;
        exit(1);
    }
    if (!file_exists($classLoader)) {
        die('ClassLoader can\'t be loaded. Please check your path or set an environment variable \'TYPO3_PATH_ROOT\' to your root path.');
    }


    \Helhum\Typo3Console\Core\Kernel::$nonComposerCompatClassLoader = $compatClassLoader;

    return $classLoader;
})();
