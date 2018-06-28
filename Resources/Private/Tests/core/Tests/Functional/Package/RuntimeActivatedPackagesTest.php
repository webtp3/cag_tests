<?php
namespace TYPO3\CMS\Core\Tests\Functional\Package;

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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case
 */
class RuntimeActivatedPackagesTest extends FunctionalTestCase
{
    protected $configurationToUseInTestInstance = [
        'EXT' => [
            'runtimeActivatedPackages' => [
                'felogin'
            ]
        ]
    ];

    /**
     * @test
     */
    public function runtimeActivatedPackageIsLoaded()
    {
        $this->assertTrue(ExtensionManagementUtility::isLoaded('felogin'));
    }
}
