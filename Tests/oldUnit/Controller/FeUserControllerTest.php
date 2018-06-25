<?php
namespace Cag\CagTests\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Thomas Ruta <email@thomasruta.de>
 * @author Jochen Rieger <j.rieger@connecta.ag>
 */
use Nimut\TestingFramework\TestCase\UnitTestCase;

class FeUserControllerTest extends UnitTestCase
{
    /**
     * @var \Cag\CagTests\Controller\FeUserController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\Cag\CagTests\Controller\FeUserController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllFeUsersFromRepositoryAndAssignsThemToView()
    {

        $allFeUsers = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $feUserRepository = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $feUserRepository->expects(self::once())->method('findAll')->will(self::returnValue($allFeUsers));
        $this->inject($this->subject, 'feUserRepository', $feUserRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('feUsers', $allFeUsers);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenFeUserToView()
    {
        $feUser = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('feUser', $feUser);

        $this->subject->showAction($feUser);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenFeUserToFeUserRepository()
    {
        $feUser = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();

        $feUserRepository = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $feUserRepository->expects(self::once())->method('add')->with($feUser);
        $this->inject($this->subject, 'feUserRepository', $feUserRepository);

        $this->subject->createAction($feUser);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenFeUserToView()
    {
        $feUser = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('feUser', $feUser);

        $this->subject->editAction($feUser);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenFeUserInFeUserRepository()
    {
        $feUser = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();

        $feUserRepository = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $feUserRepository->expects(self::once())->method('update')->with($feUser);
        $this->inject($this->subject, 'feUserRepository', $feUserRepository);

        $this->subject->updateAction($feUser);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenFeUserFromFeUserRepository()
    {
        $feUser = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();

        $feUserRepository = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $feUserRepository->expects(self::once())->method('remove')->with($feUser);
        $this->inject($this->subject, 'feUserRepository', $feUserRepository);

        $this->subject->deleteAction($feUser);
    }
    /**
     * @test
     */
    public function loginActionTestFeUserLogin()
    {
        $feUser = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();
        $this->testingFramework->createFakeFrontEnd();
        $feUserId = $this->testingFramework->createFrontEndUser();
        $this->testingFramework->loginFrontEndUser($feUserId);

    }
}
