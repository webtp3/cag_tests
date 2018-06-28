<?php
namespace TYPO3\CMS\Core\Tests\Acceptance\Backend\Topbar;

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

use Codeception\Scenario;
use TYPO3\TestingFramework\Core\Acceptance\Step\Backend\Admin;
use TYPO3\TestingFramework\Core\Acceptance\Support\Helper\ModalDialog;
use TYPO3\TestingFramework\Core\Acceptance\Support\Helper\Topbar;

/**
 * Test for the "Bookmark" functionality
 */
class BookmarkCest
{
    /**
     * Selector for the module container in the topbar
     *
     * @var string
     */
    public static $topBarModuleSelector = '#typo3-cms-backend-backend-toolbaritems-shortcuttoolbaritem';

    /**
     * Selector for the "Add to bookmark" button
     *
     * @var string
     */
    protected static $docHeaderBookmarkButtonSelector = '.module-docheader .btn[title="Create a bookmark to this page"]';

    /**
     * @param Admin $I
     */
    public function _before(Admin $I)
    {
        $I->useExistingSession();
        // Ensure main content frame is fully loaded, otherwise there are load-race-conditions
        $I->switchToIFrame('list_frame');
        $I->waitForText('Web Content Management System');
        $I->switchToIFrame();
    }

    /**
     * @param Admin $I
     * @return Admin
     */
    public function checkThatBookmarkListIsInitiallyEmpty(Admin $I)
    {
        $this->clickBookmarkDropdownToggleInTopbar($I);
        $I->cantSeeElement(self::$topBarModuleSelector . ' .shortcut');
        return $I;
    }

    /**
     * @depends checkThatBookmarkListIsInitiallyEmpty
     * @param Admin $I
     * @param ModalDialog $dialog
     * @return Admin
     */
    public function checkThatAddingABookmarkAddsItemToTheBookmarkList(Admin $I, ModalDialog $dialog, Scenario $scenario)
    {
        $I->switchToIFrame();
        // open the scheduler module as we would like to put it into the bookmark liste
        $I->click('Scheduler', '.scaffold-modulemenu');

        $I->switchToIFrame('list_frame');

        $I->click(self::$docHeaderBookmarkButtonSelector);
        // cancel the action to test the functionality
        // don't use $modalDialog->clickButtonInDialog due to too low timeout
        $dialog->canSeeDialog();
        $I->click('Cancel', ModalDialog::$openedModalButtonContainerSelector);
        $I->waitForElementNotVisible(ModalDialog::$openedModalSelector, 30);

        // check if the list is still empty
        $this->checkThatBookmarkListIsInitiallyEmpty($I);

        $I->switchToIFrame('list_frame');
        $I->click(self::$docHeaderBookmarkButtonSelector);

        $dialog->clickButtonInDialog('OK');

        $this->clickBookmarkDropdownToggleInTopbar($I);
        $I->waitForText('Scheduled tasks', 15, self::$topBarModuleSelector . ' ' . Topbar::$dropdownListSelector);

        // @test complete test when https://forge.typo3.org/issues/75689 is fixed
        $scenario->comment(
            'Tests for deleting the item in the list and readding it are missing ' .
            'as this is currently broken in the core. See https://forge.typo3.org/issues/75689'
        );

        return $I;
    }

    /**
     * @param Admin $I
     * @depends checkThatAddingABookmarkAddsItemToTheBookmarkList
     */
    public function checkIfBookmarkItemLinksToTarget(Admin $I)
    {
        $this->clickBookmarkDropdownToggleInTopbar($I);
        $I->click('Scheduled tasks', self::$topBarModuleSelector);
        $I->switchToIFrame('list_frame');
        $I->canSee('Scheduled tasks', 'h1');
    }

    /**
     * @param Admin $I
     * @depends checkThatAddingABookmarkAddsItemToTheBookmarkList
     */
    public function checkIfEditBookmarkItemWorks(Admin $I)
    {
        $this->clickBookmarkDropdownToggleInTopbar($I);
        $firstShortcutSelector = self::$topBarModuleSelector . ' .t3js-topbar-shortcut';
        $I->click('.t3js-shortcut-edit', $firstShortcutSelector);
        $secondShortcutSelector = self::$topBarModuleSelector . ' form.shortcut-form';
        $I->fillField($secondShortcutSelector . ' input[name="shortcut-title"]', 'Scheduled tasks renamed');
        $I->click('.shortcut-form-save', $secondShortcutSelector);

        // searching in a specific context fails with an "Stale Element Reference Exception"
        // see http://docs.seleniumhq.org/exceptions/stale_element_reference.jsp
        // currently don't know how to fix that so we search in the whole context.
        $I->waitForText('Scheduled tasks renamed');
    }

    /**
     * @param Admin $I
     * @depends checkThatAddingABookmarkAddsItemToTheBookmarkList
     */
    public function checkIfDeleteBookmarkWorks(Admin $I, ModalDialog $dialog)
    {
        $this->clickBookmarkDropdownToggleInTopbar($I);

        $I->canSee('Scheduled tasks renamed', self::$topBarModuleSelector);
        $I->click('.t3js-shortcut-delete', self::$topBarModuleSelector . ' .t3js-topbar-shortcut');
        $dialog->clickButtonInDialog('OK');

        $I->cantSee('Scheduled tasks renamed', self::$topBarModuleSelector);
    }

    /**
     * @param Admin $I
     */
    protected function clickBookmarkDropdownToggleInTopbar(Admin $I)
    {
        $I->waitForElementVisible(self::$topBarModuleSelector . ' ' . Topbar::$dropdownToggleSelector);
        $I->click(Topbar::$dropdownToggleSelector, self::$topBarModuleSelector);
    }
}
