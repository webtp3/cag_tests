<?php
namespace TYPO3\CMS\Core\Tests\Unit\Tca;

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

use TYPO3\CMS\Backend\Tests\Functional\Form\FormTestService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

class BackendGroupsVisibleFieldsTest extends \CAG\CagTests\Core\Functional\FunctionalTestCase
{
    protected static $backendGroupsFields = [
        'hidden',
        'title',
        'description',
        'subgroup',
        'groupMods',
        'tables_select',
        'tables_modify',
        'pagetypes_select',
        'non_exclude_fields',
        'explicit_allowdeny',
        'allowed_languages',
        'workspace_perms',
        'db_mountpoints',
        'file_mountpoints',
        'file_permissions',
        'category_perms',
        'lockToDomain',
        'hide_in_lists',
        'TSconfig',
    ];

    /**
     * @test
     */
    public function backendGroupsFormContainsExpectedFields()
    {
        $this->setUpBackendUserFromFixture(1);
        $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);

        $formEngineTestService = GeneralUtility::makeInstance(FormTestService::class);
        $formResult = $formEngineTestService->createNewRecordForm('be_groups');

        foreach (static::$backendGroupsFields as $expectedField) {
            $this->assertNotFalse(
                $formEngineTestService->formHtmlContainsField($expectedField, $formResult['html']),
                'The field ' . $expectedField . ' is not in the HTML'
            );
        }
    }
}
