<?php
declare(strict_types=1);
namespace CAG\CagTests\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Thomas Ruta <email@thomasruta.de>
 * @author Jochen Rieger <j.rieger@connecta.ag>
 */
use Nimut\TestingFramework\TestCase\UnitTestCase;

class FeUserTest extends UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $subject;

    protected function setUp()
    {
        //parent::setUp();
        $this->subject = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();
    }

    protected function tearDown()
    {
        // parent::tearDown();
    }

    /**
     * @test
     */
    public function dummyTestToNotLeaveThisFileEmpty()
    {
        self::markTestIncomplete();
    }

    /**
     * Test if title can be set
     *
     * @test
     */
    public function UsernameCanBeSet()
    {
        $act = 'Test setUsername';
        $this->subject->setUsername($act);
        $this->assertEquals($act, $this->newsDomainModelInstance->getUsername());
    }
}
